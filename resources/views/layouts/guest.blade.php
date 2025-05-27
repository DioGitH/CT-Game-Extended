<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CT-Game') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('storage/icons/game.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Tailwind CSS for animations -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @keyframes bounceIn {
                0% { transform: scale(0.5); opacity: 0; }
                60% { transform: scale(1.1); opacity: 1; }
                80% { transform: scale(0.9); }
                100% { transform: scale(1); }
            }

            .animate-bounceIn {
                animation: bounceIn 1s ease-out;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-indigo-200 via-purple-200 to-pink-200">
        <div class="min-h-screen flex flex-col items-center justify-center">

            <!-- Logo & Branding -->
            <div class="flex flex-col items-center animate-bounceIn">
                <a href="/">
                    <img src="{{ asset('storage/icons/game.png') }}" alt="CT-Game Logo" class="w-24 h-24 transition-transform duration-500 hover:rotate-12">
                </a>
                <h1 class="text-4xl font-extrabold text-indigo-700 mt-4 tracking-wide">
                    {{ config('app.name', 'CT-Game') }}
                </h1>
                <p class="text-gray-700 mt-1 italic">Gaming Experience, Redefined.</p>
            </div>

            <!-- Card Form with Hover Effect -->
            <div class="w-full sm:max-w-lg mt-8 px-8 py-6 bg-white shadow-2xl rounded-2xl border border-gray-300 transform transition-transform duration-500">
                {{ $slot }}
            </div>

            <!-- Footer with Fade-in Animation -->
            <footer class="mt-8 text-sm text-gray-600 opacity-0 animate-fadeInUp">
                &copy; {{ date('Y') }} {{ config('app.name', 'CT-Game') }}. All rights reserved.
            </footer>
        </div>

        <!-- Custom Animation -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const footer = document.querySelector('footer');
                setTimeout(() => {
                    footer.classList.add('opacity-100');
                }, 1000);
            });
        </script>

        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .animate-fadeInUp {
                animation: fadeInUp 1.5s ease-out forwards;
            }
        </style>
    </body>
</html>