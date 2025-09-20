@extends('layouts.app')

@section('title', 'Event Types')

@section('content')
    <h1 class="mb-4">Event Types</h1>
    <a href="{{ route('event-types.create') }}" class="btn btn-primary mb-3">Create New Event Type</a>
    @if($eventTypes->isEmpty())
        <p>No event types found.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Events Count</th>
                    <th>Color</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventTypes as $type)
                    <tr>
                        <td>{{ $type->name }}</td>
                        <td>{{ $type->events_count }}</td>
                        <td style="color: {{ $type->color }}">{{ $type->color }}</td>
                        <td>
                            <a href="{{ route('event-types.show', $type) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('event-types.edit', $type) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('event-types.destroy', $type) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $eventTypes->links() }}
    @endif
@endsection