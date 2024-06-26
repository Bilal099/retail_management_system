<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'transaction_type',
        'transaction_type_id',
        'transaction_date',
        'merchant_id',
        'total_amount',
        'payment_type',
        'comment',
        'reference_id',
        'reference_model',
        'is_check',
        'is_cancel',
        'is_complete',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    /**
     * Get all of the comments for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }


    public function transactionType()
    {
         return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'id');
    }
}
