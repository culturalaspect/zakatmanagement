<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_id',
        'scheme_id',
        'scheme_category_id',
        'local_zakat_committee_id',
        'institution_id',
        'cnic',
        'full_name',
        'father_husband_name',
        'mobile_number',
        'date_of_birth',
        'gender',
        'class',
        'amount',
        'requires_representative',
        'status',
        'rejection_remarks',
        'district_remarks',
        'admin_remarks',
        'submitted_by',
        'approved_by',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'jazzcash_amount',
        'jazzcash_charges',
        'jazzcash_total',
        'jazzcash_status',
        'jazzcash_reason',
        'jazzcash_tid',
        'payment_received_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'amount' => 'decimal:2',
            'jazzcash_amount' => 'decimal:2',
            'jazzcash_charges' => 'decimal:2',
            'jazzcash_total' => 'decimal:2',
            'requires_representative' => 'boolean',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'payment_received_at' => 'datetime',
        ];
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function schemeCategory()
    {
        return $this->belongsTo(SchemeCategory::class);
    }

    public function localZakatCommittee()
    {
        return $this->belongsTo(LocalZakatCommittee::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function representative()
    {
        return $this->hasOne(BeneficiaryRepresentative::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->age;
    }
}
