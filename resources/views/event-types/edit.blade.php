@extends('layouts.app')

@section('title', 'Edit ' . $eventType->name)

@section('content')
    <h1>Edit Event Type: {{ $eventType->name }}</h1>
    <form action="{{ route('event-types.update', $eventType) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $eventType->name }}" required>
        </div>
        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $eventType->description }}</textarea>
        </div>
        <div class="form-group mb-3">
            <label>Color</label>
            <input type="text" name="color" class="form-control" value="{{ $eventType->color }}" required>
        </div>
        <div class="form-group mb-3">
            <label>Icon</label>
            <input type="text" name="icon" class="form-control" value="{{ $eventType->icon }}">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
@endsection