<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_year_id',
        'total_amount',
        'date',
        'source',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function phases()
    {
        return $this->hasMany(Phase::class);
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }
}
