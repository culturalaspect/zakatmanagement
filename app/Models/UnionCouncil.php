<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnionCouncil extends Model
{
    use HasFactory;

    protected $fillable = [
        'tehsil_id',
        'name',
        'is_active',
    ];

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function activeVillages()
    {
        return $this->villages()->where('is_active', true);
    }
}
