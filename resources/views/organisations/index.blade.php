@extends(auth()->user()->role === 'admin' ? 'layouts.app' : 'layouts.front')

@section('title', 'Organisations')

@section('content')
@if(auth()->user()->role === 'admin')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-primary fw-bold">EcoEvents Organisations</h1>
        <button onclick="openModal('createOrganisationModal')" class="btn btn-primary d-flex align-items-center gap-2">
            <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"/>
            </svg>
            Créer une Organisation
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Site Web</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($organisations as $organisation)
                        <tr>
                            <td class="fw-semibold">{{ $organisation->id }}</td>
                            <td>
                                @if($organisation->logo_url)
                                    <img src="{{ $organisation->logo_url }}" alt="{{ $organisation->name }}"
                                         class="rounded-circle border border-primary shadow-sm" style="height:48px;width:48px;object-fit:cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="height:48px;width:48px;">
                                        <i class="fas fa-building fa-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong class="text-primary">{{ $organisation->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $organisation->contact_email }}</span>
                            </td>
                            <td>
                                @if($organisation->website)
                                    <a href="{{ $organisation->website }}" target="_blank" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-external-link-alt me-1"></i>Visiter
                                    </a>
                                @else
                                    <span class="text-muted">Non spécifié</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted" title="{{ $organisation->description }}">
                                    {{ Str::limit($organisation->description, 50) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('organisations.show', $organisation) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>
                                    <button type="button" onclick="populateEditForm({{ $organisation->id }})"
                                            class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </button>
                                    <button type="button" onclick="populateDeleteForm({{ $organisation->id }})"
                                            class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $organisations->links() }}
    </div>
</div>

<!-- Create Organisation Modal -->
<div id="createOrganisationModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('organisations.store') }}">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus-circle me-2"></i>Créer une Organisation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" onclick="closeModal('createOrganisationModal')"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nom de l'Organisation *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email de Contact *</label>
                                <input type="email" name="contact_email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Site Web</label>
                                <input type="url" name="website" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">URL du Logo</label>
                                <input type="url" name="logo_url" class="form-control" placeholder="https://exemple.com/logo.png">
                                <div class="form-text">
                                    <small class="text-muted">
                                        <strong>Exemples d'URLs d'images:</strong><br>
                                        • https://via.placeholder.com/200x200<br>
                                        • https://picsum.photos/200/200<br>
                                        • https://logos-world.net/wp-content/uploads/2020/12/Adidas-Logo.png<br>
                                        <em>Laissez vide pour utiliser une icône par défaut</em>
                                    </small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description *</label>
                                <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createOrganisationModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer l'Organisation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Bootstrap modal helpers
    function openModal(id) {
        var modal = new bootstrap.Modal(document.getElementById(id));
        modal.show();
        window['_' + id] = modal;
    }
    function closeModal(id) {
        if (window['_' + id]) {
            window['_' + id].hide();
        }
    }

    // Data for JS
    const organisations = @json($organisations);

    // Edit form population
    window.populateEditForm = function(id) {
        const organisation = organisations.find(o => o.id === id);
        if (!organisation) {
            alert('Organisation non trouvée!');
            return;
        }

        // For edit, redirect to edit page
        window.location.href = '/organisations/' + organisation.id + '/edit';
    };

    // Delete form population
    window.populateDeleteForm = function(id) {
        const organisation = organisations.find(o => o.id === id);
        if (!organisation) {
            alert('Organisation non trouvée!');
            return;
        }

        if (confirm('Êtes-vous sûr de vouloir supprimer l\'organisation "' + organisation.name + '" ?\n\nCette action est irréversible!')) {
            // Create a form and submit it for deletion
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/organisations/' + id;
            form.style.display = 'none';

            // Get CSRF token from Laravel (it should be available globally)
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);

            // Show loading state
            const originalText = event.target.innerHTML;
            event.target.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Suppression...';
            event.target.disabled = true;

            form.submit();
        }
    };
</script>
@else
<div id="app" class="min-h-screen">

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Organizations Directory</h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">Discover and connect with amazing organizations</p>
            <div class="max-w-md mx-auto">
                <div class="relative">
                    <input type="text"
                           id="search-input"
                           placeholder="Search organizations..."
                           class="w-full px-4 py-3 pl-12 rounded-full border-0 shadow-lg focus:ring-4 focus:ring-blue-200 outline-none">
                    <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        <!-- Organizations Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($organisations as $organisation)
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center">
                @if($organisation->logo_url)
                <img src="{{ $organisation->logo_url }}" alt="{{ $organisation->name }}" class="h-24 w-24 rounded-full object-cover mb-4 border-2 border-blue-500 shadow-sm">
                @else
                <div class="bg-blue-500 text-white rounded-full h-24 w-24 flex items-center justify-center mb-4 shadow-sm">
                    <i class="fas fa-building fa-2x"></i>
                </div>
                @endif
                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $organisation->name }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($organisation->description, 100) }}</p>
                @if($organisation->website)
                <a href="{{ $organisation->website }}" target="_blank" class="mt-auto inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-md hover:shadow-lg">
                    Visit Website
                </a>
                @endif
        </div>
            @endforeach

        <!-- Empty State -->
        <div class="text-center py-16" style="display: none;">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No organizations found</h3>
            <p class="text-gray-500">Check back later for new organizations.</p>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="mt-12 flex justify-center" style="display: none;">
            <!-- Pagination will be populated by JavaScript -->
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500">
                <p>&copy; 2024 Organization Directory. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
@endif
@endsection