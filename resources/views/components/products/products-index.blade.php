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

                        <span x-text="$store.product_stats.shopify_products" :class="products_tab==='shopify_products' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
                              class=" hidden ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block"></span>
                    </a>

                    <a href="#" @click="products_tab = 'linked_products'"
                       :class="products_tab==='linked_products' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200'"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{__('Linked products')}}

                        <span x-text="$store.product_stats.linked_products" :class="products_tab==='linked_products' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
                              class=" hidden ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block"></span>
                    </a>

                    <a href="#" @click="products_tab = 'portfolio_items'"
                       :class="products_tab==='portfolio_items' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200'"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        {{$user->customer->store->name}} {{__('portfolio items')}}
                        <span x-text="$store.product_stats.portfolio_items" :class="products_tab==='portfolio_items' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
                              class=" hidden ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block"></span>
                    </a>

                </nav>
            </div>
        </div>
    </div>

    <div x-show="products_tab === 'shopify_products'">
       <x-products.tabs.shopify-products></x-products.tabs.shopify-products>
    </div>

    <div x-show="products_tab === 'linked_products'">
        <div x-data="table('/linked_products')"
             x-init="fetchData()"
             class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>

                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500  tracking-wider">
                                    {{__('Name')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500  tracking-wider">
                                    {{__('SKU')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500  tracking-wider">
                                    {{__('Link status')}}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500  tracking-wider">

                                </th>

                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="item in items" :key="item">
                                <tr>
                                    <td x-text="item.title" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"></td>
                                    <td x-text="item.sku" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"></td>
                                    <td x-html="item.formatted_link_status" class="px-6 py-4 whitespace-nowrap  text-sm font-medium">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                        <x-table.pagination/>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div x-show="products_tab === 'portfolio_items'">
        <x-products.tabs.portfolio-items></x-products.tabs.portfolio-items>
    </div>


</div>
