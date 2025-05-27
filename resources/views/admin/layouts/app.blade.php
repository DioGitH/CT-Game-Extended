<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/game.png') }}">
</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 flex-shrink-0">
            @include('admin.layouts.sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1">
            <!-- Navbar -->
            <div class="sticky top-0 z-50">
                @include('admin.layouts.navbar')
            </div>

            <!-- Content Area with Scrollable Section -->
            <main class="flex-1 overflow-y-auto p-6 h-full">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
