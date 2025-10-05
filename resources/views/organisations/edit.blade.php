@extends('layouts.app')

@section('title', 'Modifier ' . $organisation->name)

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="text-center mb-4">
        <a href="{{ route('organisations.show', $organisation) }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 mb-3">
            <i class="fas fa-arrow-left"></i>
            Retour à l'Organisation
        </a>
        <h1 class="h2 text-primary fw-bold mb-2">Modifier l'Organisation</h1>
        <p class="text-muted">Mettez à jour les informations de votre organisation</p>
    </div>

    <!-- Current Logo Preview -->
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center bg-light rounded p-4 border-dashed border-2">
            @if($organisation->logo_url)
                <img src="{{ $organisation->logo_url }}" alt="{{ $organisation->name }}"
                     class="rounded-circle shadow-sm me-3" style="height:80px;width:80px;object-fit:cover;">
            @else
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm me-3"
                     style="height:80px;width:80px;">
                    <i class="fas fa-building fa-2xl"></i>
                </div>
            @endif
            <div class="text-start">
                <h4 class="mb-1">{{ $organisation->name }}</h4>
                <p class="text-muted mb-0">Logo actuel affiché</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow-lg mx-auto" style="max-width: 800px;">
        <div class="card-header bg-warning text-dark">
            <h3 class="card-title mb-0">
                <i class="fas fa-edit me-2"></i>
                Modifier les Informations
            </h3>
        </div>

        <form action="{{ route('organisations.update', $organisation) }}" method="POST" class="card-body">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            <i class="fas fa-building me-2 text-primary"></i>
                            Nom de l'Organisation *
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $organisation->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Ex: Association des Développeurs Tunisiens">
                        @error('name')
                            <div class="invalid-feedback d-flex align-items-center mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_email" class="form-label fw-semibold">
                            <i class="fas fa-envelope me-2 text-success"></i>
                            Email de Contact *
                        </label>
                        <input type="email"
                               id="contact_email"
                               name="contact_email"
                               value="{{ old('contact_email', $organisation->contact_email) }}"
                               class="form-control @error('contact_email') is-invalid @enderror"
                               placeholder="contact@organisation.tn">
                        @error('contact_email')
                            <div class="invalid-feedback d-flex align-items-center mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="website" class="form-label fw-semibold">
                            <i class="fas fa-globe me-2 text-info"></i>
                            Site Web
                        </label>
                        <input type="url"
                               id="website"
                               name="website"
                               value="{{ old('website', $organisation->website) }}"
                               class="form-control @error('website') is-invalid @enderror"
                               placeholder="https://organisation.tn">
                        @error('website')
                            <div class="invalid-feedback d-flex align-items-center mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="logo_url" class="form-label fw-semibold">
                            <i class="fas fa-image me-2 text-warning"></i>
                            URL du Logo
                        </label>
                        <input type="url"
                               id="logo_url"
                               name="logo_url"
                               value="{{ old('logo_url', $organisation->logo_url) }}"
                               class="form-control @error('logo_url') is-invalid @enderror"
                               placeholder="https://organisation.tn/logo.png">
                        @error('logo_url')
                            <div class="invalid-feedback d-flex align-items-center mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
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
                        <label for="description" class="form-label fw-semibold">
                            <i class="fas fa-align-left me-2 text-danger"></i>
                            Description *
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Décrivez l'organisation, ses objectifs, ses activités, sa mission...">{{ old('description', $organisation->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-flex align-items-center mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="alert alert-info border-0 mb-4">
                <h6 class="alert-heading d-flex align-items-center mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations de l'Organisation
                </h6>
                <div class="row g-2 text-sm">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-plus me-2"></i>
                            <span>Créée le: {{ $organisation->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-check me-2"></i>
                            <span>Dernière modification: {{ $organisation->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                <a href="{{ route('organisations.show', $organisation) }}"
                   class="btn btn-secondary d-flex align-items-center gap-2">
                    <i class="fas fa-times"></i>
                    Annuler
                </a>
                <button type="submit" class="btn btn-warning d-flex align-items-center gap-2">
                    <i class="fas fa-save"></i>
                    Mettre à Jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection