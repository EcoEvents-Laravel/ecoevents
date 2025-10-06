{{-- resources/views/blogs/_form.blade.php --}}
{{-- Formulaire réutilisable pour create et edit --}}
@section('title', isset($blog) ? 'Modifier l\'article' : 'Créer un article')

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

    /* Preview panel */
    .preview-panel {
        position: sticky;
        top: 2rem;
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
    }

    /* Image upload zone */
    .upload-zone {
        border: 2px dashed #cbd5e0;
        transition: all 0.3s ease;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.05);
    }

    /* Status badge */
    .status-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-blue-50 py-12">
    <div class="container mx-auto px-4">
        
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('blogs.index') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour aux articles
            </a>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900">
                {{ isset($blog) ? '✏️ Modifier l\'article' : '✨ Créer un nouvel article' }}
            </h1>
            <p class="text-gray-600 mt-2">
                {{ isset($blog) ? 'Apportez des modifications à votre article' : 'Partagez vos connaissances avec la communauté EcoEvents' }}
            </p>
        </div>

        <form action="{{ isset($blog) ? route('blogs.update', $blog) : route('blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
            @csrf
            @if(isset($blog))
                @method('PUT')
            @endif

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
                            value="{{ old('title', $blog->title ?? '') }}"
                            placeholder="Ex: Comment organiser un festival zéro déchet"
                            class="input-field w-full px-4 py-3 rounded-xl focus:outline-none text-lg"
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
                            class="input-field w-full px-4 py-3 rounded-xl focus:outline-none resize-none"
                            required
                        >{{ old('content', $blog->content ?? '') }}</textarea>
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-gray-500">💡 Conseil : Visez 800-1500 mots pour un article complet</span>
                            <span class="text-xs text-gray-400" id="wordCount">0 mots</span>
                        </div>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image (optionnel) --}}
                    <div class="form-container rounded-2xl shadow-lg p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                            🖼️ Image principale (optionnel)
                        </label>
                        <div class="upload-zone rounded-xl p-8 text-center cursor-pointer" id="uploadZone">
                            <input type="file" id="image" name="image" accept="image/*" class="hidden">
                            <input type="hidden" name="image_url" value="{{ old('image_url', $blog->image_url ?? '') }}">
                            
                            <div id="uploadPlaceholder">
                                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-600 font-medium mb-2">Cliquez ou glissez une image ici</p>
                                <p class="text-xs text-gray-500">PNG, JPG, WebP jusqu'à 10MB</p>
                            </div>
                            
                            <div id="imagePreview" class="hidden">
                                <img src="" alt="Preview" class="max-h-64 mx-auto rounded-lg shadow-md">
                                <button type="button" id="removeImage" class="mt-4 text-red-600 hover:text-red-700 font-medium text-sm">
                                    Supprimer l'image
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
                        <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags', isset($blog) ? $blog->tags->pluck('name')->implode(',') : '') }}">
                        <p class="text-xs text-gray-500 mt-2">Appuyez sur Entrée pour ajouter chaque tag. Ex: Durabilité, Innovation, Tech</p>
                    </div>

                </div>

                {{-- Sidebar - Paramètres et Preview --}}
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
                                    <option value="draft" {{ old('status', $blog->status ?? 'draft') == 'draft' ? 'selected' : '' }}>
                                        📝 Brouillon
                                    </option>
                                    <option value="published" {{ old('status', $blog->status ?? '') == 'published' ? 'selected' : '' }}>
                                        ✅ Publié
                                    </option>
                                    <option value="archived" {{ old('status', $blog->status ?? '') == 'archived' ? 'selected' : '' }}>
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
                                    value="{{ old('publication_date', isset($blog) && $blog->publication_date ? $blog->publication_date->format('Y-m-d\TH:i') : '') }}"
                                    class="input-field w-full px-4 py-2 rounded-lg focus:outline-none"
                                >
                                <p class="text-xs text-gray-500 mt-1">Laissez vide pour publier immédiatement</p>
                            </div>

                            {{-- Auteur (caché, automatique) --}}
                            @auth
                            <input type="hidden" name="author_id" value="{{ auth()->id() }}">
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-1">Auteur</p>
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-xs">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-sm text-gray-900">{{ auth()->user()->name }}</span>
                                </div>
                            </div>
                            @endauth
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="space-y-3">
                            <button 
                                type="submit" 
                                class="w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ isset($blog) ? 'Mettre à jour' : 'Publier l\'article' }}
                            </button>

                            <button 
                                type="button" 
                                onclick="document.getElementById('status').value='draft'; document.getElementById('blogForm').submit();"
                                class="w-full px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Enregistrer comme brouillon
                            </button>

                            <a 
                                href="{{ route('blogs.index') }}" 
                                class="w-full block text-center px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:border-gray-300 transition-all duration-300"
                            >
                                Annuler
                            </a>
                        </div>

                        {{-- Conseils d'écriture --}}
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-2xl p-6">
                            <h4 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                💡 Conseils d'écriture
                            </h4>
                            <ul class="space-y-2 text-sm text-blue-800">
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 font-bold">✓</span>
                                    <span>Utilisez des titres clairs et descriptifs</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 font-bold">✓</span>
                                    <span>Structurez avec des paragraphes courts</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 font-bold">✓</span>
                                    <span>Ajoutez 3-5 tags pertinents</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-500 font-bold">✓</span>
                                    <span>Relisez avant de publier</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Compteur de caractères pour le titre
    const titleInput = document.getElementById('title');
    const titleCount = document.getElementById('titleCount');
    
    titleInput.addEventListener('input', function() {
        titleCount.textContent = `${this.value.length}/200`;
    });
    
    // Initialiser le compteur
    titleCount.textContent = `${titleInput.value.length}/200`;

    // Compteur de mots pour le contenu
    const contentTextarea = document.getElementById('content');
    const wordCount = document.getElementById('wordCount');
    
    contentTextarea.addEventListener('input', function() {
        const words = this.value.trim().split(/\s+/).filter(word => word.length > 0).length;
        wordCount.textContent = `${words} mots`;
    });
    
    // Initialiser le compteur de mots
    const initialWords = contentTextarea.value.trim().split(/\s+/).filter(word => word.length > 0).length;
    wordCount.textContent = `${initialWords} mots`;

    // Gestion des tags
    const tagInput = document.getElementById('tagInput');
    const tagContainer = document.getElementById('tagContainer');
    const tagsHidden = document.getElementById('tagsHidden');
    let tags = tagsHidden.value ? tagsHidden.value.split(',') : [];

    function renderTags() {
        // Supprimer tous les tags existants sauf l'input
        tagContainer.querySelectorAll('.tag-item').forEach(tag => tag.remove());
        
        // Ajouter chaque tag
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
        
        // Mettre à jour le champ caché
        tagsHidden.value = tags.join(',');
    }

    function removeTag(tagToRemove) {
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

    // Initialiser les tags existants
    renderTags();

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

    removeImageBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        imageInput.value = '';
        uploadPlaceholder.classList.remove('hidden');
        imagePreview.classList.add('hidden');
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
        
        if (content.split(/\s+/).length < 100) {
            if (!confirm('Votre article contient moins de 100 mots. Voulez-vous continuer ?')) {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
@endpush
@endsection