<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /blogs
     */
    public function index(Request $request): View
    {
        $query = Blog::with(['author', 'tags']);

        // 🔍 Recherche par titre, contenu ou tags
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('tags', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('author', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 🎯 Filtrer par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Par défaut, afficher uniquement les articles publiés
            $query->where('status', 'published');
        }

        // 🏷️ Filtrer par tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // 📅 Trier par date de publication décroissante
        $blogs = $query->latest('publication_date')
                       ->latest('created_at')
                       ->paginate(12)
                       ->withQueryString();

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     * GET /blogs/create
     */
    public function create(): View
    {
        $tags = Tag::orderBy('name')->get();
        return view('blogs.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /blogs
     */
    public function store(Request $request): RedirectResponse
    {
        // 1️⃣ Validation des données
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string|min:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'publication_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 200 caractères.',
            'content.required' => 'Le contenu est obligatoire.',
            'content.min' => 'Le contenu doit contenir au moins 100 caractères.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne peut pas dépasser 10MB.',
            'status.in' => 'Le statut doit être : brouillon, publié ou archivé.',
        ]);

        // 2️⃣ Gérer l'upload d'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog-images', 'public');
            $validated['image_url'] = Storage::url($imagePath);
        }

        // 3️⃣ CORRECTION : Utiliser un auteur par défaut si pas connecté
        $validated['author_id'] = auth()->id() ?? 1; // ⚠️ Utilise l'ID 1 par défaut

        // 4️⃣ Si publié sans date, définir maintenant
        if ($validated['status'] === 'published' && empty($validated['publication_date'])) {
            $validated['publication_date'] = now();
        }

        // 5️⃣ Créer l'article
        $blog = Blog::create($validated);

        // 6️⃣ Gérer les tags
        if (!empty($validated['tags'])) {
            $this->syncTags($blog, $validated['tags']);
        }

        // 7️⃣ Redirection avec message de succès
        return redirect()
            ->route('blogs.show', $blog)
            ->with('success', '🎉 Article créé avec succès !');
    }

    /**
     * Display the specified resource.
     * GET /blogs/{blog}
     */
    public function show(Blog $blog): View
    {
        $blog->load(['author', 'tags']);
        return view('blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     * GET /blogs/{blog}/edit
     */
    public function edit(Blog $blog): View
    {
        // 🔒 CORRECTION : Désactiver temporairement la vérification d'auteur
        // if (auth()->id() !== $blog->author_id) {
        //     abort(403, '⛔ Vous n\'êtes pas autorisé à modifier cet article.');
        // }

        $blog->load('tags');
        $tags = Tag::orderBy('name')->get();

        return view('blogs.edit', compact('blog', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /blogs/{blog}
     */
    public function update(Request $request, Blog $blog): RedirectResponse
    {
        // 🔒 CORRECTION : Désactiver temporairement la vérification d'auteur
        // if (auth()->id() !== $blog->author_id) {
        //     abort(403, '⛔ Vous n\'êtes pas autorisé à modifier cet article.');
        // }

        // 1️⃣ Validation
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string|min:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'publication_date' => 'nullable|date',
            'status' => 'required|in:draft,published,archived',
            'tags' => 'nullable|string',
        ]);

        // 2️⃣ Gérer le nouvel upload d'image
        if ($request->hasFile('image')) {
            if ($blog->image_url) {
                $oldPath = str_replace('/storage/', '', parse_url($blog->image_url, PHP_URL_PATH));
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('blog-images', 'public');
            $validated['image_url'] = Storage::url($imagePath);
        }

        // 3️⃣ Si publié sans date, définir maintenant
        if ($validated['status'] === 'published' && empty($blog->publication_date) && empty($validated['publication_date'])) {
            $validated['publication_date'] = now();
        }

        // 4️⃣ Mettre à jour l'article
        $blog->update($validated);

        // 5️⃣ Mettre à jour les tags
        if (isset($validated['tags'])) {
            $this->syncTags($blog, $validated['tags']);
        } else {
            $blog->tags()->detach();
        }

        // 6️⃣ Redirection
        return redirect()
            ->route('blogs.show', $blog)
            ->with('success', '✅ Article mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /blogs/{blog}
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        // 🔒 CORRECTION : Désactiver temporairement la vérification d'auteur
        // if (auth()->id() !== $blog->author_id) {
        //     abort(403, '⛔ Vous n\'êtes pas autorisé à supprimer cet article.');
        // }

        // 1️⃣ Supprimer l'image si elle existe
        if ($blog->image_url) {
            $imagePath = str_replace('/storage/', '', parse_url($blog->image_url, PHP_URL_PATH));
            Storage::disk('public')->delete($imagePath);
        }

        // 2️⃣ Détacher les tags
        $blog->tags()->detach();

        // 3️⃣ Supprimer l'article
        $blog->delete();

        // 4️⃣ Redirection
        return redirect()
            ->route('blogs.index')
            ->with('success', '🗑️ Article supprimé avec succès.');
    }

    /**
     * Display the media gallery.
     * GET /blogs/gallery
     */
    public function gallery(): View
    {
        return view('blogs.gallery');
    }

    /**
     * Méthode privée pour synchroniser les tags
     */
    private function syncTags(Blog $blog, string $tagsString): void
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            if (!empty($tagName)) {
                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );
                $tagIds[] = $tag->id;
            }
        }

        $blog->tags()->sync($tagIds);
    }
}