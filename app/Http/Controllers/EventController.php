<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['eventType', 'tags'])->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $eventTypes = EventType::all();
        $tags = Tag::all();
        return view('events.create', compact('eventTypes', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'city' => 'required|string',
            'country' => 'required|string',
            'address' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'event_type_id' => 'required|exists:event_types,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            // Add other fields as needed
        ]);

        $event = Event::create($validated);
        $event->tags()->sync($request->tags ?? []);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['eventType', 'tags']);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $eventTypes = EventType::all();
        $tags = Tag::all();
        $event->load('tags');
        return view('events.edit', compact('event', 'eventTypes', 'tags'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'city' => 'required|string',
            'country' => 'required|string',
            'event_type_id' => 'required|exists:event_types,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $event->update($validated);
        $event->tags()->sync($request->tags ?? []);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}