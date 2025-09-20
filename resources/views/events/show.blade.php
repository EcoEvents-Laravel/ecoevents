@extends('layouts.app')

@section('content')
    <h1>{{ $event->title }}</h1>

    @if($event->banner_url)
        <img src="{{ asset('storage/' . $event->banner_url) }}" alt="Event Banner" style="max-width: 400px;">
    @else
        <p>No banner image available.</p>
    @endif

    <p><strong>Description:</strong> {{ $event->description }}</p>
    <p><strong>Type:</strong> {{ $event->eventType->name ?? 'N/A' }}</p>
    <p><strong>Tags:</strong> {{ $event->tags->pluck('name')->implode(', ') }}</p>
    <p><strong>Start:</strong> {{ $event->start_date->format('Y-m-d H:i') }}</p>
    <p><strong>End:</strong> {{ $event->end_date->format('Y-m-d H:i') }}</p>
    <p><strong>Location:</strong> {{ $event->address }}, {{ $event->city }}, {{ $event->country }}</p>
    <p><strong>Maximum Participants:</strong> {{ $event->max_participants ?? 'N/A' }}</p>

    <a href="{{ route('events.index') }}" class="btn btn-secondary">Back</a>
@endsection