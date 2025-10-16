<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ShoppingListItemCreateAndAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('shoppingList'));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1|max:999',
            'notes' => 'nullable|string|max:255',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The item name is required.',
            'name.max' => 'The item name may not be greater than 255 characters.',
            'category.required' => 'The category is required.',
            'category.max' => 'The category may not be greater than 100 characters.',
            'description.max' => 'The description may not be greater than 500 characters.',
            'unit.required' => 'The unit is required.',
            'unit.max' => 'The unit may not be greater than 50 characters.',
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least 1.',
            'quantity.max' => 'The quantity may not be greater than 999.',
            'notes.max' => 'The notes may not be greater than 255 characters.',
        ];
    }
}
