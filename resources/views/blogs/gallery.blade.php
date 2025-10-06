{{-- resources/views/blogs/gallery.blade.php --}}
@extends('layouts.app')

@section('title', 'Galerie Média - EcoEvents')

@push('styles')
<style>
    /* Grid masonry effect */
    .masonry-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        grid-auto-rows: 10px;
        gap: 1.5rem;
    }

    .masonry-item {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
    }

    .masonry-item:hover {
        transform: scale(1.03);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    .masonry-item img,
    .masonry-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .masonry-item:hover img,
    .masonry-item:hover video {
        transform: scale(1.1);
    }

    .media-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.5rem;
        color: white;
    }

    .masonry-item:hover .media-overlay {
        opacity: 1;
    }

    /* Lightbox modal */
    .lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .lightbox.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-content {
        max-width: 90vw;
        max-height: 90vh;
        animation: zoomIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes zoomIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Filter buttons */
    .filter-btn {
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background: linear-gradient(to right, #10b981, #3b82f6);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Upload button floating */
    .upload-fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #10b981, #3b82f6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 100;
    }

    .upload-fab:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 12px 32px rgba(16, 185, 129, 0.5);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-blue-50">
    
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-r from-green-600 to-blue-600 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')]"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-5xl md:text-6xl font-black mb-6">
                📸 Galerie Média EcoEvents
            </h1>
            <p class="text-xl md:text-2xl text-green-100 max-w-3xl mx-auto">
                Découvrez les moments forts de nos événements durables en images et vidéos
            </p>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        
        {{-- Filtres --}}
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button class="filter-btn active px-6 py-3 bg-white rounded-full font-semibold shadow-md hover:shadow-lg" data-filter="all">
                Tous les médias
            </button>
            <button class="filter-btn px-6 py-3 bg-white rounded-full font-semibold shadow-md hover:shadow-lg" data-filter="images">
                🖼️ Images
            </button>
            <button class="filter-btn px-6 py-3 bg-white rounded-full font-semibold shadow-md hover:shadow-lg" data-filter="videos">
                🎥 Vidéos
            </button>
            <button class="filter-btn px-6 py-3 bg-white rounded-full font-semibold shadow-md hover:shadow-lg" data-filter="events">
                🎉 Événements
            </button>
        </div>

        {{-- Galerie Masonry --}}
        <div class="masonry-grid" id="mediaGrid">
            
            {{-- Exemples de médias (à remplacer par une boucle dynamique) --}}
            @for($i = 1; $i <= 20; $i++)
                @php
                    $isVideo = $i % 5 == 0;
                    $height = rand(250, 450);
                @endphp
                
                <div class="masonry-item media-item {{ $isVideo ? 'video' : 'image' }}" style="grid-row-end: span {{ ceil($height / 10) }};" data-type="{{ $isVideo ? 'videos' : 'images' }}" data-category="events">
                    @if($isVideo)
                        <video src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" class="w-full h-full object-cover" muted loop></video>
                        <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                            </svg>
                            Vidéo
                        </div>
                    @else
                        <img src="https://picsum.photos/seed/media{{ $i }}/600/{{ $height }}" alt="Média {{ $i }}" loading="lazy">
                    @endif
                    
                    <div class="media-overlay">
                        <h3 class="font-bold text-lg mb-2">Festival Zéro Déchet {{ $i }}</h3>
                        <p class="text-sm text-gray-200 mb-3">{{ $i }} Octobre 2025</p>
                        <div class="flex gap-2">
                            <button class="flex-1 py-2 bg-white/20 backdrop-blur-sm rounded-lg hover:bg-white/30 transition-colors duration-300 text-sm font-medium" onclick="openLightbox({{ $i }}, {{ $isVideo ? 'true' : 'false' }})">
                                👁️ Voir
                            </button>
                            <button class="py-2 px-4 bg-green-500 hover:bg-green-600 rounded-lg transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endfor

        </div>

        {{-- Message si aucun résultat --}}
        <div id="noResults" class="hidden text-center py-20">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun média trouvé</h3>
            <p class="text-gray-600">Essayez un autre filtre ou ajoutez du nouveau contenu</p>
        </div>
    </div>

    {{-- Bouton flottant d'upload --}}
    @auth
    <div class="upload-fab" onclick="document.getElementById('uploadModal').classList.add('active')">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </div>
    @endauth

    {{-- Lightbox Modal --}}
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors duration-300 z-10" onclick="closeLightbox()">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="lightbox-content" onclick="event.stopPropagation()">
            <img id="lightboxImage" src="" alt="Lightbox" class="max-w-full max-h-full rounded-lg hidden">
            <video id="lightboxVideo" src="" controls class="max-w-full max-h-full rounded-lg hidden"></video>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-4">
            <button class="p-3 bg-white/10 backdrop-blur-md rounded-full hover:bg-white/20 transition-colors duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button class="p-3 bg-white/10 backdrop-blur-md rounded-full hover:bg-white/20 transition-colors duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </button>
            <button class="p-3 bg-white/10 backdrop-blur-md rounded-full hover:bg-white/20 transition-colors duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Upload Modal --}}
    <div class="lightbox" id="uploadModal">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full mx-4" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-900">📤 Ajouter un média</h2>
                <button onclick="document.getElementById('uploadModal').classList.remove('active')" class="text-gray-400 hover:text-gray-600 transition-colors duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                {{-- Zone de drop --}}
                <div class="border-3 border-dashed border-gray-300 rounded-2xl p-12 text-center hover:border-green-500 transition-colors duration-300 cursor-pointer" id="dropZone">
                    <input type="file" id="mediaFile" name="media" accept="image/*,video/*" multiple class="hidden">
                    <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-gray-600 font-medium mb-2">Glissez vos fichiers ou cliquez pour parcourir</p>
                    <p class="text-sm text-gray-500">Images (JPG, PNG, WebP) ou Vidéos (MP4, WebM)</p>
                </div>

                {{-- Titre --}}
                <div>
                    <label for="mediaTitle" class="block text-sm font-bold text-gray-700 mb-2">Titre</label>
                    <input type="text" id="mediaTitle" name="title" placeholder="Ex: Festival Éco-Tech 2025" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:outline-none transition-colors duration-300">
                </div>

                {{-- Description --}}
                <div>
                    <label for="mediaDescription" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea id="mediaDescription" name="description" rows="3" placeholder="Quelques mots sur ce média..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:outline-none resize-none transition-colors duration-300"></textarea>
                </div>

                {{-- Catégorie --}}
                <div>
                    <label for="mediaCategory" class="block text-sm font-bold text-gray-700 mb-2">Catégorie</label>
                    <select id="mediaCategory" name="category" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:outline-none transition-colors duration-300">
                        <option value="events">🎉 Événements</option>
                        <option value="speakers">🎤 Intervenants</option>
                        <option value="venue">🏛️ Lieux</option>
                        <option value="activities">🎨 Activités</option>
                        <option value="team">👥 Équipe</option>
                    </select>
                </div>

                {{-- Boutons --}}
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-green-500 to-blue-500 text-white font-bold rounded-xl hover:from-green-600 hover:to-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Télécharger
                    </button>
                    <button type="button" onclick="document.getElementById('uploadModal').classList.remove('active')" class="px-6 py-4 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition-colors duration-300">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Système de filtrage
    const filterBtns = document.querySelectorAll('.filter-btn');
    const mediaItems = document.querySelectorAll('.media-item');
    const noResults = document.getElementById('noResults');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Mettre à jour les boutons actifs
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            let visibleCount = 0;

            // Filtrer les médias
            mediaItems.forEach(item => {
                if (filter === 'all' || item.dataset.type === filter) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Afficher le message "aucun résultat" si nécessaire
            noResults.classList.toggle('hidden', visibleCount > 0);
        });
    });

    // Lightbox
    function openLightbox(index, isVideo) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxVideo = document.getElementById('lightboxVideo');

        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';

        if (isVideo) {
            lightboxVideo.src = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';
            lightboxVideo.classList.remove('hidden');
            lightboxImage.classList.add('hidden');
        } else {
            lightboxImage.src = `https://picsum.photos/seed/media${index}/1200/800`;
            lightboxImage.classList.remove('hidden');
            lightboxVideo.classList.add('hidden');
        }
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        const lightboxVideo = document.getElementById('lightboxVideo');
        
        lightbox.classList.remove('active');
        document.body.style.overflow = 'auto';
        
        if (lightboxVideo.src) {
            lightboxVideo.pause();
            lightboxVideo.src = '';
        }
    }

    // Fermeture avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
            document.getElementById('uploadModal').classList.remove('active');
        }
    });

    // Hover effect sur les vidéos
    document.querySelectorAll('.media-item video').forEach(video => {
        video.addEventListener('mouseenter', function() {
            this.play();
        });
        video.addEventListener('mouseleave', function() {
            this.pause();
            this.currentTime = 0;
        });
    });

    // Upload modal - Drag & Drop
    const dropZone = document.getElementById('dropZone');
    const mediaFileInput = document.getElementById('mediaFile');

    dropZone.addEventListener('click', () => mediaFileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-green-500', 'bg-green-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-green-500', 'bg-green-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-green-500', 'bg-green-50');
        
        if (e.dataTransfer.files.length) {
            mediaFileInput.files = e.dataTransfer.files;
            console.log('Fichiers déposés:', e.dataTransfer.files);
        }
    });

    mediaFileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            console.log('Fichiers sélectionnés:', this.files);
            dropZone.innerHTML = `
                <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p class="text-green-600 font-bold">${this.files.length} fichier(s) sélectionné(s)</p>
            `;
        }
    });

    // Animation au scroll (lazy loading)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '50px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.animation = 'fadeIn 0.6s ease forwards';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Ajouter l'animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);

    mediaItems.forEach(item => observer.observe(item));
</script>
@endpush
@endsection