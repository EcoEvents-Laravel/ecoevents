<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBadge;
use App\Http\Requests\UserBadgeRequest;

class UserBadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_badges = UserBadge::all(); // Fetch user badges from the database
        return view('user_badge.index', compact('user_badges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $request=Request();
        $user_badge=new UserBadge();
        $user_badge->user_id=$request->user_id;
        $user_badge->badge_id=$request->badge_id;
        $user_badge->create();
        return redirect()->route('user_badge.index');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserBadgeRequest $request)
    {
        $user_badge = new UserBadge();
        $user_badge->user_id = $request->user_id;
        $user_badge->badge_id = $request->badge_id;
        $user_badge->save();
        return redirect()->route('user_badge.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_badge = UserBadge::findOrFail($id);
        return view('user_badge.show', compact('user_badge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserBadgeRequest $request, string $id)
    {
        $user_badge = UserBadge::findOrFail($id);
        $user_badge->user_id = $request->input('user_id');
        $user_badge->badge_id = $request->input('badge_id');
        $user_badge->save();
        return redirect()->route('user_badge.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user_badge = UserBadge::findOrFail($id);
        $user_badge->delete();
        return redirect()->route('user_badge.index');
    }
}
