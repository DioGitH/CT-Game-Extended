<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Page Expired</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glow {
            text-shadow: 0 0 8px rgba(255, 0, 0, 0.7), 0 0 20px rgba(255, 0, 0, 0.5);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 to-black text-white flex items-center justify-center min-h-screen">

    <div class="text-center px-6 fade-in-up">
        <h1 class="text-7xl font-extrabold text-red-500 glow mb-4">419</h1>
        <p class="text-2xl font-bold text-white">Oops! Page Expired</p>
        <p class="text-gray-400 mt-2">Your session has expired. Please refresh the page or login again.</p>
        <a href="{{ url('/') }}"
            class="mt-6 inline-block bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:scale-105 transform transition-all font-semibold">
            🔐 Go to Login
        </a>
    </div>

</body>

</html>
