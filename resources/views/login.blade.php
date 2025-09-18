<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoEvents - Login</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        </head>
        <body class="font-sans bg-gradient-to-br from-green-100 to-green-200 min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg border-2 border-green-300 max-w-md w-full p-8 mx-auto">
            <h2 class="text-green-800 font-semibold text-2xl mb-6 text-center">Login to EcoEvents</h2>
            <form method="GET" action="{{ route('welcome') }}">
            @csrf
            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-green-700 font-medium mb-2">Email</label>
                <input id="email" class="w-full px-4 py-3 border border-green-300 rounded-lg focus:border-green-700 focus:outline-none transition" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            </div>
            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-green-700 font-medium mb-2">Password</label>
                <input id="password" class="w-full px-4 py-3 border border-green-300 rounded-lg focus:border-green-700 focus:outline-none transition" type="password" name="password" required autocomplete="current-password" />
            </div>
            <div class="flex justify-between items-center mb-4">
                @if (Route::has('password.request'))
                <a class="text-green-700 underline hover:text-green-900 transition" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
                @endif
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-900 transition">
                Log in
                </button>
            </div>
            </form>
            <div class="text-center mt-2">
            <a href="{{ route('register') }}" class="text-green-700 underline hover:text-green-900 transition">
                Register
            </a>
            </div>
        </div>
        </body>
        </html>
