<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $badge->name }} - Eco Badge</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 font-sans text-green-900 m-0 p-0">

    <div class="bg-white rounded-2xl shadow-lg max-w-md mx-auto mt-10 p-8 text-center">
        <img src="{{ $badge->image_url }}" alt="{{ $badge->name }}" class="w-30 h-30 object-contain rounded-full border-4 border-green-200 bg-green-50 shadow-md mx-auto mb-6">
        <h1 class="text-3xl font-bold text-green-700 mb-4">{{ $badge->name }}</h1>
        <p class="text-green-600 mb-6">{{ $badge->description }}</p>
    </div>

</body>
</html>
