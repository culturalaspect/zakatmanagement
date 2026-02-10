<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LZCMember extends Model
{
    use HasFactory;

    protected $table = 'lzc_members';

    protected $fillable = [
        'local_zakat_committee_id',
        'cnic',
        'full_name',
        'father_husband_name',
        'mobile_number',
        'date_of_birth',
        'gender',
        'designation',
        'start_date',
        'end_date',
        'is_active',
        'verification_status',
        'verification_remarks',
        'rejection_reason',
        'verified_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'verified_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function localZakatCommittee()
    {
        return $this->belongsTo(LocalZakatCommittee::class);
    }

    public function isActiveDuring($date = null)
    {
        $date = $date ?? now();
        return $this->is_active 
            && $this->start_date <= $date 
            && ($this->end_date === null || $this->end_date >= $date);
    }

    /**
     * Check if a member with the given CNIC is currently active
     */
    public static function hasActiveMember($cnic, $excludeId = null)
    {
        $query = self::where('cnic', $cnic)
            ->where('is_active', true)
            ->where('start_date', '<=', now());
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        // Check if member is active (no end_date or end_date is in future)
        $query->where(function($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
        
        return $query->exists();
    }

    /**
     * Deactivate members whose tenure has expired
     */
    public static function deactivateExpiredMembers()
    {
        return self::where('is_active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->update(['is_active' => false]);
    }
}
