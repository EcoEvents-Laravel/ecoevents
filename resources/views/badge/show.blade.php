@extends('layouts.app')

@section('title', $badge->name . ' - Eco Badge')

@section('content')
<div class="container my-5">
    <div class="card mx-auto shadow" style="max-width: 28rem;">
        <div class="card-body text-center">
            <img src="{{ asset('storage/' . $badge->icon) }}" alt="{{ $badge->name }}" class="rounded-circle border border-success mb-4" style="width: 120px; height: 120px; object-fit: contain; background: #e9fbe5;">
            <h1 class="card-title h3 text-success mb-3">{{ $badge->name }}</h1>
            <p class="card-text text-secondary">{{ $badge->description }}</p>
        </div>
    </div>
</div>
@endsection
