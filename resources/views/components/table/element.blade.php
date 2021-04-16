<span x-data="{open: {{$open}} , table: '{{$table}}' ,element: '{{$element}}'}" class="flex items-center mr-10">
    <button
                @click="open = !open;$store.tables[table]['open'][element]=open;$dispatch('fetch-data')"
                type="button"
                class="mr-1 flex-shrink-0 group relative rounded-full inline-flex items-center justify-center h-5 w-10 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                role="switch" aria-checked="false">
  <span class="sr-only">Use setting</span>
  <span aria-hidden="true" class="   pointer-events-none absolute bg-white w-full h-full rounded-md"></span>
  <span aria-hidden="true" :class="open? 'bg-indigo-600' : 'bg-gray-200'" class="pointer-events-none absolute h-4 w-9 mx-auto rounded-full transition-colors ease-in-out duration-200"></span>
  <span aria-hidden="true" :class="open? 'translate-x-5' : 'translate-x-0'"
        class="pointer-events-none absolute left-0 inline-block h-5 w-5 border border-gray-200 rounded-full bg-white shadow transform ring-0 transition-transform ease-in-out duration-200"></span>
</button>
    <span :class="open? 'text-indigo-800' : 'text-gray-500'" class="text-sm">{{$label}}</span>
<span  x-text="$store.tables[table]['qty'][element]" :class="open ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'"
      class=" hidden ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block"></span>

</span>
