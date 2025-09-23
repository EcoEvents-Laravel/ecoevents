<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoEvents - User Badges</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-green-100 via-green-200 to-green-300 min-h-screen font-sans">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="bg-white/80 rounded-xl shadow-lg p-8 w-full max-w-3xl border border-green-200">
        <h1 class="text-3xl font-bold mb-6 text-green-800 flex items-center gap-2">
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C12 2 7 7 7 12a5 5 0 0 0 10 0c0-5-5-10-5-10z"/><circle cx="12" cy="12" r="3" /></svg>
            User Badges
        </h1>
        <table class="table-auto w-full rounded-lg overflow-hidden shadow border border-green-200 bg-green-50">
            <thead>
                <tr class="bg-green-200 text-green-900">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">User ID</th>
                    <th class="px-4 py-2">Badge ID</th>
                    <th class="px-4 py-2">Acquired at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user_badges as $user_badge)
                    <tr class="hover:bg-green-100 transition">
                        <td class="border-t border-green-200 px-4 py-2 text-center">{{ $user_badge->id }}</td>
                        <td class="border-t border-green-200 px-4 py-2 text-center">{{ $user_badge->user_id }}</td>
                        <td class="border-t border-green-200 px-4 py-2 text-center">{{ $user_badge->badge_id }}</td>
                        <td class="border-t border-green-200 px-4 py-2 text-center">{{ $user_badge->acquired_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
