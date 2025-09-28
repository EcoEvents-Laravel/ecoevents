<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    public function index()
    {
        $eventTypes = EventType::withCount('events')->latest()->paginate(10);
        return view('event-types.index', compact('eventTypes'));
    }

    public function create()
    {
        return view('event-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_types,name',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7', // e.g., #FF0000
            'icon' => 'nullable|string|max:255',
        ]);

        EventType::create($validated);

        return redirect()->route('event-types.index')->with('success', 'Event Type created successfully.');
    }

    public function show(EventType $eventType)
    {
        $eventType->load('events');
        return view('event-types.show', compact('eventType'));
    }

    public function edit(EventType $eventType)
    {
        return view('event-types.edit', compact('eventType'));
    }

    public function update(Request $request, EventType $eventType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_types,name,' . $eventType->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:255',
        ]);

        $eventType->update($validated);

        return redirect()->route('event-types.index')->with('success', 'Event Type updated successfully.');
    }

    public function destroy(EventType $eventType)
    {
        $eventType->delete();
        return redirect()->route('event-types.index')->with('success', 'Event Type deleted successfully.');
    }
}