@extends(auth()->user()->role === 'admin' ? 'layouts.app' : 'layouts.front')
@section(auth()->user()->role === 'admin' ? 'Badges EcoEvents' : 'Badges EcoEvents')

@section('content')
    @if (auth()->user()->role === 'admin')
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-success fw-bold">EcoEvents Badges</h1>
                <button onclick="openModal('createBadgeModal')" class="btn btn-success d-flex align-items-center gap-2">
                    <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                    Create Badge
                </button>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($badges as $badge)
                                <tr>
                                    <td>{{ $badge->id }}</td>
                                    <td class="fw-semibold text-success">{{ $badge->name }}</td>
                                    <td class="text-muted">{{ $badge->description }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $badge->icon) }}" alt="{{ $badge->name }}"
                                            class="rounded-circle border border-success shadow-sm"
                                            style="height:48px;width:48px;object-fit:cover;">
                                    </td>
                                    <td>
                                        <a href="{{ route('badge.show', $badge->id) }}"
                                            class="btn btn-outline-primary btn-sm">View</a>
                                        <button type="button" onclick="populateUpdateForm({{ $badge->id }})"
                                            class="btn btn-outline-warning btn-sm">Update</button>
                                        <button type="button" onclick="populateDeleteForm({{ $badge->id }})"
                                            class="btn btn-outline-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Badge Modal -->
        <div id="createBadgeModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('badge.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title text-success fw-bold">Create Badge</h5>
                            <button type="button" class="btn-close" onclick="closeModal('createBadgeModal')"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="createBadgeName" class="form-label fw-medium text-success">Name</label>
                                <input type="text" name="name" id="createBadgeName" class="form-control">
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="createBadgeDescription"
                                    class="form-label fw-medium text-success">Description</label>
                                <textarea name="description" id="createBadgeDescription" class="form-control"></textarea>
                                @error('description')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="createBadgeIcon" class="form-label fw-medium text-success">Image</label>
                                <input type="file" name="icon" id="createBadgeIcon" class="form-control">
                                @error('icon')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                onclick="closeModal('createBadgeModal')">Cancel</button>
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Badge Modal -->
        <div id="updateBadgeModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="updateBadgeForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title text-warning fw-bold">Update Badge</h5>
                            <button type="button" class="btn-close" onclick="closeModal('updateBadgeModal')"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="updateBadgeName" class="form-label fw-medium text-warning">Name</label>
                                <input type="text" name="name" id="updateBadgeName" class="form-control">
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="updateBadgeDescription"
                                    class="form-label fw-medium text-warning">Description</label>
                                <textarea name="description" id="updateBadgeDescription" class="form-control"></textarea>
                                @error('description')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="updateBadgeIcon" class="form-label fw-medium text-warning">Image</label>
                                <input type="file" name="icon" id="updateBadgeIcon" class="form-control">
                                @error('icon')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                onclick="closeModal('updateBadgeModal')">Cancel</button>
                            <button type="submit" class="btn btn-warning text-white">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Badge Modal -->
        <div id="deleteBadgeModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="deleteBadgeForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title text-danger fw-bold">Delete Badge</h5>
                            <button type="button" class="btn-close" onclick="closeModal('deleteBadgeModal')"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">Are you sure you want to delete this badge?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                onclick="closeModal('deleteBadgeModal')">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Bootstrap modal helpers
            function openModal(id) {
                var modal = new bootstrap.Modal(document.getElementById(id));
                modal.show();
                window['_' + id] = modal;
            }

            function closeModal(id) {
                if (window['_' + id]) {
                    window['_' + id].hide();
                }
            }

            // Data for JS
            const badges = @json($badges);

            // Update form population
            window.populateUpdateForm = function(id) {
                const badge = badges.find(b => b.id === id);
                if (!badge) return;
                document.getElementById('updateBadgeName').value = badge.name;
                document.getElementById('updateBadgeDescription').value = badge.description;
                document.getElementById('updateBadgeForm').action = '/badge/' + badge.id;
                openModal('updateBadgeModal');
            };

            // Delete form population
            window.populateDeleteForm = function(id) {
                document.getElementById('deleteBadgeForm').action = '/badge/' + id;
                openModal('deleteBadgeModal');
            };
        </script>
    @else
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-green-600 mb-6">Badges EcoEvents</h1>
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center w-full sm:w-1/2">
                    <label for="badgeSearch" class="sr-only">Search badges</label>
                    <input id="badgeSearch" type="search" placeholder="Search badges by name or description..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        autocomplete="off">
                </div>

                <div class="flex items-center gap-3">
                    <button id="clearBadgeSearch" type="button"
                        class="px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-md hover:bg-green-100">Clear</button>
                </div>
            </div>

            <script>
                (function () {
                    const input = document.getElementById('badgeSearch');
                    const clearBtn = document.getElementById('clearBadgeSearch');

                    function getGrid() {
                        return document.querySelector('.grid.grid-cols-1') || document.querySelector('.grid');
                    }

                    function updateNoResults(grid) {
                        let noEl = document.getElementById('noBadgesMessage');
                        const anyVisible = Array.from(grid.children).some(c => c.style.display !== 'none');
                        if (!anyVisible) {
                            if (!noEl) {
                                noEl = document.createElement('div');
                                noEl.id = 'noBadgesMessage';
                                noEl.className = 'col-span-full text-center text-gray-500';
                                noEl.textContent = 'No badges match your search.';
                                grid.parentNode.insertBefore(noEl, grid.nextSibling);
                            }
                        } else {
                            if (noEl) noEl.remove();
                        }
                    }

                    function filterBadges() {
                        const q = input.value.trim().toLowerCase();
                        const grid = getGrid();
                        if (!grid) return;
                        const cards = Array.from(grid.children);
                        cards.forEach(card => {
                            const nameEl = card.querySelector('h2');
                            const descEl = card.querySelector('p');
                            const name = nameEl ? nameEl.textContent.toLowerCase() : '';
                            const desc = descEl ? descEl.textContent.toLowerCase() : '';
                            const match = q === '' || name.includes(q) || desc.includes(q);
                            card.style.display = match ? '' : 'none';
                        });
                        updateNoResults(grid);
                    }

                    if (input) {
                        input.addEventListener('input', filterBadges);
                        clearBtn.addEventListener('click', function () {
                            input.value = '';
                            filterBadges();
                            input.focus();
                        });
                        // run once on load to ensure proper state
                        document.addEventListener('DOMContentLoaded', filterBadges);
                        // also run immediately in case DOMContentLoaded already fired
                        filterBadges();
                    }
                })();
            </script>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($badges as $badge)
                    @foreach ($userBadges as $userBadge)
                        @if ($userBadge->badge_id === $badge->id && $userBadge->user_id === auth()->user()->id)
                            <div class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center h-full">
                                <div
                                    class="w-24 h-24 flex items-center justify-center rounded-full mb-4 border border-green-600 shadow-sm bg-white overflow-hidden">
                                    <img src="{{ $badge->icon ? asset('storage/' . $badge->icon) : asset('images/default-badge.png') }}"
                                        alt="{{ $badge->name }}" style="width:100%;height:100%;object-fit:contain;">
                                </div>
                                <h2 class="text-xl font-semibold text-green-700 mb-2">{{ $badge->name }}</h2>
                                <p class="text-gray-600 mb-4">{{ $badge->description }}</p>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif
@endsection
