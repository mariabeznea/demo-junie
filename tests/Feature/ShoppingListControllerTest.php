<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\GroceryItem;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class ShoppingListControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guests_cannot_access_shopping_lists(): void
    {
        $response = $this->get(route('shopping-lists.index'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_users_can_view_shopping_lists_index(): void
    {
        $user = User::factory()->create();
        ShoppingList::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('shopping-lists.index'));

        $response->assertStatus(200);
        $response->assertViewIs('shopping-lists.index');
        $response->assertViewHas('shoppingLists');
    }

    #[Test]
    public function authenticated_users_can_create_and_add_new_grocery_item(): void
    {
        $user = User::factory()->create();
        $shoppingList = ShoppingList::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Fresh Apples',
            'category' => 'Produce',
            'description' => 'Red delicious apples',
            'unit' => 'kg',
            'quantity' => 2,
            'notes' => 'Get organic if available',
        ];

        $response = $this->actingAs($user)->post(
            route('shopping-lists.items.create-and-add', $shoppingList),
            $data
        );

        $response->assertRedirect(route('shopping-lists.show', $shoppingList));
        $response->assertSessionHas('success', 'New item created and added to shopping list.');

        // Check that grocery item was created
        $this->assertDatabaseHas('grocery_items', [
            'name' => 'Fresh Apples',
            'category' => 'Produce',
            'description' => 'Red delicious apples',
            'unit' => 'kg',
        ]);

        // Check that item was added to shopping list
        $groceryItem = GroceryItem::where('name', 'Fresh Apples')->first();
        $this->assertDatabaseHas('shopping_list_items', [
            'shopping_list_id' => $shoppingList->id,
            'grocery_item_id' => $groceryItem->id,
            'quantity' => 2,
            'notes' => 'Get organic if available',
            'is_completed' => false,
        ]);
    }

    #[Test]
    public function create_and_add_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $shoppingList = ShoppingList::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(
            route('shopping-lists.items.create-and-add', $shoppingList),
            []
        );

        $response->assertSessionHasErrors(['name', 'category', 'unit', 'quantity']);
    }

    #[Test]
    public function create_and_add_works_without_optional_fields(): void
    {
        $user = User::factory()->create();
        $shoppingList = ShoppingList::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Milk',
            'category' => 'Dairy',
            'unit' => 'liter',
            'quantity' => 1,
        ];

        $response = $this->actingAs($user)->post(
            route('shopping-lists.items.create-and-add', $shoppingList),
            $data
        );

        $response->assertRedirect(route('shopping-lists.show', $shoppingList));
        $response->assertSessionHas('success', 'New item created and added to shopping list.');

        // Check that grocery item was created without optional fields
        $this->assertDatabaseHas('grocery_items', [
            'name' => 'Milk',
            'category' => 'Dairy',
            'unit' => 'liter',
            'description' => null,
        ]);

        // Check that item was added to shopping list without optional notes
        $groceryItem = GroceryItem::where('name', 'Milk')->first();
        $this->assertDatabaseHas('shopping_list_items', [
            'shopping_list_id' => $shoppingList->id,
            'grocery_item_id' => $groceryItem->id,
            'quantity' => 1,
            'notes' => null,
            'is_completed' => false,
        ]);
    }

    #[Test]
    public function users_cannot_create_and_add_items_to_other_users_shopping_lists(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $shoppingList = ShoppingList::factory()->create(['user_id' => $otherUser->id]);

        $data = [
            'name' => 'Bread',
            'category' => 'Bakery',
            'unit' => 'piece',
            'quantity' => 1,
        ];

        $response = $this->actingAs($user)->post(
            route('shopping-lists.items.create-and-add', $shoppingList),
            $data
        );

        $response->assertStatus(403);
    }

    #[Test]
    public function create_and_add_validates_quantity_constraints(): void
    {
        $user = User::factory()->create();
        $shoppingList = ShoppingList::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'Test Item',
            'category' => 'Test',
            'unit' => 'piece',
            'quantity' => 0, // Invalid quantity
        ];

        $response = $this->actingAs($user)->post(
            route('shopping-lists.items.create-and-add', $shoppingList),
            $data
        );

        $response->assertSessionHasErrors(['quantity']);

        // Test maximum quantity
        $data['quantity'] = 1000; // Above max of 999

        $response = $this->actingAs($user)->post(
            route('shopping-lists.items.create-and-add', $shoppingList),
            $data
        );

        $response->assertSessionHasErrors(['quantity']);
    }

    #[Test]
    public function create_and_add_validates_string_length_constraints(): void
    {
        $user = User::factory()->create();
        $shoppingList = ShoppingList::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => str_repeat('a', 256), // Too long
            'category' => str_repeat('b', 101), // Too long
            'unit' => str_repeat('c', 51), // Too long
            'description' => str_repeat('d', 501), // Too long
            'notes' => str_repeat('e', 256), // Too long
            'quantity' => 1,
        ];

        $response = $this->actingAs($user)->post(
            route('shopping-lists.items.create-and-add', $shoppingList),
            $data
        );

        $response->assertSessionHasErrors(['name', 'category', 'unit', 'description', 'notes']);
    }
}
