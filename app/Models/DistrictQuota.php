<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_id',
        'district_id',
        'percentage',
        'total_beneficiaries',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function schemeDistributions()
    {
        return $this->hasMany(SchemeDistribution::class);
    }
}
