@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <h1 class="mb-4">Tags</h1>
    

    <a href="{{ route('tags.create') }}" class="btn btn-primary mb-3">Create New Tag</a>

        <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('tags.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="name" class="form-label">Search Tag</label>
                    <input type="text" name="name" id="name" class="form-control" 
                           value="{{ request('name') }}" placeholder="Enter tag name">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="{{ route('tags.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    @if($tags->isEmpty())
        <p>No tags found.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Events Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $tag)
                    <tr>
                        <td>{{ $tag->name }}</td>
                        <td>{{ $tag->events_count }}</td>
                        <td>
                            <a href="{{ route('tags.show', $tag) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('tags.edit', $tag) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('tags.destroy', $tag) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tags->appends(request()->query())->links() }}
    @endif
@endsection