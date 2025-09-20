@extends('layouts.app')

@section('content')
    <h1>Edit Event: {{ $event->title }}</h1>
    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $event->title }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required>{{ $event->description }}</textarea>
        </div>

        <div class="form-group">
            <label>Banner Image</label>
            <input type="file" name="banner_image" class="form-control-file" accept="image/jpeg,image/png,image/jpg,image/gif">
            @if($event->banner_url)
                <p>Current image: <img src="{{ asset('storage/' . $event->banner_url) }}" alt="Banner" style="max-width: 200px;"></p>
            @endif
            @error('banner_image') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Start Date</label>
            <input type="datetime-local" name="start_date" class="form-control" value="{{ $event->start_date->format('Y-m-d\TH:i') }}" required>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="datetime-local" name="end_date" class="form-control" value="{{ $event->end_date->format('Y-m-d\TH:i') }}" required>
        </div>
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" class="form-control" value="{{ $event->city }}" required>
        </div>
        <div class="form-group">
            <label>Country</label>
            <input type="text" name="country" class="form-control" value="{{ $event->country }}" required>
        </div>

        <div class="form-group">
    <label>Maximum Participants</label>
    <input type="number" name="max_participants" class="form-control" value="{{ $event->max_participants }}" min="1">
    @error('max_participants') <div class="text-danger">{{ $message }}</div> @enderror
</div>

        <div class="form-group">
            <label>Event Type</label>
            <select name="event_type_id" class="form-control" required>
                @foreach($eventTypes as $type)
                    <option value="{{ $type->id }}" {{ $type->id == $event->event_type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Tags</label>
            @foreach($tags as $tag)
                <div class="form-check">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="form-check-input" {{ $event->tags->contains($tag->id) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $tag->name }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
@endsection