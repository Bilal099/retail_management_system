<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'inventory_id',
        'unit_price',
        'remaining_quantity'  
    ];
}
