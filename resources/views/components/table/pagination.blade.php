<nav class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6" aria-label="Pagination">
    <div class="hidden sm:block">
        <p class="text-sm text-gray-700">
            {{__('Showing')}}
            <span x-text="from" class="font-medium"></span>
            {{__('to')}}
            <span x-text="to" class="font-medium"></span>
            {{__('of')}}
            <span x-text="total" class="font-medium"></span>
            {{__('results')}}
        </p>
    </div>
    <div x-show="total>limit" class="flex-1 flex justify-between sm:justify-end">
        <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            {{__('Previous')}}
        </a>
        <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            {{__('Next')}}
        </a>
    </div>
</nav>

