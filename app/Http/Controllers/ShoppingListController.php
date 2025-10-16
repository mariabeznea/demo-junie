<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ShoppingListCreateRequest;
use App\Http\Requests\ShoppingListUpdateRequest;
use App\Http\Requests\ShoppingListItemCreateRequest;
use App\Http\Requests\ShoppingListItemUpdateRequest;
use App\Http\Requests\ShoppingListItemCreateAndAddRequest;
use App\Models\GroceryItem;
use App\Models\ShoppingList;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

final class ShoppingListController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $shoppingLists = ShoppingList::query()
            ->with(['user', 'groceryItems'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('shopping-lists.index', compact('shoppingLists'));
    }

    public function show(ShoppingList $shoppingList): View
    {
        $this->authorize('view', $shoppingList);

        $shoppingList->load(['groceryItems', 'user']);
        $availableGroceryItems = GroceryItem::query()
            ->whereNotIn('id', $shoppingList->groceryItems->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('shopping-lists.show', compact('shoppingList', 'availableGroceryItems'));
    }

    public function create(): View
    {
        return view('shopping-lists.create');
    }

    public function store(ShoppingListCreateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $shoppingList = ShoppingList::query()->create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'Shopping list created successfully.');
    }

    public function edit(ShoppingList $shoppingList): View
    {
        $this->authorize('update', $shoppingList);

        return view('shopping-lists.edit', compact('shoppingList'));
    }

    public function update(ShoppingListUpdateRequest $request, ShoppingList $shoppingList): RedirectResponse
    {
        $validated = $request->validated();

        $shoppingList->update($validated);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'Shopping list updated successfully.');
    }

    public function destroy(ShoppingList $shoppingList): RedirectResponse
    {
        $this->authorize('delete', $shoppingList);

        $shoppingList->delete();

        return redirect()
            ->route('shopping-lists.index')
            ->with('success', 'Shopping list deleted successfully.');
    }

    public function addItem(ShoppingListItemCreateRequest $request, ShoppingList $shoppingList): RedirectResponse
    {
        $validated = $request->validated();

        $shoppingList->groceryItems()->attach($validated['grocery_item_id'], [
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
            'is_completed' => false,
        ]);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'Item added to shopping list.');
    }

    public function createAndAddItem(ShoppingListItemCreateAndAddRequest $request, ShoppingList $shoppingList): RedirectResponse
    {
        $this->authorize('update', $shoppingList);

        $validated = $request->validated();

        // Create the new grocery item
        $groceryItem = GroceryItem::query()->create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'unit' => $validated['unit'],
        ]);

        // Add the newly created item to the shopping list
        $shoppingList->groceryItems()->attach($groceryItem->id, [
            'quantity' => $validated['quantity'],
            'notes' => $validated['notes'] ?? null,
            'is_completed' => false,
        ]);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'New item created and added to shopping list.');
    }

    public function updateItem(ShoppingListItemUpdateRequest $request, ShoppingList $shoppingList, GroceryItem $groceryItem): RedirectResponse
    {
        $validated = $request->validated();

        $shoppingList->groceryItems()->updateExistingPivot($groceryItem->id, $validated);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'Item updated successfully.');
    }

    public function removeItem(ShoppingList $shoppingList, GroceryItem $groceryItem): RedirectResponse
    {
        $this->authorize('update', $shoppingList);

        $shoppingList->groceryItems()->detach($groceryItem->id);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'Item removed from shopping list.');
    }

    public function toggleItemStatus(Request $request, ShoppingList $shoppingList, GroceryItem $groceryItem): RedirectResponse
    {
        $this->authorize('update', $shoppingList);

        $currentStatus = $shoppingList->groceryItems()
            ->where('grocery_item_id', $groceryItem->id)
            ->first()
            ->pivot
            ->is_completed;

        $shoppingList->groceryItems()->updateExistingPivot($groceryItem->id, [
            'is_completed' => !$currentStatus,
        ]);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'Item status updated.');
    }

    public function updateStatus(Request $request, ShoppingList $shoppingList): RedirectResponse
    {
        $this->authorize('update', $shoppingList);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $shoppingList->update($validated);

        return redirect()
            ->route('shopping-lists.show', $shoppingList)
            ->with('success', 'Shopping list status updated.');
    }
}
