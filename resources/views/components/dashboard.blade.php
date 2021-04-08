<div>

    <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">
                {{__('Products')}}
            </dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{Arr::get($user->data,'products.link_status.linked','xxx')}}
            </dd>
        </div>

        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">
                {{__('Orders')}}

            </dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">

            </dd>
        </div>

        <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">
                {{__('Current balance')}}
            </dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                {{$user->customer->balance}}
            </dd>
        </div>
    </dl>
</div>


<div class="mb-3">

    <dl class="mt-5 grid grid-cols-1 rounded-lg bg-white overflow-hidden shadow divide-y divide-gray-200 md:grid-cols-3 md:divide-y-0 md:divide-x">
        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
                {{__('Your products/variants')}}
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                    <span title="{{__('Your linked products')}}">{{Arr::get($user->stats,'products.total','0')}}</span>
                </div>
                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">
            Increased by
          </span>
                    2.02%
                </div>
            </dd>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
                {{__('Linked products')}}
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                    58.16%
                    <span class="ml-2 text-sm font-medium text-gray-500">
            from 56.14%
          </span>
                </div>

                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 md:mt-2 lg:mt-0">
                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">
            Increased by
          </span>
                    2.02%
                </div>
            </dd>
        </div>

        <div class="px-4 py-5 sm:p-6">
            <dt class="text-base font-normal text-gray-900">
                {{__('Portfolio')}}
            </dt>
            <dd class="mt-1 flex justify-between items-baseline md:block lg:flex">
                <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                    <span title="{{__('Portfolio linked items')}}">{{Arr::get($user->stats,'portfolio.link_status.linked','0')}}</span>
                    <span class="ml-2 text-sm font-medium text-gray-500">
                        {{__('of')}}  <span title="{{__('Portfolio total items')}}">{{Arr::get($user->stats,'portfolio.total','0')}}</span>
                    </span>
                </div>

                <div class="inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800 md:mt-2 lg:mt-0">
                    <svg class="-ml-1 mr-0.5 flex-shrink-0 self-center h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">
            Decreased by
          </span>
                    4.05%
                </div>
            </dd>
        </div>
    </dl>
</div>
