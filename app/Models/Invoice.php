<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_id',
        'reference_model',
        'total_amount',
        'comment',
        'transaction_id',
        'remaining_amount',
        'invoice_date',
        'is_check',
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    /**
     * If column reference_model set as "Merchant" than use this relation  
    */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'reference_id', 'id');
    }
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
