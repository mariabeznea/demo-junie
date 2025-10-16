<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ShoppingListItemCreateRequest extends FormRequest
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
            'grocery_item_id' => 'required|exists:grocery_items,id',
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
            'grocery_item_id.required' => 'Please select a grocery item.',
            'grocery_item_id.exists' => 'The selected grocery item is invalid.',
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be a number.',
            'quantity.min' => 'The quantity must be at least 1.',
            'quantity.max' => 'The quantity may not be greater than 999.',
            'notes.max' => 'The notes may not be greater than 255 characters.',
        ];
    }
}
