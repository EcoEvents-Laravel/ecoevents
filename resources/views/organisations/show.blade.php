@extends('layouts.app')

@section('title', $organisation->name)

@section('content')
<div class="container py-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('organisations.index') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Retour aux Organisations
        </a>
    </div>

    <!-- Main Card -->
    <div class="card shadow-lg">
        <!-- Header Section -->
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            @if($organisation->logo_url)
                                <img src="{{ $organisation->logo_url }}" alt="{{ $organisation->name }}"
                                     class="rounded-circle border border-white shadow-sm" style="height:80px;width:80px;object-fit:cover;">
                            @else
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                     style="height:80px;width:80px;">
                                    <i class="fas fa-building fa-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h1 class="h2 mb-1">{{ $organisation->name }}</h1>
                            <p class="mb-2 h5 text-white-50">
                                <i class="fas fa-envelope me-2"></i>{{ $organisation->contact_email }}
                            </p>
                            <div class="d-flex gap-2">
                                @if($organisation->website)
                                    <a href="{{ $organisation->website }}" target="_blank"
                                       class="btn btn-light btn-sm">
                                        <i class="fas fa-globe me-1"></i>Visiter le Site Web
                                    </a>
                                @endif
                                <span class="badge bg-light text-primary">
                                    <i class="fas fa-calendar me-1"></i>Membre depuis {{ $organisation->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        <a href="{{ route('organisations.edit', $organisation) }}"
                           class="btn btn-warning d-flex align-items-center gap-2">
                            <i class="fas fa-edit"></i>
                            Modifier
                        </a>
                        <form action="{{ route('organisations.destroy', $organisation) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette organisation ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
                                <i class="fas fa-trash"></i>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="card-body">
            <!-- Description -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="h4 text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>Description
                    </h3>
                    <div class="alert alert-light border-start border-primary border-4">
                        <p class="mb-0 text-muted fs-6 lh-base">{{ $organisation->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Info Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-primary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-envelope text-primary fa-2xl mb-3"></i>
                            <h5 class="card-title text-primary">Contact</h5>
                            <p class="card-text text-muted">{{ $organisation->contact_email }}</p>
                        </div>
                    </div>
                </div>

                @if($organisation->website)
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-globe text-success fa-2xl mb-3"></i>
                                <h5 class="card-title text-success">Site Web</h5>
                                <a href="{{ $organisation->website }}" target="_blank" class="btn btn-success btn-sm">
                                    Visiter le site
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-4">
                    <div class="card border-info h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar text-info fa-2xl mb-3"></i>
                            <h5 class="card-title text-info">Membre Depuis</h5>
                            <p class="card-text text-muted">{{ $organisation->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Note about Events -->
            <div class="alert alert-warning border-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle text-warning me-3 fa-lg"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Fonctionnalité à venir</h6>
                        <p class="mb-0">
                            La liaison avec les événements sera bientôt disponible. Vous pourrez alors voir tous les événements organisés par cette organisation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection