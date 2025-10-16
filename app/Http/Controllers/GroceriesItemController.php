<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateGroceriesItemRequest;
use App\Http\Requests\UpdateGroceriesItemRequest;
use App\Models\GroceryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class GroceriesItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $groceriesItems = GroceryItem::query()->latest()->paginate(15);

        return view('groceries-items.index', compact('groceriesItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('groceries-items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateGroceriesItemRequest $request): RedirectResponse
    {
        GroceryItem::query()->create($request->validated());

        return redirect()->route('groceries-items.index')
            ->with('success', 'Grocery item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroceryItem $groceriesItem): View
    {
        return view('groceries-items.show', compact('groceriesItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroceryItem $groceriesItem): View
    {
        return view('groceries-items.edit', compact('groceriesItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroceriesItemRequest $request, GroceryItem $groceriesItem): RedirectResponse
    {
        $groceriesItem->update($request->validated());

        return redirect()->route('groceries-items.index')
            ->with('success', 'Grocery item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroceryItem $groceriesItem): RedirectResponse
    {
        $groceriesItem->delete();

        return redirect()->route('groceries-items.index')
            ->with('success', 'Grocery item deleted successfully.');
    }
}
