<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoEvents - Badges</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-green-50 via-green-100 to-emerald-100 min-h-screen font-sans">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <h1 class="text-3xl font-extrabold mb-6 text-green-800 tracking-wide flex items-center gap-2">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C12 2 7 7 7 12c0 3.866 3.134 7 7 7s7-3.134 7-7c0-5-5-10-5-10z" /></svg>
        EcoEvents Badges
    </h1>
    <a href="#" onclick="openModal('createBadgeModal')" class="bg-gradient-to-r from-green-400 to-emerald-500 text-white px-6 py-2 rounded-full shadow-md hover:from-green-500 hover:to-emerald-600 transition mb-6 font-semibold flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Create Badge
    </a>
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg p-6">
        <table class="table-auto w-full text-left">
            <thead>
                <tr class="bg-green-100 text-green-900">
                    <th class="px-4 py-2 rounded-tl-lg">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Image</th>
                    <th class="px-4 py-2 rounded-tr-lg">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($badges as $badge)
                <tr class="border-b hover:bg-green-50 transition">
                    <td class="px-4 py-2">{{ $badge->id }}</td>
                    <td class="px-4 py-2 font-semibold text-green-700">{{ $badge->name }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $badge->description }}</td>
                    <td class="px-4 py-2">
                        <img src="{{ asset('storage/' . $badge->icon) }}" alt="{{ $badge->name }}" class="h-16 w-16 object-cover rounded-full border-2 border-green-300 shadow">
                    </td>
                    <td class="px-4 py-2 flex gap-2">
                        <button onclick="window.location='{{ route('badge.show', $badge->id) }}'" class="bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 transition font-medium">View</button>
                        <button onclick="openModal('updateBadgeModal'); populateUpdateForm({{ $badge->id }})" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200 transition font-medium">Update</button>
                        <button onclick="openModal('deleteBadgeModal'); populateDeleteForm({{ $badge->id }})" class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition font-medium">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Badge Modal -->
<div id="createBadgeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
    <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-lg border border-green-200">
        <h2 class="text-2xl font-bold mb-4 text-green-700">Create Badge</h2>
        <form method="POST" action="{{ route('badge.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium text-green-800">Name</label>
                <input type="text" name="name" class="w-full border border-green-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium text-green-800">Description</label>
                <textarea name="description" class="w-full border border-green-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required></textarea>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium text-green-800">Image</label>
                <input type="file" name="icon" class="w-full border border-green-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('createBadgeModal')" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-gradient-to-r from-green-400 to-emerald-500 text-white font-semibold hover:from-green-500 hover:to-emerald-600 transition">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Badge Modal -->
<div id="updateBadgeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
    <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-lg border border-green-200">
        <h2 class="text-2xl font-bold mb-4 text-green-700">Update Badge</h2>
        <form id="updateBadgeForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block mb-1 font-medium text-green-800">Name</label>
                <input type="text" name="name" id="updateBadgeName" class="w-full border border-green-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium text-green-800">Description</label>
                <textarea name="description" id="updateBadgeDescription" class="w-full border border-green-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400" required></textarea>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium text-green-800">Image</label>
                <input type="file" name="icon" class="w-full border border-green-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('updateBadgeModal')" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-gradient-to-r from-yellow-400 to-yellow-500 text-white font-semibold hover:from-yellow-500 hover:to-yellow-600 transition">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Badge Modal -->
<div id="deleteBadgeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
    <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-lg border border-green-200">
        <h2 class="text-2xl font-bold mb-4 text-red-700">Delete Badge</h2>
        <p class="mb-6 text-gray-700">Are you sure you want to delete this badge?</p>
        <form id="deleteBadgeForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('deleteBadgeModal')" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-gradient-to-r from-red-400 to-red-600 text-white font-semibold hover:from-red-500 hover:to-red-700 transition">Delete</button>
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
    document.querySelector('a.bg-gradient-to-r').addEventListener('click', function(e) {
        e.preventDefault();
        openModal('createBadgeModal');
    });

    // Open Update Badge Modal and fill form
    document.querySelectorAll('button.bg-yellow-100').forEach(function(btn, idx) {
        btn.addEventListener('click', function() {
            const badge = @json($badges)[idx];
            document.getElementById('updateBadgeName').value = badge.name;
            document.getElementById('updateBadgeDescription').value = badge.description;
            document.getElementById('updateBadgeForm').action = '/badge/' + badge.id;
            openModal('updateBadgeModal');
        });
    });

    // Open Delete Badge Modal and set form action
    document.querySelectorAll('button.bg-red-100').forEach(function(btn, idx) {
        btn.addEventListener('click', function() {
            const badge = @json($badges)[idx];
            document.getElementById('deleteBadgeForm').action = '/badge/' + badge.id;
            openModal('deleteBadgeModal');
        });
    });
</script>
</body>
</html>
