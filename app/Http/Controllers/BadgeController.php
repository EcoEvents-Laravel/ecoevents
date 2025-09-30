<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Http\Controllers\UserBadgeController;
use App\Http\Requests\BadgeRequest;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $badges = Badge::all(); // Fetch badges from the database
        return view('badge.index', compact('badges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $request=Request();
        $badge=new Badge();
        $badge->name=$request->name;
        $badge->description=$request->description;
        $badge->icon=$request->icon;
        $badge->create();
        return redirect()->route('badge.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BadgeRequest $request)
    {
        $badge = new Badge();
        $badge->name = $request->input('name');
        $badge->description = $request->input('description');
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $path = $file->store('badges', 'public');
            $badge->icon = $path;
        }
        Badge::create($request->validated());
        $badge->save();
        return redirect()->route('badge.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $badge = Badge::findOrFail($id);
        return view('badge.show', compact('badge'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BadgeRequest $request, string $id)
    {
        $badge = Badge::findOrFail($id);
        $badge->name = $request->input('name');
        $badge->description = $request->input('description');
        $badge->icon = $request->input('icon');
        Badge::where('id', $id)->update($request->validated());
        return redirect()->route('badge.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();
        return redirect()->route('badge.index');
    }
}
