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
                </div>
                <div class="card-body bg-light">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-success">
                                <tr>
                                    <th scope="col" class="text-center">ID</th>
                                    <th scope="col" class="text-center">User ID</th>
                                    <th scope="col" class="text-center">Badge ID</th>
                                    <th scope="col" class="text-center">Acquired at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user_badges as $user_badge)
                                    <tr>
                                        <td class="text-center">{{ $user_badge->id }}</td>
                                        <td class="text-center">{{ $user_badge->user_id }}</td>
                                        <td class="text-center">{{ $user_badge->badge_id }}</td>
                                        <td class="text-center">{{ $user_badge->acquired_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
