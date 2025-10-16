<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('groceries-items', \App\Http\Controllers\GroceriesItemController::class);

    // Shopping Lists routes
    Route::resource('shopping-lists', \App\Http\Controllers\ShoppingListController::class);

    // Shopping List Items management routes
    Route::post('shopping-lists/{shoppingList}/items', [\App\Http\Controllers\ShoppingListController::class, 'addItem'])
        ->name('shopping-lists.items.store');
    Route::post('shopping-lists/{shoppingList}/items/create-and-add', [\App\Http\Controllers\ShoppingListController::class, 'createAndAddItem'])
        ->name('shopping-lists.items.create-and-add');
    Route::patch('shopping-lists/{shoppingList}/items/{groceryItem}', [\App\Http\Controllers\ShoppingListController::class, 'updateItem'])
        ->name('shopping-lists.items.update');
    Route::delete('shopping-lists/{shoppingList}/items/{groceryItem}', [\App\Http\Controllers\ShoppingListController::class, 'removeItem'])
        ->name('shopping-lists.items.destroy');
    Route::patch('shopping-lists/{shoppingList}/items/{groceryItem}/toggle', [\App\Http\Controllers\ShoppingListController::class, 'toggleItemStatus'])
        ->name('shopping-lists.items.toggle');
    Route::patch('shopping-lists/{shoppingList}/status', [\App\Http\Controllers\ShoppingListController::class, 'updateStatus'])
        ->name('shopping-lists.status.update');
});

require __DIR__.'/auth.php';
