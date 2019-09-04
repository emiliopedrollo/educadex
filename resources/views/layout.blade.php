<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("favicon-32x32.png") }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset("favicon-96x96.png") }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("favicon-16x16.png") }}">
    <title>Educadex</title>
    <link rel="stylesheet" href="{{ mix("css/app.css") }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app">
        @yield("content")
    </div>
    <script src="{{ mix("js/manifest.js") }}"></script>
    <script src="{{ mix("js/vendor.js") }}"></script>
    <script type="application/json" id="i18n">
        {!! file_get_contents(resource_path('lang/'.config('app.locale').'.json')) !!}
    </script>
    <script src="{{ mix("js/app.js") }}"></script>
</body>
