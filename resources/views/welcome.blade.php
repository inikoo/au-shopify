@extends('shopify-app::layouts.default')

@section('content')
    <!-- You are: (shop domain name) -->
    <p>You are: {{ Auth::user()->name }}</p>

    <form method="POST" action="/profile">
        @csrf


        <label for="title">Access code</label>

        <input id="title" type="text" class="@error('title') is-invalid @enderror">
        @error('title')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <button class="btn btn-success save-data">Save</button>
        </div>
    </form>


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
            title: 'Registration',
        };
        var myTitleBar = TitleBar.create(app, titleBarOptions);
    </script>
@endsection
