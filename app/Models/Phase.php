<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $fillable = [
        'installment_id',
        'district_id',
        'scheme_id',
        'name',
        'phase_number',
        'max_beneficiaries',
        'max_amount',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'max_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
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
    
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
    
    /**
     * Generate auto phase name
     */
    public static function generateName($installment, $district, $phaseNumber, $scheme = null)
    {
        $financialYear = $installment->fundAllocation->financialYear->name ?? '';
        $installmentNumber = $installment->installment_number;
        $districtName = $district->name;
        $schemeName = $scheme ? $scheme->name : '';
        
        $parts = [];
        if ($financialYear) $parts[] = $financialYear;
        $parts[] = "Installment # {$installmentNumber}";
        if ($districtName) $parts[] = $districtName;
        if ($schemeName) $parts[] = $schemeName;
        $parts[] = "Phase {$phaseNumber}";
        
        return trim(implode(' ', $parts));
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function getCurrentBeneficiariesCountAttribute()
    {
        return $this->beneficiaries()->whereIn('status', ['submitted', 'approved', 'paid'])->count();
    }

    public function getCurrentAmountAttribute()
    {
        return $this->beneficiaries()->whereIn('status', ['submitted', 'approved', 'paid'])->sum('amount');
    }

    /**
     * Automatically close phases where end_date has passed
     */
    public static function closeExpiredPhases()
    {
        $expiredPhases = static::where('status', 'open')
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->toDateString())
            ->get();
        
        foreach ($expiredPhases as $phase) {
            $phase->update(['status' => 'closed']);
            
            // Notify district users about automatic phase closure
            $districtUsers = \App\Models\User::where('role', 'district_user')
                ->where('district_id', $phase->district_id)
                ->get();
            
            foreach ($districtUsers as $user) {
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'type' => 'phase_status_changed',
                    'title' => 'Phase Automatically Closed',
                    'message' => 'Phase "' . $phase->name . '" has been automatically closed as the end date has passed.',
                    'notifiable_type' => self::class,
                    'notifiable_id' => $phase->id,
                ]);
            }
        }
        
        return $expiredPhases->count();
    }

    /**
     * Check if phase is expired
     */
    public function isExpired()
    {
        return $this->end_date && $this->end_date < now()->toDateString();
    }
}
