<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organisations = Organisation::latest()->paginate(12);
        return view('organisations.index', compact('organisations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organisations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo_url' => 'nullable|url',
            'website' => 'nullable|url',
            'contact_email' => 'required|email|max:255',
        ]);

        Organisation::create($validated);

        return redirect()->route('organisations.index')
                        ->with('success', 'Organisation créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organisation $organisation)
    {
        return view('organisations.show', compact('organisation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organisation $organisation)
    {
        return view('organisations.edit', compact('organisation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organisation $organisation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo_url' => 'nullable|url',
            'website' => 'nullable|url',
            'contact_email' => 'required|email|max:255',
        ]);

        $organisation->update($validated);

        return redirect()->route('organisations.index')
                        ->with('success', 'Organisation mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organisation $organisation)
    {
        // Delete logo file if exists
        if ($organisation->logo_url && Storage::disk('public')->exists($organisation->logo_url)) {
            Storage::disk('public')->delete($organisation->logo_url);
        }

        $organisation->delete();

        return redirect()->route('organisations.index')
                        ->with('success', 'Organisation supprimée avec succès.');
    }
}
