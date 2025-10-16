<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\GroceryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class GroceriesItemControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guests_cannot_access_groceries_items(): void
    {
        $response = $this->get(route('groceries-items.index'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_users_can_view_groceries_items_index(): void
    {
        $user = User::factory()->create();
        GroceryItem::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('groceries-items.index'));

        $response->assertStatus(200);
        $response->assertViewIs('groceries-items.index');
        $response->assertViewHas('groceriesItems');
    }

    #[Test]
    public function authenticated_users_can_view_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('groceries-items.create'));

        $response->assertStatus(200);
        $response->assertViewIs('groceries-items.create');
    }

    #[Test]
    public function authenticated_users_can_store_grocery_item(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Item',
            'category' => 'Test Category',
            'unit' => 'kg',
            'description' => 'Test description',
        ];

        $response = $this->actingAs($user)->post(route('groceries-items.store'), $data);

        $response->assertRedirect(route('groceries-items.index'));
        $response->assertSessionHas('success', 'Grocery item created successfully.');
        $this->assertDatabaseHas('grocery_items', $data);
    }

    #[Test]
    public function store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('groceries-items.store'), []);

        $response->assertSessionHasErrors(['name', 'category', 'unit']);
    }

    #[Test]
    public function authenticated_users_can_view_grocery_item(): void
    {
        $user = User::factory()->create();
        $item = GroceryItem::factory()->create();

        $response = $this->actingAs($user)->get(route('groceries-items.show', $item));

        $response->assertStatus(200);
        $response->assertViewIs('groceries-items.show');
        $response->assertViewHas('groceriesItem', $item);
    }

    #[Test]
    public function authenticated_users_can_view_edit_form(): void
    {
        $user = User::factory()->create();
        $item = GroceryItem::factory()->create();

        $response = $this->actingAs($user)->get(route('groceries-items.edit', $item));

        $response->assertStatus(200);
        $response->assertViewIs('groceries-items.edit');
        $response->assertViewHas('groceriesItem', $item);
    }

    #[Test]
    public function authenticated_users_can_update_grocery_item(): void
    {
        $user = User::factory()->create();
        $item = GroceryItem::factory()->create();
        $data = [
            'name' => 'Updated Item',
            'category' => 'Updated Category',
            'unit' => 'lbs',
            'description' => 'Updated description',
        ];

        $response = $this->actingAs($user)->put(route('groceries-items.update', $item), $data);

        $response->assertRedirect(route('groceries-items.index'));
        $response->assertSessionHas('success', 'Grocery item updated successfully.');
        $this->assertDatabaseHas('grocery_items', array_merge(['id' => $item->id], $data));
    }

    #[Test]
    public function update_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $item = GroceryItem::factory()->create();

        $response = $this->actingAs($user)->put(route('groceries-items.update', $item), []);

        $response->assertSessionHasErrors(['name', 'category', 'unit']);
    }

    #[Test]
    public function authenticated_users_can_delete_grocery_item(): void
    {
        $user = User::factory()->create();
        $item = GroceryItem::factory()->create();

        $response = $this->actingAs($user)->delete(route('groceries-items.destroy', $item));

        $response->assertRedirect(route('groceries-items.index'));
        $response->assertSessionHas('success', 'Grocery item deleted successfully.');
        $this->assertDatabaseMissing('grocery_items', ['id' => $item->id]);
    }
}
