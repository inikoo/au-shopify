@extends('common')

@section('content')
    <div class="bg-white">
        <div class="max-w-7xl  py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <h2 class="pb-4 inline text-3xl font-extrabold tracking-tight text-gray-900 sm:block sm:text-4xl">
                {{__('Hello')}} 👋 <span class="italic pl-3">{{ Auth::user()->name }}</span>
            </h2>
            <p class="inline text-2xl font-extrabold tracking-tight text-indigo-600 sm:block sm:text-3xl">{{__('We need the code given in your supplier website')}}</p>
            <form class="mt-8 sm:flex">
                <label for="accessCode" class="sr-only">{{__('Access code')}}</label>
                <input id="accessCode" name="accessCode" type="text" required class="w-full px-5 py-3 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs border-gray-300 rounded-md"
                       placeholder="Access code">
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                    <!--suppress CheckEmptyScriptTag -->
                    <x-button label="Verify"/>
                </div>
            </form>

            <div class="error-message rounded-md bg-red-50 p-4 mt-3 invisible">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="server-error hidden  text-sm font-medium text-red-800">
                            {{__('An error in our side has occurred 😭, we are working to fix it!')}}
                        </h3>
                        <h3 class="invalid-access-code hidden text-sm font-medium text-red-800">
                            {{__('The access code is invalid')}}
                        </h3>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <!--suppress CheckEmptyScriptTag -->
    <x-breadcrumbs title="{{__('Verification')}}"/>
    <script type="text/javascript">
        $(document).ready(function () {
            $("form").submit(function (event) {

                const submitButton = $("form button");
                submitButton.find('.action-label').addClass('hidden')
                submitButton.find('.processing').removeClass('hidden')

                const formData = {
                    accessCode: $("#accessCode").val(),
                };

                $.ajax({
                    type: "POST", url: "verify", data: formData, dataType: "json", encode: true, beforeSend: function () {
                        $('.error-message').addClass('invisible');
                        $('.error-message h3').addClass('hidden');
                    }
                }).done(function (data) {

                    if (data.success) {

                        location.reload();

                    } else {
                        submitButton.find('.action-label').removeClass('hidden')
                        submitButton.find('.processing').addClass('hidden')
                        $('.error-message').removeClass('invisible');
                        $('.' + data.reason).removeClass('hidden');
                    }


                }).fail(function () {
                    submitButton.find('.action-label').removeClass('hidden')
                    submitButton.find('.processing').addClass('hidden')
                    $('.error-message').removeClass('invisible');
                    $('.server-error').removeClass('hidden');
                });

                event.preventDefault();
            });
        });
    </script>
@endsection


