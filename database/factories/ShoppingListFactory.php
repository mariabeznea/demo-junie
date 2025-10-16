<?php

namespace Database\Factories;

use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShoppingList>
 */
class ShoppingListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShoppingList::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $weekCounter = 0;
        $weekStart = now()->startOfWeek()->addWeeks($weekCounter++);

        return [
            'user_id' => User::factory(),
            'name' => 'Weekly Shopping - Week of ' . $weekStart->format('M d, Y'),
            'week_start_date' => $weekStart->format('Y-m-d'),
            'status' => $this->faker->randomElement(['active', 'completed', 'cancelled']),
        ];
    }

    /**
     * Indicate that the shopping list is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the shopping list is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the shopping list is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the shopping list is for this week.
     */
    public function thisWeek(): static
    {
        $weekStart = now()->startOfWeek();

        return $this->state(fn (array $attributes) => [
            'name' => 'Weekly Shopping - Week of ' . $weekStart->format('M d, Y'),
            'week_start_date' => $weekStart->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the shopping list is for next week.
     */
    public function nextWeek(): static
    {
        $weekStart = now()->addWeek()->startOfWeek();

        return $this->state(fn (array $attributes) => [
            'name' => 'Weekly Shopping - Week of ' . $weekStart->format('M d, Y'),
            'week_start_date' => $weekStart->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the shopping list belongs to a specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
