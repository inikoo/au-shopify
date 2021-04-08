@extends('common')

@section('content')
    <div class="bg-white">
        <div x-data="validate()" class="max-w-7xl  py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <h2 class="pb-4 inline text-3xl font-extrabold tracking-tight text-gray-900 sm:block sm:text-4xl">
                {{__('Hello')}} 👋
            </h2>
            <p class="inline text-2xl font-extrabold tracking-tight text-indigo-600 sm:block sm:text-3xl">{{__('We need the code given in your supplier website')}}</p>
            <form action="{{url('/verify')}}" method="POST" class="mt-8 sm:flex" @submit.prevent="submitData">
                <label for="accessCode" class="sr-only">{{__('Access code')}}</label>
                <input x-model="formData.accessCode" id="accessCode" name="accessCode" type="text" required
                       class="w-full px-5 py-3 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs border-gray-300 rounded-md"
                       placeholder="{{__('Access code')}}">
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">

                    <button type="submit"
                            class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">

                        <span x-show="!processing" class="action-label ">{{__('Save')}}</span>
                        <svg x-show="processing" class=" animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="processing">{{__('Processing')}}</span>
                    </button>

                </div>


            </form>
            <div x-show="haveError" class="error-message rounded-md bg-red-50 p-4 mt-3 ">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 x-text="errorMessage" class="server-error  text-sm font-medium text-red-800"></h3>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        const actions = window['app-bridge'].actions;
        const myTitleBar = actions['TitleBar'].create(app, {
            title: '{{__('Verification')}}',
        });

        function validate() {
            return {
                formData: {

                    accessCode: ''
                }, errorMessage: '', haveError: false, processing: false,

                submitData() {
                    this.processing = true;
                    this.errorMessage = '';
                    this.haveError = false;

                    fetch('/verify', {
                        method: 'POST', headers: {
                            'Content-Type': 'application/json', "X-Requested-With": "XMLHttpRequest", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')

                        }, body: JSON.stringify(this.formData)
                    })
                        .then(rawResponse => rawResponse.json())
                        .then(res => {


                            if (res.success) {
                                window.location.replace("/");

                            } else {
                                this.processing = false
                                this.haveError = true;
                                this.errorMessage = res['errorMessage'];
                            }

                        })
                        .catch(() => {
                            this.processing = false;
                            this.errorMessage = '{{__('An error in our side has occurred 😭, we are working to fix it!')}}'
                        })
                }
            }
        }


    </script>
@endsection


