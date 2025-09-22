<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoEvents - Badges</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">Badges</h1>
    <a href="#" onclick="openModal('createBadgeModal')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Create Badge</a>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Image</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            @foreach ($badges as $badge)
                    <td class="border px-4 py-2">{{ $badge->id }}</td>
                    <td class="border px-4 py-2">{{ $badge->name }}</td>
                    <td class="border px-4 py-2">{{ $badge->description }}</td>
                    <td class="border px-4 py-2">
                        <img src="{{ asset('/resources/images/' . $badge->icon) }}" alt="{{ $badge->name }}" class="h-16 w-16 object-cover">
                    </td>
            
                    <td class="border px-4 py-2">
                        <button onclick="window.location='{{ route('badge.show', $badge->id) }}'" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View</button>
                        <button onclick="openModal('updateBadgeModal'); populateUpdateForm({{ $badge->id }})" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Update Badge</button>
                        <button onclick="openModal('deleteBadgeModal'); populateDeleteForm({{ $badge->id }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete Badge</button>
                    </td>
            @endforeach
            </tr>
        </tbody>
    </table>
</div>
<!-- Create Badge Modal -->
<div id="createBadgeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Create Badge</h2>
        <form method="POST" action="{{ route('badge.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Image</label>
                <input type="file" name="icon" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('createBadgeModal')" class="mr-2 px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-500 text-white">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Badge Modal -->
<div id="updateBadgeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Update Badge</h2>
        <form id="updateBadgeForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1">Name</label>
                <input type="text" name="name" id="updateBadgeName" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Description</label>
                <textarea name="description" id="updateBadgeDescription" class="w-full border rounded px-3 py-2" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Image</label>
                <input type="file" name="icon" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('updateBadgeModal')" class="mr-2 px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-yellow-500 text-white">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Badge Modal -->
<div id="deleteBadgeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Delete Badge</h2>
        <p class="mb-4">Are you sure you want to delete this badge?</p>
        <form id="deleteBadgeForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('deleteBadgeModal')" class="mr-2 px-4 py-2 rounded bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-red-500 text-white">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Open Create Badge Modal
    document.querySelector('a.bg-green-500').addEventListener('click', function(e) {
        e.preventDefault();
        openModal('createBadgeModal');
    });

    // Open Update Badge Modal and fill form
    document.querySelectorAll('button.bg-yellow-500').forEach(function(btn, idx) {
        btn.addEventListener('click', function() {
            const badge = @json($badges)[idx];
            document.getElementById('updateBadgeName').value = badge.name;
            document.getElementById('updateBadgeDescription').value = badge.description;
            document.getElementById('updateBadgeForm').action = '/badge/' + badge.id;
            openModal('updateBadgeModal');
        });
    });

    // Open Delete Badge Modal and set form action
    document.querySelectorAll('button.bg-red-500').forEach(function(btn, idx) {
        btn.addEventListener('click', function() {
            const badge = @json($badges)[idx];
            document.getElementById('deleteBadgeForm').action = '/badge/' + badge.id;
            openModal('deleteBadgeModal');
        });
    });
</script>
</body>
</html>
