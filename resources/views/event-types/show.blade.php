@extends('layouts.app')

@section('title', $eventType->name)

@section('content')
    <h1>{{ $eventType->name }}</h1>
    <p><strong>Description:</strong> {{ $eventType->description ?? 'N/A' }}</p>
    <p><strong>Color:</strong> <span style="color: {{ $eventType->color }}">{{ $eventType->color }}</span></p>
    <p><strong>Icon:</strong> {{ $eventType->icon ?? 'N/A' }}</p>
    <h3>Associated Events</h3>
    @if($eventType->events->isEmpty())
        <p>No events associated.</p>
    @else
        <ul>
            @foreach($eventType->events as $event)
                <li>{{ $event->title }} ({{ $event->start_date->format('Y-m-d') }})</li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('event-types.index') }}" class="btn btn-secondary">Back</a>
@endsection