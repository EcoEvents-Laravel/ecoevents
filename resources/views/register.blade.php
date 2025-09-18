<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoEvents - Register</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-100 via-emerald-50 to-lime-100 flex flex-col items-center justify-center font-sans">
    <div class="w-full max-w-md mx-auto p-8 bg-white rounded-xl shadow-lg border border-green-300">
        <fieldset class="border-2 border-green-400 rounded-lg p-6 bg-green-50">
            <legend class="font-bold text-2xl text-green-700 mb-4 px-2">
                {{ __('Register') }}
            </legend>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-green-900 font-medium mb-1">Name</label>
                    <input id="name" class="block w-full px-4 py-2 border border-green-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400 bg-white" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-green-900 font-medium mb-1">Email</label>
                    <input id="email" class="block w-full px-4 py-2 border border-green-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400 bg-white" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-green-900 font-medium mb-1">Password</label>
                    <input id="password" class="block w-full px-4 py-2 border border-green-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400 bg-white" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-green-900 font-medium mb-1">Confirm Password</label>
                    <input id="password_confirmation" class="block w-full px-4 py-2 border border-green-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400 bg-white" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded transition duration-200 shadow">
                        Register
                    </button>
                    <button type="button" onclick="window.location='{{ route('login') }}'" class="text-green-700 underline hover:text-green-900 font-medium ml-4">
                        Login
                    </button>
                </div>
            </form>
        </fieldset>
    </div>
</body>
</html>
