<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tehsil extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'name',
        'is_active',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function unionCouncils()
    {
        return $this->hasMany(UnionCouncil::class);
    }

    public function activeUnionCouncils()
    {
        return $this->unionCouncils()->where('is_active', true);
    }
}
