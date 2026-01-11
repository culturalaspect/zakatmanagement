<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fund_allocation_id',
        'installment_number',
        'installment_amount',
        'release_date',
    ];

    protected function casts(): array
    {
        return [
            'installment_amount' => 'decimal:2',
            'release_date' => 'date',
        ];
    }

    public function fundAllocation()
    {
        return $this->belongsTo(FundAllocation::class);
    }

    public function districtQuotas()
    {
        return $this->hasMany(DistrictQuota::class);
    }
}
