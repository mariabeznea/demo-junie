<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'week_start_date',
        'status',
    ];

    protected $casts = [
        'week_start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groceryItems()
    {
        return $this->belongsToMany(GroceryItem::class, 'shopping_list_items')
                    ->withPivot('quantity', 'is_completed', 'notes')
                    ->withTimestamps()
                    ->using(\App\Models\ShoppingListItem::class);
    }
}
