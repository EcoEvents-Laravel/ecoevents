@extends('layouts.app')

@section('content')
    <h1>Events</h1>
    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">Create New Event</a>

    <!-- Advanced Search Form -->
    <form method="GET" action="{{ route('events.index') }}" class="mb-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <!-- Search by Title -->
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Search by Title</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Enter event title">
                    </div>

                    <!-- Event Type -->
                    <div class="col-md-4 mb-3">
                        <label for="event_type_id" class="form-label">Event Type</label>
                        <select name="event_type_id" id="event_type_id" class="form-control">
                            <option value="">All Types</option>
                            @foreach($eventTypes as $type)
                                <option value="{{ $type->id }}" {{ request('event_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary">Clear Filters</a>
                    </div>

                </div>

                
            </div>
        </div>
    </form>

    <!-- Events Table -->
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Tags</th>
                <th>Start Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->eventType->name ?? 'N/A' }}</td>
                    <td>{{ $event->tags->pluck('name')->implode(', ') }}</td>
                    <td>{{ $event->start_date->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No events found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $events->links() }}
@endsection