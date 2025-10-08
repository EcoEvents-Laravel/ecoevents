@extends('layouts.app')

@section('title', 'EcoEvents - User Badges')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white d-flex align-items-center gap-2">
                    <svg class="me-2" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C12 2 7 7 7 12a5 5 0 0 0 10 0c0-5-5-10-5-10z"/><circle cx="12" cy="12" r="3" /></svg>
                    <h1 class="mb-0 fs-3 fw-bold">User Badges</h1>
                    <!-- Create User Badge Button trigger modal -->
                    <button type="button" class="btn btn-light border" data-bs-toggle="modal" data-bs-target="#createUserBadgeModal">
                        Create User Badge
                    </button>
                </div>
                <div class="card-body bg-light">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-success">
                                <tr>
                                    <th scope="col" class="text-center">ID</th>
                                    <th scope="col" class="text-center">User Name</th>
                                    <th scope="col" class="text-center">Badge Name</th>
                                    <th scope="col" class="text-center">Acquired at</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user_badges as $user_badge)
                                    <tr>
                                        <td class="text-center">{{ $user_badge->id }}</td>
                                        <td class="text-center">{{ $user_badge->user->name }}</td>
                                        <td class="text-center">{{ $user_badge->badge->name }}</td>
                                        <td class="text-center">{{ $user_badge->acquired_at }}</td>
                                        <td class="text-center">
                                            <!-- Edit Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserBadgeModal{{ $user_badge->id }}">
                                                Edit
                                            </button>
                                            <!-- Delete Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserBadgeModal{{ $user_badge->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Create User Badge Modal (bottom of page) -->
            <div class="modal fade" id="createUserBadgeModal" tabindex="-1" aria-labelledby="createUserBadgeModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('user_badge.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="createUserBadgeModalLabel">Create User Badge</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="user_id_create" class="form-label">User</label>
                                    <select class="form-select" id="user_id_create" name="user_id" required>
                                        <option value="" disabled selected>Select a user</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="badge_id_create" class="form-label">Badge</label>
                                    <select class="form-select" id="badge_id_create"  name="badge_id" required>
                                        @foreach($badges as $badge)
                                           <option value="{{$badge->id}}">{{$badge->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="acquired_at_create" class="form-label">Acquired At</label>
                                    <input type="datetime-local" class="form-control" id="acquired_at_create" name="acquired_at" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit and Delete Modals (bottom of page) -->
            @foreach ($user_badges as $user_badge)
                <!-- Edit Modal -->
                <div class="modal fade" id="editUserBadgeModal{{ $user_badge->id }}" tabindex="-1" aria-labelledby="editUserBadgeModalLabel{{ $user_badge->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('user_badge.update', $user_badge->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserBadgeModalLabel{{ $user_badge->id }}">Edit User Badge</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="user_id{{ $user_badge->id }}" class="form-label">User Name</label>
                                        <select class="form-select" id="user_id{{ $user_badge->id }}" name="user_id" required>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $user->id == $user_badge->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="badge_id{{ $user_badge->id }}" class="form-label">Badge</label>
                                        <select class="form-select" id="badge_id{{ $user_badge->id }}" name="badge_id" required>
                                            @foreach($badges as $badge)
                                                <option value="{{ $badge->id }}" {{ $badge->id == $user_badge->badge_id ? 'selected' : '' }}>{{ $badge->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="acquired_at{{ $user_badge->id }}" class="form-label">Acquired At</label>
                                        <input type="datetime-local" class="form-control" id="acquired_at{{ $user_badge->id }}" name="acquired_at" value="{{ \Carbon\Carbon::parse($user_badge->acquired_at)->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteUserBadgeModal{{ $user_badge->id }}" tabindex="-1" aria-labelledby="deleteUserBadgeModalLabel{{ $user_badge->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('user_badge.destroy', $user_badge->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserBadgeModalLabel{{ $user_badge->id }}">Delete User Badge</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this user badge?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
