<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryRepresentative extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_id',
        'cnic',
        'full_name',
        'father_husband_name',
        'mobile_number',
        'date_of_birth',
        'gender',
        'relationship',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}
