<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalZakatCommittee extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'code',
        'name',
        'area_coverage',
        'formation_date',
        'tenure_end_date',
        'tenure_years',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'formation_date' => 'date',
            'tenure_end_date' => 'date',
        ];
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function members()
    {
        return $this->hasMany(LZCMember::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function activeMembers()
    {
        return $this->members()->where('is_active', true);
    }

    public function mohallas()
    {
        return $this->belongsToMany(Mohalla::class, 'local_zakat_committee_mohalla');
    }

    public static function generateCode($districtId)
    {
        $district = District::find($districtId);
        if (!$district) {
            return null;
        }

        // Get district abbreviation (first 3 letters, uppercase)
        $districtAbbr = strtoupper(substr($district->name, 0, 3));
        
        // Get the count of existing committees for this district
        $count = self::where('district_id', $districtId)->count() + 1;
        
        // Generate code: District abbreviation + sequential number (e.g., GIL001, GIL002)
        $code = $districtAbbr . str_pad($count, 3, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness
        while (self::where('code', $code)->exists()) {
            $count++;
            $code = $districtAbbr . str_pad($count, 3, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }
}
