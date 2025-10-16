<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $shoppingList->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Created {{ $shoppingList->created_at->format('M j, Y') }}
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('shopping-lists.edit', $shoppingList) }}"
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit List
                </a>
                <a href="{{ route('shopping-lists.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Lists
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Shopping List Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            @if($shoppingList->description)
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    {{ $shoppingList->description }}
                                </p>
                            @endif
                            <div class="flex items-center space-x-4">
                                <span class="px-3 py-1 text-sm rounded-full
                                    @if($shoppingList->status === 'completed') bg-green-100 text-green-800
                                    @elseif($shoppingList->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $shoppingList->status)) }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $shoppingList->groceryItems->count() }} items
                                </span>
                            </div>
                        </div>

                        <!-- Status Update Form -->
                        <form action="{{ route('shopping-lists.status.update', $shoppingList) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                <option value="pending" {{ $shoppingList->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $shoppingList->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $shoppingList->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Item Form -->
            @if($availableGroceryItems->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Add Item to List</h3>
                        <form action="{{ route('shopping-lists.items.store', $shoppingList) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                            @csrf
                            <div class="flex-1 min-w-48">
                                <label for="grocery_item_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grocery Item</label>
                                <select name="grocery_item_id" id="grocery_item_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Select an item...</option>
                                    @foreach($availableGroceryItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->category }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-24">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                <input type="number" name="quantity" id="quantity" min="1" value="1" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            </div>
                            <div class="flex-1 min-w-48">
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (optional)</label>
                                <input type="text" name="notes" id="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            </div>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Add Item
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Shopping List Items -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Shopping Items</h3>

                    @if($shoppingList->groceryItems->count() > 0)
                        <div class="space-y-3">
                            @foreach($shoppingList->groceryItems as $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg {{ $item->pivot->is_completed ? 'opacity-60' : '' }}">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <!-- Checkbox to toggle completion -->
                                        <form action="{{ route('shopping-lists.items.toggle', [$shoppingList, $item]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="checkbox"
                                                   {{ $item->pivot->is_completed ? 'checked' : '' }}
                                                   onchange="this.form.submit()"
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        </form>

                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-medium text-gray-900 dark:text-gray-100 {{ $item->pivot->is_completed ? 'line-through' : '' }}">
                                                    {{ $item->name }}
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    ({{ $item->category }})
                                                </span>
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                    Qty: {{ $item->pivot->quantity }}
                                                </span>
                                            </div>
                                            @if($item->pivot->notes)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    {{ $item->pivot->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex space-x-2">
                                        <!-- Edit Item Modal Trigger -->
                                        <button onclick="openEditModal({{ $item->id }}, '{{ $item->name }}', {{ $item->pivot->quantity }}, '{{ $item->pivot->notes }}', {{ $item->pivot->is_completed ? 'true' : 'false' }})"
                                                class="bg-yellow-500 hover:bg-yellow-700 text-white text-xs font-bold py-1 px-2 rounded">
                                            Edit
                                        </button>

                                        <!-- Remove Item -->
                                        <form action="{{ route('shopping-lists.items.destroy', [$shoppingList, $item]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Remove this item from the list?')"
                                                    class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 dark:text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6h8v-6M8 11H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-2" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                No items in this list yet
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                Add some grocery items to get started with your shopping.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Item</h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item</label>
                        <p id="editItemName" class="text-gray-900 dark:text-gray-100"></p>
                    </div>
                    <div class="mb-4">
                        <label for="editQuantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                        <input type="number" name="quantity" id="editQuantity" min="1" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label for="editNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <input type="text" name="notes" id="editNotes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_completed" id="editCompleted" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Mark as completed</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeEditModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(itemId, itemName, quantity, notes, isCompleted) {
            document.getElementById('editItemName').textContent = itemName;
            document.getElementById('editQuantity').value = quantity;
            document.getElementById('editNotes').value = notes || '';
            document.getElementById('editCompleted').checked = isCompleted;
            document.getElementById('editForm').action = `{{ route('shopping-lists.items.update', [$shoppingList, ':item']) }}`.replace(':item', itemId);
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-app-layout>
