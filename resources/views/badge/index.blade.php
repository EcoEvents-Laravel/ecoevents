@extends('layouts.app')

@section('title', 'Mes Inscriptions')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-success fw-bold">EcoEvents Badges</h1>
        <button onclick="openModal('createBadgeModal')" class="btn btn-success d-flex align-items-center gap-2">
            <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
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
                            <img src="{{ asset('storage/' . $badge->icon) }}" alt="{{ $badge->name }}" class="rounded-circle border border-success shadow-sm" style="height:48px;width:48px;object-fit:cover;">
                        </td>
                        <td>
                            <a href="{{ route('badge.show', $badge->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                            <button type="button" onclick="populateUpdateForm({{ $badge->id }})" class="btn btn-outline-warning btn-sm">Update</button>
                            <button type="button" onclick="populateDeleteForm({{ $badge->id }})" class="btn btn-outline-danger btn-sm">Delete</button>
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
                    <button type="button" class="btn-close" onclick="closeModal('createBadgeModal')" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-success">Name</label>
                        <input type="text" name="name" class="form-control">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-success">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-success">Image</label>
                        <input type="file" name="icon" class="form-control">
                        @error('icon')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createBadgeModal')">Cancel</button>
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
                    <button type="button" class="btn-close" onclick="closeModal('updateBadgeModal')" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-warning">Name</label>
                        <input type="text" name="name" id="updateBadgeName" class="form-control">
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-warning">Description</label>
                        <textarea name="description" id="updateBadgeDescription" class="form-control"></textarea>
                        @error('description')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-warning">Image</label>
                        <input type="file" name="icon" class="form-control">
                        @error('icon')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('updateBadgeModal')">Cancel</button>
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
                    <button type="button" class="btn-close" onclick="closeModal('deleteBadgeModal')" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Are you sure you want to delete this badge?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteBadgeModal')">Cancel</button>
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
