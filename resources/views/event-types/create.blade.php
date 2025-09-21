@extends('layouts.app')

@section('title', 'Create Event Type')

@section('content')
    <h1>Create Event Type</h1>
    <form action="{{ route('event-types.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group mb-3">
            <label>Color (e.g., #FF0000)</label>
            <input type="text" name="color" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Icon</label>
            <input type="text" name="icon" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('event-types.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection