<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Shopping Lists') }}
            </h2>
            <a href="{{ route('shopping-lists.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Create New List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($shoppingLists->count() > 0)
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($shoppingLists as $shoppingList)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $shoppingList->name }}
                                        </h3>
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($shoppingList->status === 'completed') bg-green-100 text-green-800
                                            @elseif($shoppingList->status === 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $shoppingList->status)) }}
                                        </span>
                                    </div>

                                    @if($shoppingList->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                                            {{ Str::limit($shoppingList->description, 100) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        <span>{{ $shoppingList->groceryItems->count() }} items</span>
                                        <span>{{ $shoppingList->created_at->format('M j, Y') }}</span>
                                    </div>

                                    <div class="flex space-x-2">
                                        <a href="{{ route('shopping-lists.show', $shoppingList) }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-1 px-2 rounded">
                                            View
                                        </a>
                                        <a href="{{ route('shopping-lists.edit', $shoppingList) }}"
                                           class="bg-yellow-500 hover:bg-yellow-700 text-white text-xs font-bold py-1 px-2 rounded">
                                            Edit
                                        </a>
                                        <form action="{{ route('shopping-lists.destroy', $shoppingList) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this shopping list?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-500 hover:bg-red-700 text-white text-xs font-bold py-1 px-2 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $shoppingLists->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 dark:text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                No shopping lists yet
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                Get started by creating your first shopping list.
                            </p>
                            <a href="{{ route('shopping-lists.create') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Your First List
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
