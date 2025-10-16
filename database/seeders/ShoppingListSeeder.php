<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ShoppingList;
use App\Models\GroceryItem;
use Carbon\Carbon;

class ShoppingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the start of this week (Monday)
        $weekStart = Carbon::parse('2025-10-13'); // Monday of the week containing 2025-10-15

        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating a test user first.');
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $users = collect([$user]);
        }

        // Get some grocery items to add to shopping lists
        $groceryItems = GroceryItem::take(10)->get();

        foreach ($users as $user) {
            // Create a shopping list for this week
            $shoppingList = ShoppingList::create([
                'user_id' => $user->id,
                'name' => 'Weekly Shopping - Week of ' . $weekStart->format('M d, Y'),
                'week_start_date' => $weekStart,
                'status' => 'active',
            ]);

            // Add some grocery items to the shopping list
            $selectedItems = $groceryItems->random(min(7, $groceryItems->count()));

            foreach ($selectedItems as $item) {
                $shoppingList->groceryItems()->attach($item->id, [
                    'quantity' => rand(1, 3),
                    'is_completed' => false,
                    'notes' => null,
                ]);
            }

            $this->command->info("Created shopping list for user: {$user->name}");
        }
    }
}
