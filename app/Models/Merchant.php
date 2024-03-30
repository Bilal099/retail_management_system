<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchant extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'merchant_name',
        'phone',
        'address',
        'details'
    ];

    /**
     * Get all of the transactions for the Merchant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'merchant_id', 'id');
    }

}
