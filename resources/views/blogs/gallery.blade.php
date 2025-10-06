@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Masonry grid using Bootstrap */
    .masonry-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    .masonry-item {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        transition: box-shadow 0.3s, transform 0.3s;
        cursor: pointer;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        flex: 1 1 300px;
        max-width: 32%;
        margin-bottom: 1.5rem;
        min-width: 280px;
    }
    .masonry-item:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
        z-index: 10;
    }
    .masonry-item img,
    .masonry-item video {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.4s;
        border-radius: 1rem 1rem 0 0;
        display: block;
    }
    .masonry-item:hover img,
    .masonry-item:hover video {
        transform: scale(1.07);
    }
    .media-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.6);
        color: #fff;
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.3s;
        border-radius: 0 0 1rem 1rem;
    }
    .masonry-item:hover .media-overlay {
        opacity: 1;
    }
    /* Lightbox modal */
    .lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.95);
        z-index: 1055;
        align-items: center;
        justify-content: center;
    }
    .lightbox.active {
        display: flex;
    }
    .lightbox-content {
        max-width: 90vw;
        max-height: 90vh;
        background: #fff;
        border-radius: 1rem;
        padding: 1rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    /* Filter buttons */
    .filter-btn {
        transition: all 0.2s;
        border-radius: 2rem;
        font-weight: 500;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #333;
        padding: 0.5rem 1.5rem;
        margin: 0.25rem;
    }
    .filter-btn.active,
    .filter-btn:hover {
        background: linear-gradient(90deg, #198754 60%, #0d6efd 100%);
        color: #fff;
        border-color: #198754;
        box-shadow: 0 2px 8px rgba(13,110,253,0.12);
    }
    /* Upload button floating */
    .upload-fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #198754, #0d6efd);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(13,110,253,0.18);
        cursor: pointer;
        transition: all 0.3s;
        z-index: 1060;
    }
    .upload-fab:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 12px 32px rgba(13,110,253,0.25);
    }
</style>
@endpush
