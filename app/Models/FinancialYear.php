<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'total_allocation',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'total_allocation' => 'decimal:2',
        ];
    }

    public function fundAllocations()
    {
        return $this->hasMany(FundAllocation::class);
    }

    /**
     * Get the current financial year
     */
    public static function getCurrent()
    {
        return static::where('is_current', true)->first();
    }

    /**
     * Set this financial year as current (and unset others)
     */
    public function setAsCurrent()
    {
        // Unset all other current financial years
        static::where('is_current', true)->update(['is_current' => false]);
        
        // Set this one as current
        $this->update(['is_current' => true]);
    }

    /**
     * Get formatted name (e.g., "2025-26")
     */
    public function getFormattedNameAttribute()
    {
        return $this->name;
    }
}
