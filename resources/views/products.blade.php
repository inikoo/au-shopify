@extends('common')

@section('content')

    <x-header/>


@endsection

@section('scripts')
    @parent
    <x-breadcrumbs title="{{__('Products')}}"/>


@endsection


