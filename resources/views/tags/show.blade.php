@extends('layouts.app')

@section('title', $tag->name)

@section('content')
    <h1>{{ $tag->name }}</h1>
    <h3>Associated Events</h3>
    @if($tag->events->isEmpty())
        <p>No events associated.</p>
    @else
        <ul>
            @foreach($tag->events as $event)
                <li>{{ $event->title }} ({{ $event->start_date->format('Y-m-d') }})</li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('tags.index') }}" class="btn btn-secondary">Back</a>
@endsection