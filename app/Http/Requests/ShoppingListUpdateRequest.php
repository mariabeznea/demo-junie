<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ShoppingListUpdateRequest extends FormRequest
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
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,in_progress,completed',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The shopping list name is required.',
            'name.max' => 'The shopping list name may not be greater than 255 characters.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be one of: pending, in progress, or completed.',
        ];
    }
}
