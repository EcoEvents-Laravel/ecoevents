@extends('layouts.app')

@section('content')
    <h1>Create Event</h1>
    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label>Banner Image</label>
            <input type="file" name="banner_image" class="form-control-file" accept="image/jpeg,image/png,image/jpg,image/gif">
            @error('banner_image') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Start Date</label>
            <input type="datetime-local" name="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>End Date</label>
            <input type="datetime-local" name="end_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input id="address" name="address" value="{{ old('address', $event->address ?? '') }}" class="form-control" />
            @error('address') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Country</label>
            <input type="text" name="country" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Maximum Participants</label>
            <input type="number" name="max_participants" class="form-control" min="1">
            @error('max_participants') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Event Type</label>
            <select name="event_type_id" class="form-control" required>
                @foreach($eventTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Tags</label>
            @foreach($tags as $tag)
                <div class="form-check">
                    <input type="checkbox" name="tag_id" value="{{ $tag->id }}" class="form-check-input">
                    <label class="form-check-label">{{ $tag->name }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
@endsection