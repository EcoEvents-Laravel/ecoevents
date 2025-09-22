<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoEvents Dashboard</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-green-100 via-green-200 to-emerald-100 min-h-screen flex flex-col items-center justify-center font-sans">
    <div class="bg-white/80 rounded-xl shadow-lg p-10 max-w-3xl w-full border border-green-200">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-extrabold text-green-800 flex items-center gap-2">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                </svg>
                EcoEvents Dashboard
            </h1>
            <a href="#" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-600 transition font-semibold shadow-md">Logout</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 rounded-lg p-6 shadow flex flex-col items-center">
                <svg class="w-10 h-10 text-green-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v.01"/>
                </svg>
                <h2 class="text-xl font-bold text-green-700 mb-1">Badges</h2>
                <p class="text-green-600 mb-3 text-center">View and manage your eco badges.</p>
                <a href="{{ route('badge.index') }}" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition font-semibold shadow">Go</a>
            </div>
            <div class="bg-green-50 rounded-lg p-6 shadow flex flex-col items-center">
                <svg class="w-10 h-10 text-green-400 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <h2 class="text-xl font-bold text-green-700 mb-1">User Badges</h2>
                <p class="text-green-600 mb-3 text-center">View badges acquired by users.</p>
                <a href="{{ route('user_badge.index') }}" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition font-semibold shadow">Go</a>
            </div>
        </div>
    </div>
</body>
</html>
