<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mohalla extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_id',
        'name',
        'is_active',
    ];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function localZakatCommittees()
    {
        return $this->belongsToMany(LocalZakatCommittee::class, 'local_zakat_committee_mohalla');
    }
}
