<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Lecturer Dashboard') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/game.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="flex">
        @include('lecturer.layouts.sidebar')

        <div class="flex-1 flex flex-col ml-64">
            @include('lecturer.layouts.navbar')

            <main class="p-6 overflow-auto">
                @yield('content')
                @yield('scripts')
            </main>
        </div>
    </div>
</body>


</html>
