<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroceryItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'description',
        'unit',
    ];

    public function shoppingLists()
    {
        return $this->belongsToMany(ShoppingList::class, 'shopping_list_items')
                    ->withPivot('quantity', 'is_completed', 'notes')
                    ->withTimestamps();
    }
}
