<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-400 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:bg-gray-50 dark:focus:bg-gray-600 active:bg-gray-100 dark:active:bg-gray-600 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
