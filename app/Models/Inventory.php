<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'quantity_in_stock',
        'last_restock_date'
    ];

    /**
     * Get all of the comments for the Inventory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventoryDetail()
    {
        return $this->hasMany(InventoryDetail::class, 'inventory_id', 'id');
    }
}
