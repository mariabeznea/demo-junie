<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ShoppingListItem extends Pivot
{
    protected $table = 'shopping_list_items';

    protected $casts = [
        'is_completed' => 'boolean',
        'quantity' => 'integer',
    ];

    protected $fillable = [
        'shopping_list_id',
        'grocery_item_id',
        'quantity',
        'is_completed',
        'notes',
    ];
}
