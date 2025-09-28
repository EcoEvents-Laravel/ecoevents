@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <h1 class="mb-4">Tags</h1>
    <a href="{{ route('tags.create') }}" class="btn btn-primary mb-3">Create New Tag</a>
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
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tags->links() }}
    @endif
@endsection