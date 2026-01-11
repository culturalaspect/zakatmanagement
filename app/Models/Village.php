<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'union_council_id',
        'name',
        'is_active',
    ];

    public function unionCouncil()
    {
        return $this->belongsTo(UnionCouncil::class);
    }

    public function mohallas()
    {
        return $this->hasMany(Mohalla::class);
    }

    public function activeMohallas()
    {
        return $this->mohallas()->where('is_active', true);
    }
}
