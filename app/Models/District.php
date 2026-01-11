<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'population',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function localZakatCommittees()
    {
        return $this->hasMany(LocalZakatCommittee::class);
    }

    public function districtQuotas()
    {
        return $this->hasMany(DistrictQuota::class);
    }

    public function phases()
    {
        return $this->hasMany(Phase::class);
    }

    public function beneficiaries()
    {
        return $this->hasManyThrough(
            Beneficiary::class,
            Phase::class,
            'district_id', // Foreign key on phases table
            'phase_id',    // Foreign key on beneficiaries table
            'id',          // Local key on districts table
            'id'           // Local key on phases table
        );
    }

    public function tehsils()
    {
        return $this->hasMany(Tehsil::class);
    }

    public function activeTehsils()
    {
        return $this->tehsils()->where('is_active', true);
    }
}
