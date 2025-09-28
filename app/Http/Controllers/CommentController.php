<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();
        
        $event = Event::findOrFail($validated['event_id']);

        $event->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Votre commentaire a été publié.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        // Utilise la Policy pour l'autorisation
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Le commentaire a été supprimé.');
    }
}