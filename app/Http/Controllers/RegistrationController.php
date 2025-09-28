<?php
namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RegistrationController extends Controller {
    public function index() {
        $registrations = Auth::user()->registrations()->with('event')->latest()->paginate(10);
        return view('registrations.index', compact('registrations'));
    }
    public function store(Request $request) {
        $request->validate(['event_id' => ['required', 'exists:events,id']]);
        $event = Event::findOrFail($request->event_id);
        if ($event->registrations()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Vous êtes déjà inscrit à cet événement.');
        }
        $event->registrations()->create(['user_id' => auth()->id()]);
        return back()->with('success', 'Votre inscription a été confirmée !');
    }
    public function destroy(Registration $registration) {
        Gate::authorize('delete', $registration);
        $registration->delete();
        return back()->with('success', 'Votre inscription a bien été annulée.');
    }
}