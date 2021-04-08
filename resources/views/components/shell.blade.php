<!-- This example requires Tailwind CSS v2.0+ -->
<div
    x-data="{ tab: '{{$routeName}}' , title:get_title('{{$routeName}}')  }"
    x-init="$watch('tab', value => { title=get_title(value);set_breadcrumb(title)} );"
>
    <nav class="bg-indigo-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8" src="https://tailwindui.com/img/logos/workflow-mark-indigo-300.svg" alt="Workflow">
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">


                            <a href="#" @click="tab = 'dashboard'" :class="tab==='dashboard' ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' "
                               class="text-white px-3 py-2 rounded-md text-sm font-medium">{{__('Dashboard')}}</a>

                            <a href="#" @click="tab = 'products'" :class="tab==='products' ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' "
                               class="text-white px-3 py-2 rounded-md text-sm font-medium">{{__('Products')}}</a>

                            <a href="#" @click="tab = 'orders'" :class="tab==='orders' ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' "
                               class="text-white px-3 py-2 rounded-md text-sm font-medium">{{__('Orders')}}</a>


                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <button class="p-1 bg-indigo-600 rounded-full text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
                            <span class="sr-only">View notifications</span>
                            <!-- Hero icon name: outline/bell -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>


                        </button>


                        <div class="ml-3 relative">
                            <div class="store-menu">

                                <a href="https://{{$user->customer->store->url}}" target="_blank"
                                   class="max-w-xs bg-indigo-600 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white"
                                   id="user-menu" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>

                                    <span class="text-white mr-4 text-lg">{{$user->customer->store->name}}</span>
                                    <img class="h-8 w-8 rounded-full"
                                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixqx=qnlYDc49zf&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                         alt="">
                                </a>
                            </div>


                        </div>

                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <!-- Mobile menu button -->
                    <button type="button"
                            class="bg-indigo-600 inline-flex items-center justify-center p-2 rounded-md text-indigo-200 hover:text-white hover:bg-indigo-500 hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <!--
                          Hero icon name: outline/menu

                          Menu open: "hidden", Menu closed: "block"
                        -->
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <!--
                          Hero icon name: outline/x

                          Menu open: "block", Menu closed: "hidden"
                        -->
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#"
                   class="@if($routeName=='dashboard')bg-indigo-700 @else hover:bg-indigo-500 hover:bg-opacity-75 @endif text-white block px-3 py-2 rounded-md text-base font-medium">
                    {{__('Dashboard')}}</a>

                <a href="#"
                   class="@if($routeName=='products')bg-indigo-700 @else hover:bg-indigo-500 hover:bg-opacity-75 @endif text-white block px-3 py-2 rounded-md text-base font-medium">
                    {{__('Products')}}</a>

                <a href="#"
                   class="@if($routeName=='orders')bg-indigo-700 @else hover:bg-indigo-500 hover:bg-opacity-75 @endif text-white block px-3 py-2 rounded-md text-base font-medium">
                    {{__('Orders')}}</a>


            </div>
            <div class="pt-4 pb-3 border-t border-indigo-700">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full"
                             src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixqx=qnlYDc49zf&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">Tom Cook</div>
                        <div class="text-sm font-medium text-indigo-300">tom@example.com</div>
                    </div>
                    <button class="ml-auto bg-indigo-600 flex-shrink-0 p-1 rounded-full text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
                        <span class="sr-only">View notifications</span>
                        <!-- Hero icon name: outline/bell -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-3 px-2 space-y-1 hidden">
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-indigo-500 hover:bg-opacity-75">Your Profile</a>

                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-indigo-500 hover:bg-opacity-75">Settings</a>

                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-indigo-500 hover:bg-opacity-75">Sign out</a>
                </div>
            </div>
        </div>
    </nav>


    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">


            <div class="px-4 py-0 sm:px-0">
                <div class="border border-solid border-gray-200 rounded-lg  bg-white p-4 ">

                    <div x-show="tab === 'dashboard'">
                        <x-dashboard :user="$user"/>
                    </div>
                    <div x-show="tab === 'products'">
                        <x-products.products-index :user="$user"/>
                    </div>
                    <div x-show="tab === 'orders'">
                        <x-orders :user="$user"/>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        window["translations"] = @json($translations, JSON_PRETTY_PRINT);

        const actions = window['app-bridge'].actions;
        const myTitleBar = actions['TitleBar'].create(app, {
            title: '{{$title()}}',
        });

        window.Spruce.store('product_stats', {
            shopify_products: '{{Arr::get($user->stats,'products.total','0')}}',
            linked_products: '{{Arr::get($user->stats,'products.linked_status.linked','0')}}',
            portfolio_items: '{{Arr::get($user->stats,'portfolio.total','0')}}',
        });

    </script>

@endsection
