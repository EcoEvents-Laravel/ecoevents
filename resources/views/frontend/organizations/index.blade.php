<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Organizations Directory</title>
    @vite(['resources/css/app.css', 'resources/js/frontend/organizations.js'])
</head>
<body class="bg-gray-50">
    <div id="app" class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">Organizations Directory</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-gray-900">Home</a>
                        <a href="/organisations" class="text-blue-600 hover:text-blue-800">Manage Organizations</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Organizations Directory</h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">Discover and connect with amazing organizations</p>
                <div class="max-w-md mx-auto">
                    <div class="relative">
                        <input type="text"
                               id="search-input"
                               placeholder="Search organizations..."
                               class="w-full px-4 py-3 pl-12 rounded-full border-0 shadow-lg focus:ring-4 focus:ring-blue-200 outline-none">
                        <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Loading State -->
            <div id="loading-state" class="flex justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <!-- Organizations Grid -->
            <div id="organizations-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" style="display: none;">
                <!-- Organizations will be populated by JavaScript -->
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="text-center py-16" style="display: none;">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No organizations found</h3>
                <p class="text-gray-500">Check back later for new organizations.</p>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="mt-12 flex justify-center" style="display: none;">
                <!-- Pagination will be populated by JavaScript -->
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-16">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-gray-500">
                    <p>&copy; 2024 Organization Directory. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>