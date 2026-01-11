<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_quota_id',
        'scheme_id',
        'percentage',
        'amount',
        'beneficiaries_count',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function districtQuota()
    {
        return $this->belongsTo(DistrictQuota::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}
