@extends(!auth()->user() ? 'layouts.app' : 'layouts.front')

@section('title',!auth()->user() ? 'Modifier l\'article - ' . $blog->title : 'Modifier l\'article - ' . $blog->title)

@section('content')
@if(!auth()->user())
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('blogs.show', $blog) }}" class="btn btn-link">
            &larr; Retour à l'article
        </a>
        <h1 class="display-4">✏️ Modifier l'article</h1>
        <p class="text-muted">Apportez des modifications à votre article</p>
    </div>

    <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                {{-- Titre --}}
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold">📝 Titre de l'article *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $blog->title) }}"
                        placeholder="Ex: Comment organiser un festival zéro déchet"
                        class="form-control @error('title') is-invalid @enderror"
                        required
                        maxlength="200"
                    >
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Un titre accrocheur attire plus de lecteurs</small>
                        <small class="text-muted" id="titleCount">0/200</small>
                    </div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contenu --}}
                <div class="mb-4">
                    <label for="content" class="form-label fw-bold">📄 Contenu de l'article *</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="10"
                        placeholder="Rédigez votre article ici... Utilisez des paragraphes clairs et concis."
                        class="form-control @error('content') is-invalid @enderror"
                        required
                    >{{ old('content', $blog->content) }}</textarea>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">💡 Conseil : Visez 800-1500 mots pour un article complet</small>
                        <small class="text-muted" id="wordCount">0 mots</small>
                    </div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">🖼️ Image principale</label>
                    @if($blog->image_url)
                        <div class="mb-2">
                            <p class="text-muted mb-1">Image actuelle :</p>
                            <div class="position-relative d-inline-block">
                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-thumbnail" style="width: 200px; height: 130px; object-fit: cover;">
                                <button type="button" id="removeCurrentImage" class="btn btn-sm btn-danger position-absolute top-0 end-0">
                                    &times;
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="mb-2">
                        <input type="file" id="image" name="image" accept="image/*" class="form-control">
                        <small class="text-muted">PNG, JPG, WebP jusqu'à 10MB</small>
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tags --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">🏷️ Tags</label>
                    <input 
                        type="text" 
                        id="tagInput" 
                        placeholder="Ajoutez des tags (Entrée pour valider)"
                        class="form-control mb-2"
                    >
                    <div id="tagContainer" class="mb-2"></div>
                    <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags', $blog->tags->pluck('name')->implode(',')) }}">
                    <small class="text-muted">Appuyez sur Entrée pour ajouter chaque tag. Ex: Durabilité, Innovation, Tech</small>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Paramètres</h5>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut de publication</label>
                            <select 
                                id="status" 
                                name="status" 
                                class="form-select"
                            >
                                <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                                <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>✅ Publié</option>
                                <option value="archived" {{ old('status', $blog->status) == 'archived' ? 'selected' : '' }}>📦 Archivé</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="publication_date" class="form-label">Date de publication</label>
                            <input 
                                type="datetime-local" 
                                id="publication_date" 
                                name="publication_date" 
                                value="{{ old('publication_date', $blog->publication_date ? $blog->publication_date->format('Y-m-d\TH:i') : '') }}"
                                class="form-control"
                            >
                            <small class="text-muted">Date actuelle : {{ $blog->publication_date?->format('d/m/Y H:i') ?? 'Non définie' }}</small>
                        </div>
                        <div class="border-top pt-3">
                            <small class="text-muted">Auteur</small>
                            <div class="d-flex align-items-center mt-1">
                                <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;">
                                    {{ substr($blog->author->name, 0, 1) }}
                                </span>
                                <span>{{ $blog->author->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">
                    Mettre à jour l'article
                </button>
                <a href="{{ route('blogs.show', $blog) }}" class="btn btn-outline-secondary w-100 mb-2">
                    Annuler
                </a>
                <form action="{{ route('blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement cet article ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        Supprimer l'article
                    </button>
                </form>

                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title">ℹ️ Informations</h6>
                        <ul class="list-unstyled mb-0">
                            <li>Créé le : {{ $blog->created_at->format('d/m/Y à H:i') }}</li>
                            <li>Modifié le : {{ $blog->updated_at->format('d/m/Y à H:i') }}</li>
                            <li>ID de l'article : #{{ $blog->id }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@else
@push('styles')
<style>
    /* Éditeur moderne */
    .form-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
    }

    .input-field {
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .input-field:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        transform: translateY(-2px);
    }

    .tag-input {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        min-height: 3rem;
    }

    .tag-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #10b981;
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        animation: tagAppear 0.3s ease;
    }

    @keyframes tagAppear {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .tag-remove {
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .tag-remove:hover {
        transform: scale(1.2);
    }

    .preview-panel {
        position: sticky;
        top: 2rem;
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
    }

    .upload-zone {
        border: 2px dashed #cbd5e0;
        transition: all 0.3s ease;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.05);
    }
</style>
@endpush
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-blue-50 py-12">
    <div class="container mx-auto px-4">
        
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('blogs.show', $blog) }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à l'article
            </a>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900">
                ✏️ Modifier l'article
            </h1>
            <p class="text-gray-600 mt-2">
                Apportez des modifications à votre article
            </p>
        </div>

        <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" id="blogForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Formulaire principal --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Titre --}}
                    <div class="form-container rounded-2xl shadow-lg p-6">
                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            📝 Titre de l'article *
                        </label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            value="{{ old('title', $blog->title) }}"
                            placeholder="Ex: Comment organiser un festival zéro déchet"
                            class="input-field w-full px-4 py-3 rounded-xl focus:outline-none text-lg @error('title') border-red-500 @enderror"
                            required
                            maxlength="200"
                        >
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-gray-500">Un titre accrocheur attire plus de lecteurs</span>
                            <span class="text-xs text-gray-400" id="titleCount">0/200</span>
                        </div>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contenu --}}
                    <div class="form-container rounded-2xl shadow-lg p-6">
                        <label for="content" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            📄 Contenu de l'article *
                        </label>
                        <textarea 
                            id="content" 
                            name="content" 
                            rows="20"
                            placeholder="Rédigez votre article ici... Utilisez des paragraphes clairs et concis."
                            class="input-field w-full px-4 py-3 rounded-xl focus:outline-none resize-none @error('content') border-red-500 @enderror"
                            required
                        >{{ old('content', $blog->content) }}</textarea>
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-gray-500">💡 Conseil : Visez 800-1500 mots pour un article complet</span>
                            <span class="text-xs text-gray-400" id="wordCount">0 mots</span>
                        </div>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="form-container rounded-2xl shadow-lg p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            🖼️ Image principale
                        </label>
                        
                        {{-- Image actuelle --}}
                        @if($blog->image_url)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                                <div class="relative inline-block">
                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-48 h-32 object-cover rounded-lg shadow-md">
                                    <button type="button" id="removeCurrentImage" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="upload-zone rounded-xl p-8 text-center cursor-pointer" id="uploadZone">
                            <input type="file" id="image" name="image" accept="image/*" class="hidden">
                            
                            <div id="uploadPlaceholder">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-600 font-medium mb-2">Cliquez ou glissez une nouvelle image ici</p>
                                <p class="text-xs text-gray-500">PNG, JPG, WebP jusqu'à 10MB</p>
                            </div>
                            
                            <div id="imagePreview" class="hidden">
                                <img src="" alt="Preview" class="max-h-64 mx-auto rounded-lg shadow-md">
                                <button type="button" id="removeImage" class="mt-4 text-red-600 hover:text-red-700 font-medium text-sm">
                                    Supprimer la nouvelle image
                                </button>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tags --}}
                    <div class="form-container rounded-2xl shadow-lg p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            🏷️ Tags
                        </label>
                        <div class="tag-input" id="tagContainer">
                            <input 
                                type="text" 
                                id="tagInput" 
                                placeholder="Ajoutez des tags (Entrée pour valider)"
                                class="flex-1 min-w-[200px] outline-none text-sm"
                            >
                        </div>
                        <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags', $blog->tags->pluck('name')->implode(',')) }}">
                        <p class="text-xs text-gray-500 mt-2">Appuyez sur Entrée pour ajouter chaque tag. Ex: Durabilité, Innovation, Tech</p>
                    </div>

                </div>

                {{-- Sidebar - Paramètres --}}
                <div class="lg:col-span-1">
                    <div class="preview-panel space-y-6">
                        
                        {{-- Paramètres de publication --}}
                        <div class="form-container rounded-2xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Paramètres
                            </h3>

                            {{-- Statut --}}
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Statut de publication
                                </label>
                                <select 
                                    id="status" 
                                    name="status" 
                                    class="input-field w-full px-4 py-2 rounded-lg focus:outline-none"
                                >
                                    <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>
                                        📝 Brouillon
                                    </option>
                                    <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>
                                        ✅ Publié
                                    </option>
                                    <option value="archived" {{ old('status', $blog->status) == 'archived' ? 'selected' : '' }}>
                                        📦 Archivé
                                    </option>
                                </select>
                            </div>

                            {{-- Date de publication --}}
                            <div class="mb-4">
                                <label for="publication_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Date de publication
                                </label>
                                <input 
                                    type="datetime-local" 
                                    id="publication_date" 
                                    name="publication_date" 
                                    value="{{ old('publication_date', $blog->publication_date ? $blog->publication_date->format('Y-m-d\TH:i') : '') }}"
                                    class="input-field w-full px-4 py-2 rounded-lg focus:outline-none"
                                >
                                <p class="text-xs text-gray-500 mt-1">Date actuelle : {{ $blog->publication_date?->format('d/m/Y H:i') ?? 'Non définie' }}</p>
                            </div>

                            {{-- Auteur --}}
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-1">Auteur</p>
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-xs">
                                        {{ substr($blog->author->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-sm text-gray-900">{{ $blog->author->name }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="space-y-3">
                            <button 
                                type="submit" 
                                class="w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Mettre à jour l'article
                            </button>

                            <a 
                                href="{{ route('blogs.show', $blog) }}" 
                                class="w-full block text-center px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-gray-300 transition-all duration-300"
                            >
                                Annuler
                            </a>

                            <form action="{{ route('blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    class="w-full px-6 py-3 bg-red-50 border-2 border-red-200 text-red-600 font-semibold rounded-xl hover:bg-red-100 hover:border-red-300 transition-all duration-300 flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Supprimer l'article
                                </button>
                            </form>
                        </div>

                        {{-- Informations supplémentaires --}}
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
                            <h4 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                ℹ️ Informations
                            </h4>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-500 font-bold">•</span>
                                    <span>Créé le : {{ $blog->created_at->format('d/m/Y à H:i') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-500 font-bold">•</span>
                                    <span>Modifié le : {{ $blog->updated_at->format('d/m/Y à H:i') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-500 font-bold">•</span>
                                    <span>ID de l'article : #{{ $blog->id }}</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
     // Compteur de caractères pour le titre
    const titleInput = document.getElementById('title');
    const titleCount = document.getElementById('titleCount');
    
    function updateTitleCount() {
        titleCount.textContent = `${titleInput.value.length}/200`;
    }
    
    titleInput.addEventListener('input', updateTitleCount);
    updateTitleCount(); // Initialiser

    // Compteur de mots pour le contenu
    const contentTextarea = document.getElementById('content');
    const wordCount = document.getElementById('wordCount');
    
    function updateWordCount() {
        const words = contentTextarea.value.trim().split(/\s+/).filter(word => word.length > 0).length;
        wordCount.textContent = `${words} mots`;
    }
    
    contentTextarea.addEventListener('input', updateWordCount);
    updateWordCount(); // Initialiser

    // Gestion des tags
    const tagInput = document.getElementById('tagInput');
    const tagContainer = document.getElementById('tagContainer');
    const tagsHidden = document.getElementById('tagsHidden');
    let tags = tagsHidden.value ? tagsHidden.value.split(',').filter(t => t.trim()) : [];

    function renderTags() {
        tagContainer.querySelectorAll('.tag-item').forEach(tag => tag.remove());
        
        tags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.className = 'tag-item';
            tagElement.innerHTML = `
                ${tag}
                <svg class="w-4 h-4 tag-remove" fill="currentColor" viewBox="0 0 20 20" onclick="removeTag('${tag}')">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            `;
            tagContainer.insertBefore(tagElement, tagInput);
        });
        
        tagsHidden.value = tags.join(',');
    }

    window.removeTag = function(tagToRemove) {
        tags = tags.filter(tag => tag !== tagToRemove);
        renderTags();
    }

    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const tag = this.value.trim();
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                renderTags();
                this.value = '';
            }
        }
    });

    renderTags(); // Initialiser les tags existants

    // Gestion de l'upload d'image
    const uploadZone = document.getElementById('uploadZone');
    const imageInput = document.getElementById('image');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImage');

    uploadZone.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                uploadPlaceholder.classList.add('hidden');
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    removeImageBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        imageInput.value = '';
        uploadPlaceholder.classList.remove('hidden');
        imagePreview.classList.add('hidden');
    });

    // Supprimer l'image actuelle
    const removeCurrentImageBtn = document.getElementById('removeCurrentImage');
    removeCurrentImageBtn?.addEventListener('click', function() {
        if (confirm('Voulez-vous supprimer l\'image actuelle ?')) {
            this.closest('div').remove();
        }
    });

    // Drag & Drop
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            const event = new Event('change');
            imageInput.dispatchEvent(event);
        }
    });

    // Validation avant soumission
    document.getElementById('blogForm').addEventListener('submit', function(e) {
        const title = titleInput.value.trim();
        const content = contentTextarea.value.trim();
        
        if (!title || !content) {
            e.preventDefault();
            alert('Veuillez remplir le titre et le contenu de l\'article.');
            return false;
        }
    });
</script>
@endpush
@endsection
