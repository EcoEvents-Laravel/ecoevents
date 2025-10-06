@extends('layouts.app')

@section('title', 'Créer un article - EcoEvents')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <a href="{{ route('blogs.index') }}" class="btn btn-link text-success mb-2">
                <i class="bi bi-arrow-left"></i> Retour aux articles
            </a>
            <h1 class="display-4 fw-bold text-dark">✨ Créer un nouvel article</h1>
            <p class="text-muted">Partagez vos connaissances avec la communauté EcoEvents</p>
        </div>
        <div class="col-lg-8">
            <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data" id="blogForm">
                @csrf
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold text-uppercase">
                        📝 Titre de l'article *
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}"
                        placeholder="Ex: Comment organiser un festival zéro déchet"
                        class="form-control @error('title') is-invalid @enderror"
                        required
                        maxlength="200"
                    >
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">Un titre accrocheur attire plus de lecteurs</small>
                        <small class="text-secondary" id="titleCount">0/200</small>
                    </div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="content" class="form-label fw-bold text-uppercase">
                        📄 Contenu de l'article *
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="10"
                        placeholder="Rédigez votre article ici... Utilisez des paragraphes clairs et concis."
                        class="form-control @error('content') is-invalid @enderror"
                        required
                    >{{ old('content') }}</textarea>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">💡 Conseil : Visez 800-1500 mots pour un article complet</small>
                        <small class="text-secondary" id="wordCount">0 mots</small>
                    </div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase">
                        🖼️ Image principale
                    </label>
                    <div class="border rounded p-4 text-center mb-2" id="uploadZone" style="cursor:pointer;">
                        <input type="file" id="image" name="image" accept="image/*" class="d-none">
                        <div id="uploadPlaceholder">
                            <i class="bi bi-image" style="font-size:2rem;color:#6c757d;"></i>
                            <p class="text-muted mb-1">Cliquez ou glissez une image ici</p>
                            <small class="text-secondary">PNG, JPG, WebP jusqu'à 10MB</small>
                        </div>
                        <div id="imagePreview" class="d-none">
                            <img src="" alt="Preview" class="img-fluid rounded mb-2" style="max-height:250px;">
                            <button type="button" id="removeImage" class="btn btn-sm btn-outline-danger">Supprimer l'image</button>
                        </div>
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase">
                        🏷️ Tags
                    </label>
                    <div class="border rounded p-2 mb-2 d-flex flex-wrap align-items-center" id="tagContainer">
                        <input 
                            type="text" 
                            id="tagInput" 
                            placeholder="Ajoutez des tags (Entrée pour valider)"
                            class="form-control border-0 flex-grow-1"
                            style="min-width:150px;"
                        >
                    </div>
                    <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags') }}">
                    <small class="text-muted">Appuyez sur Entrée pour ajouter chaque tag. Ex: Durabilité, Innovation, Tech</small>
                </div>
                <div class="mb-4">
                    <label for="status" class="form-label fw-bold text-uppercase">
                        Statut de publication
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        class="form-select"
                    >
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                            📝 Brouillon
                        </option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                            ✅ Publié
                        </option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>
                            📦 Archivé
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="publication_date" class="form-label fw-bold text-uppercase">
                        Date de publication
                    </label>
                    <input 
                        type="datetime-local" 
                        id="publication_date" 
                        name="publication_date" 
                        value="{{ old('publication_date') }}"
                        class="form-control"
                    >
                    <small class="text-muted">Laissez vide pour publier immédiatement</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="bi bi-check-lg"></i> Publier l'article
                    </button>
                    <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <div class="col-lg-4">
            <div class="bg-light border rounded p-4 mb-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-lightbulb"></i> Conseils d'écriture</h4>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><span class="text-success fw-bold">✓</span> Utilisez des titres clairs et descriptifs</li>
                    <li class="mb-2"><span class="text-success fw-bold">✓</span> Structurez avec des paragraphes courts</li>
                    <li class="mb-2"><span class="text-success fw-bold">✓</span> Ajoutez 3-5 tags pertinents</li>
                    <li class="mb-2"><span class="text-success fw-bold">✓</span> Relisez avant de publier</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Compteur de caractères pour le titre
    const titleInput = document.getElementById('title');
    const titleCount = document.getElementById('titleCount');
    function updateTitleCount() {
        titleCount.textContent = `${titleInput.value.length}/200`;
    }
    titleInput.addEventListener('input', updateTitleCount);
    updateTitleCount();

    // Compteur de mots pour le contenu
    const contentTextarea = document.getElementById('content');
    const wordCount = document.getElementById('wordCount');
    function updateWordCount() {
        const words = contentTextarea.value.trim().split(/\s+/).filter(word => word.length > 0).length;
        wordCount.textContent = `${words} mots`;
    }
    contentTextarea.addEventListener('input', updateWordCount);
    updateWordCount();

    // Gestion des tags
    const tagInput = document.getElementById('tagInput');
    const tagContainer = document.getElementById('tagContainer');
    const tagsHidden = document.getElementById('tagsHidden');
    let tags = tagsHidden.value ? tagsHidden.value.split(',').filter(t => t.trim()) : [];
    function renderTags() {
        tagContainer.querySelectorAll('.badge').forEach(tag => tag.remove());
        tags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.className = 'badge bg-success me-2 mb-1';
            tagElement.innerHTML = `
                ${tag}
                <span style="cursor:pointer;" onclick="removeTag('${tag}')">&times;</span>
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
                uploadPlaceholder.classList.add('d-none');
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
    removeImageBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        imageInput.value = '';
        uploadPlaceholder.classList.remove('d-none');
        imagePreview.classList.add('d-none');
    });
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('border-success');
    });
    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('border-success');
    });
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('border-success');
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
