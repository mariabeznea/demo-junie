<?php

namespace Database\Factories;

use App\Models\GroceryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroceryItem>
 */
class GroceryItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroceryItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Fruits', 'Vegetables', 'Dairy', 'Meat', 'Seafood', 'Bakery', 'Pantry', 'Frozen', 'Beverages', 'Snacks'];
        $units = ['piece', 'kg', 'g', 'liter', 'ml', 'pack', 'bottle', 'can', 'box'];

        return [
            'name' => $this->faker->words(2, true),
            'category' => $this->faker->randomElement($categories),
            'description' => $this->faker->optional(0.7)->sentence(),
            'unit' => $this->faker->randomElement($units),
        ];
    }

    /**
     * Indicate that the grocery item is a fruit.
     */
    public function fruit(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'Fruits',
            'unit' => 'piece',
        ]);
    }

    /**
     * Indicate that the grocery item is a vegetable.
     */
    public function vegetable(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'Vegetables',
            'unit' => $this->faker->randomElement(['piece', 'kg', 'g']),
        ]);
    }

    /**
     * Indicate that the grocery item is a dairy product.
     */
    public function dairy(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'Dairy',
            'unit' => $this->faker->randomElement(['liter', 'ml', 'pack']),
        ]);
    }
}
