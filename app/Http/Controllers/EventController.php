<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['eventType', 'tags']);

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by event type
        if ($request->filled('event_type_id')) {
            $query->where('event_type_id', $request->event_type_id);
        }


        $events = $query->latest()->paginate(10)->appends($request->query());
        $eventTypes = EventType::all();
        $tags = Tag::all();

        return view('events.index', compact('events', 'eventTypes', 'tags'));
    }

    public function create()
    {
        $eventTypes = EventType::all();
        $tags = Tag::all();
        return view('events.create', compact('eventTypes', 'tags'));
    }

    public function store(Request $request)
    {
        // Log file details for debugging
        if ($request->hasFile('banner_image')) {
            Log::info('File uploaded:', [
                'name' => $request->file('banner_image')->getClientOriginalName(),
                'mime' => $request->file('banner_image')->getClientMimeType(),
                'size' => $request->file('banner_image')->getSize(),
            ]);
        } else {
            Log::info('No file uploaded in request.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'city' => 'required|string',
            'country' => 'required|string',
            'address' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'event_type_id' => 'required|exists:event_types,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle file upload
        if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
            $path = $request->file('banner_image')->store('banners', 'public');
            $validated['banner_url'] = $path;
            Log::info('Image stored at: ' . $path);
        } else {
            $validated['banner_url'] = null;
        }

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
        // Log file details for debugging
        if ($request->hasFile('banner_image')) {
            Log::info('File uploaded for update:', [
                'name' => $request->file('banner_image')->getClientOriginalName(),
                'mime' => $request->file('banner_image')->getClientMimeType(),
                'size' => $request->file('banner_image')->getSize(),
            ]);
        } else {
            Log::info('No file uploaded for update.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'city' => 'required|string',
            'country' => 'required|string',
            'address' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'event_type_id' => 'required|exists:event_types,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle file upload
        if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
            // Delete old image if exists
            if ($event->banner_url) {
                Storage::disk('public')->delete($event->banner_url);
            }
            $path = $request->file('banner_image')->store('banners', 'public');
            $validated['banner_url'] = $path;
            Log::info('Image stored at: ' . $path);
        }

        $event->update($validated);
        $event->tags()->sync($request->tags ?? []);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->banner_url) {
            Storage::disk('public')->delete($event->banner_url);
        }
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}