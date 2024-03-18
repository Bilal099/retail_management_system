<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryMapping extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_from',
        'quantity_from',
        'price_from',
        'product_to',
        'quantity_to',
        'price_to',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
