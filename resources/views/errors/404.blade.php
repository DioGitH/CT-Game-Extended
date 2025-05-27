<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .text-glow {
            text-shadow:
                0 0 8px rgba(255, 213, 0, 0.8),
                0 0 16px rgba(255, 213, 0, 0.6),
                0 0 32px rgba(255, 213, 0, 0.4);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 to-black text-white flex items-center justify-center h-screen p-4">

    <div class="text-center">
        <div class="float-animation">
            <h1 class="text-9xl font-extrabold text-yellow-400 text-glow">404</h1>
            <p class="text-2xl font-semibold mt-2 animate-pulse">Oops! Page Not Found</p>
        </div>

        <p class="text-gray-400 mt-4 max-w-md mx-auto">
            The page you're looking for might have been moved, deleted, or never existed.
        </p>

        <div class="mt-8">
            <a href="{{ url()->previous() }}"
                class="px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-black font-semibold rounded-lg shadow-lg transition-all transform hover:scale-105">
                🔙 Go Back
            </a>
        </div>
    </div>

</body>
</html>
