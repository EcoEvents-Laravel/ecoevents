<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoEvents - User Badges</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">User Badges</h1>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">User ID</th>
                <th class="px-4 py-2">Badge ID</th>
                <th class="px-4 py-2">Acquired at</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user_badges as $user_badge)
                <tr>
                    <td class="border px-4 py-2">{{ $user_badge->id }}</td>
                    <td class="border px-4 py-2">{{ $user_badge->user_id }}</td>
                    <td class="border px-4 py-2">{{ $user_badge->badge_id }}</td>
                    <td class="border px-4 py-2">{{ $user_badge->acquired_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
