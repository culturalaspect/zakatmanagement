<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'percentage',
        'has_age_restriction',
        'minimum_age',
        'is_lump_sum',
        'is_active',
        'is_institutional',
        'institutional_type',
        'allows_representative',
        'beneficiary_required_fields',
        'representative_required_fields',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:2',
            'has_age_restriction' => 'boolean',
            'is_lump_sum' => 'boolean',
            'is_active' => 'boolean',
            'is_institutional' => 'boolean',
            'allows_representative' => 'boolean',
            'beneficiary_required_fields' => 'array',
            'representative_required_fields' => 'array',
        ];
    }

    public function categories()
    {
        return $this->hasMany(SchemeCategory::class);
    }

    public function schemeDistributions()
    {
        return $this->hasMany(SchemeDistribution::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function getInstitutionalTypeLabelAttribute()
    {
        return match($this->institutional_type) {
            'educational' => 'Educational Stipend',
            'madarsa' => 'Madarsa Stipend',
            'health' => 'Health Stipend',
            default => null,
        };
    }
}
