<div x-data="{ products_tab: 'shopify_products'  }">
    <div class="mb-5">
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a tab</label>
            <select id="tabs" name="tabs" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option selected>{{__('Your products/variants')}}</option>

                <option>{{__('Linked products')}}</option>

                <option>{{__('Portfolio')}}</option>


            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">

                    <a href="#" @click="products_tab = 'shopify_products'"
                       :class="products_tab==='shopify_products' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200'"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{__('Your products/variants')}}

                        <span x-text="$store.tables.shopify_products.qty.total" :class="products_tab==='shopify_products' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
                              class=" hidden ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block"></span>
                    </a>

                    <a href="#" @click="products_tab = 'portfolio_items'"
                       :class="products_tab==='portfolio_items' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200'"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{$user->customer->store->name}} {{__('portfolio items')}}
                        <span x-text="$store.tables.portfolio_items.qty.total" :class="products_tab==='portfolio_items' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
                              class=" hidden ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block"></span>
                    </a>

                </nav>
            </div>
        </div>
    </div>

    <div x-show="products_tab === 'shopify_products'">
        <x-products.tabs.shopify-products></x-products.tabs.shopify-products>
    </div>

    <div x-show="products_tab === 'portfolio_items'">
        <x-products.tabs.portfolio-items></x-products.tabs.portfolio-items>
    </div>


</div>
