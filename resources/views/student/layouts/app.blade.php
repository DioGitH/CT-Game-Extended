<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamified Dashboard</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

    <link rel="icon" type="image/png" href="{{ asset('storage/icons/game.png') }}">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at top, #2d3658, #050c22);
            color: white;
        }

        /* Efek Glow */
        .glow {
            box-shadow: 0px 0px 15px rgba(255, 255, 0, 0.6);
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="flex min-h-screen">

    <!-- Sidebar -->
    @include('student.layouts.sidebar')

    <!-- Content Wrapper -->
    <div id="main-content" class="flex-1 transition-all duration-500 p-8 ml-64">
        @yield('content')
    </div>

    <!-- Sidebar Animation -->
    <script>
        $(document).ready(function() {
            $('#toggleSidebar').click(function() {
                $('#sidebar').toggleClass('w-64 w-16');
                $('#main-content').toggleClass('ml-64 ml-16');
                $('.sidebar-text').toggleClass('hidden');
                $('#sidebar-logo').toggleClass('text-xl text-sm');
            });
        });
    </script>
    <!-- Gaming-Style Loading Overlay -->
    <div id="loadingOverlay"
        class="fixed inset-0 z-50 bg-gradient-to-br from-gray-900 via-black to-gray-800 flex items-center justify-center hidden flex-col animate-fadeIn">
        <dotlottie-player src="https://lottie.host/88a43e25-4a6e-4d57-acfd-62d3ccc78bf3/eCBKRbsqNR.lottie"
            background="transparent" speed="1" style="width: 300px; height: 300px" loop
            autoplay></dotlottie-player>
    </div>
    @if (session('show_tutorial_popup'))
        <div id="tutorialPopup" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
            <div class="bg-gray-900 p-8 rounded-lg text-center shadow-2xl animate-pop w-96">
                <h2 class="text-2xl font-bold text-yellow-400 mb-4">📘 Welcome {{ explode(' ', $user->name)[0] }}!</h2>
                <p class="text-white mb-6">Yuk mulai dengan membaca tutorial penggunaan aplikasi terlebih dahulu!</p>
                <div class="flex justify-center gap-4">
                    <form action="{{ route('student.dismiss.tutorial') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-semibold transition">
                            Lihat Tutorial
                        </button>
                    </form>
                    <button onclick="document.getElementById('tutorialPopup').remove()"
                        class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded font-semibold transition">Nanti
                        Saja</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Custom Animations -->
    <style>
        @keyframes pop {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-pop {
            animation: pop 0.4s ease-in-out;
        }

        @keyframes spinSlow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spinSlow 3s linear infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
    </style>

    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const links = document.querySelectorAll("a:not([target='_blank']):not([href^='#'])");
            const overlay = document.getElementById("loadingOverlay");

            links.forEach(link => {
                link.addEventListener("click", (e) => {
                    const href = link.getAttribute("href");
                    if (href && !href.startsWith("javascript:") && !link.classList.contains(
                            'no-loading')) {
                        overlay.classList.remove("hidden");
                    }
                });
            });
        });
    </script>
</body>

</html>
