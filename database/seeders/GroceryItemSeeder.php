<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GroceryItem;

class GroceryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groceryItems = [
            // Fruits
            ['name' => 'Apples', 'category' => 'Fruits', 'description' => 'Fresh red apples', 'unit' => 'kg'],
            ['name' => 'Bananas', 'category' => 'Fruits', 'description' => 'Ripe yellow bananas', 'unit' => 'kg'],
            ['name' => 'Oranges', 'category' => 'Fruits', 'description' => 'Fresh oranges', 'unit' => 'kg'],
            ['name' => 'Strawberries', 'category' => 'Fruits', 'description' => 'Fresh strawberries', 'unit' => 'pack'],

            // Vegetables
            ['name' => 'Carrots', 'category' => 'Vegetables', 'description' => 'Fresh carrots', 'unit' => 'kg'],
            ['name' => 'Broccoli', 'category' => 'Vegetables', 'description' => 'Fresh broccoli', 'unit' => 'piece'],
            ['name' => 'Tomatoes', 'category' => 'Vegetables', 'description' => 'Fresh tomatoes', 'unit' => 'kg'],
            ['name' => 'Onions', 'category' => 'Vegetables', 'description' => 'Yellow onions', 'unit' => 'kg'],
            ['name' => 'Potatoes', 'category' => 'Vegetables', 'description' => 'Fresh potatoes', 'unit' => 'kg'],

            // Dairy
            ['name' => 'Milk', 'category' => 'Dairy', 'description' => 'Fresh whole milk', 'unit' => 'liter'],
            ['name' => 'Eggs', 'category' => 'Dairy', 'description' => 'Fresh eggs', 'unit' => 'dozen'],
            ['name' => 'Cheese', 'category' => 'Dairy', 'description' => 'Cheddar cheese', 'unit' => 'pack'],
            ['name' => 'Yogurt', 'category' => 'Dairy', 'description' => 'Greek yogurt', 'unit' => 'pack'],

            // Meat & Fish
            ['name' => 'Chicken Breast', 'category' => 'Meat', 'description' => 'Fresh chicken breast', 'unit' => 'kg'],
            ['name' => 'Ground Beef', 'category' => 'Meat', 'description' => 'Fresh ground beef', 'unit' => 'kg'],
            ['name' => 'Salmon', 'category' => 'Fish', 'description' => 'Fresh salmon fillet', 'unit' => 'kg'],

            // Pantry
            ['name' => 'Bread', 'category' => 'Bakery', 'description' => 'Whole wheat bread', 'unit' => 'loaf'],
            ['name' => 'Rice', 'category' => 'Pantry', 'description' => 'White rice', 'unit' => 'kg'],
            ['name' => 'Pasta', 'category' => 'Pantry', 'description' => 'Spaghetti pasta', 'unit' => 'pack'],
            ['name' => 'Olive Oil', 'category' => 'Pantry', 'description' => 'Extra virgin olive oil', 'unit' => 'bottle'],
        ];

        foreach ($groceryItems as $item) {
            GroceryItem::create($item);
        }
    }
}
