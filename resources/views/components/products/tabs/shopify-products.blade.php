<div x-data="table('shopify_products')"
     x-init="fetchData()"
     class="flex flex-col"  @fetch-data="fetchData()" >

    <div class="mb-6 flex">
        <x-table.element label="{{__('Other supplier')}}" table="shopify_products"  element="unlinked"  open=$store.tables.shopify_products.open.unlinked   />
        <x-table.element label="{{__('Ready to be linked')}}" table="shopify_products"  element="engaged"  open=$store.tables.shopify_products.open.engaged />
        <x-table.element label="{{__('Linked')}}" table="shopify_products"  element="linked" open=$store.tables.shopify_products.open.linked  />
    </div>

    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                    <tr>

                        <th scope="col" class="w-3/4 px-6 py-3 text-left text-xs font-medium text-gray-500  tracking-wider">
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
                            <td x-html="item.formatted_link_status" class="px-6 py-4 whitespace-nowrap  text-sm font-medium"></td>
                            <td x-html="item.action" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"></td>
                        </tr>
                    </template>

                    </tbody>
                </table>
                <x-table.pagination/>
            </div>

        </div>
    </div>
</div>
