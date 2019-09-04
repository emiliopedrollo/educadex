@extends('layout')

@section('content')
    <div class="row h-100">
        <div class="col-sm-10 offset-sm-1 my-auto col-lg-6 offset-lg-3">
            <educadex-search action="{{ route('search') }}"></educadex-search>
        </div>
    </div>
@endsection
