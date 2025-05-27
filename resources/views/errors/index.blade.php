<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-red-500 flex items-center justify-center h-screen">
    <div class="text-center max-w-lg">
        <!-- Warning Icon -->
        <div class="text-8xl mb-6 animate-pulse">
            ☠️
        </div>
        <!-- Title -->
        <h1 class="text-6xl font-extrabold uppercase tracking-wider mb-4">
            Forbidden Access!
        </h1>
        <!-- Threat Message -->
        <p class="text-lg font-bold mb-6">
            You have attempted to access a restricted area.
            <span class="text-red-400">This action is logged.</span>
            Leave immediately before further action is taken.
        </p>
        <!-- Legal Disclaimer -->
        <p class="text-sm text-gray-400 mb-8 italic">
            Unauthorized access is a violation of system policy and may lead to severe consequences.
        </p>
        <!-- Button to Escape -->
        <a href="{{ url('/') }}"
            class="bg-red-700 text-white font-bold uppercase tracking-wide px-8 py-4 rounded-lg shadow-lg hover:bg-red-800 transition duration-300">
            Escape Now
        </a>
        <!-- Flickering Text -->
        <div class="mt-8 text-sm text-gray-500 animate-pulse">
            Your IP and activity have been recorded.
        </div>
    </div>
</body>

</html>
