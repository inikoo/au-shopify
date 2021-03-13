@extends('shopify-app::layouts.default')


@section('styles')
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endsection

@section('content')


    <div class="bg-white">
        <div class="max-w-7xl  py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <h2 class="pb-4 inline text-3xl font-extrabold tracking-tight text-gray-900 sm:block sm:text-4xl">
                Hello 👋  <span class="italic pl-3">{{ Auth::user()->name }}</span>
            </h2>
            <p class="inline text-2xl font-extrabold tracking-tight text-indigo-600 sm:block sm:text-3xl">We need the code given in your supplier website</p>
            <form class="mt-8 sm:flex">
                <label for="accessCode" class="sr-only">Access code</label>
                <input id="accessCode" name="accessCode" type="text"  required class="w-full px-5 py-3 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs border-gray-300 rounded-md" placeholder="Access code">
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                    <button type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Verify
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    @parent

    <script type="text/javascript">
        var AppBridge = window['app-bridge'];
        var actions = AppBridge.actions;
        var TitleBar = actions.TitleBar;
        var Button = actions.Button;
        var Redirect = actions.Redirect;
        var titleBarOptions = {
            title: 'Verification',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
    </script>
@endsection
