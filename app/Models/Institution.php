<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'code',
        'district_id',
        'tehsil_id',
        'union_council_id',
        'village_id',
        'mohalla_id',
        'address',
        'phone',
        'email',
        'principal_director_name',
        'registration_number',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function unionCouncil()
    {
        return $this->belongsTo(UnionCouncil::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function mohalla()
    {
        return $this->belongsTo(Mohalla::class);
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'middle_school' => 'Middle School',
            'high_school' => 'High School',
            'college' => 'College',
            'university' => 'University',
            'madarsa' => 'Madarsa',
            'hospital' => 'Hospital',
            default => $this->type,
        };
    }

    public static function generateCode($type)
    {
        $prefix = match($type) {
            'middle_school' => 'MS',
            'high_school' => 'HS',
            'college' => 'COL',
            'university' => 'UNI',
            'madarsa' => 'MAD',
            'hospital' => 'HOS',
            default => 'INS',
        };
        
        $count = self::where('type', $type)->count() + 1;
        $code = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        
        while (self::where('code', $code)->exists()) {
            $count++;
            $code = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }
}
