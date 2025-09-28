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
        // ... (ton code pour store reste inchangé)
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function show(Event $event)
    {
        // On charge les relations existantes ET les commentaires avec leurs utilisateurs.
        $event->load(['eventType', 'tags', 'comments.user']);

        // On initialise la variable pour l'inscription de l'utilisateur à null.
        $userRegistration = null;

        // Si un utilisateur est connecté, on cherche son inscription pour cet événement.
        if (auth()->check()) {
            $userRegistration = $event->registrations()->where('user_id', auth()->id())->first();
        }

        // On envoie les deux variables ('event' et 'userRegistration') à la vue.
        return view('events.show', compact('event', 'userRegistration'));
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
        // ... (ton code pour update reste inchangé)
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

        if ($request->hasFile('banner_image') && $request->file('banner_image')->isValid()) {
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