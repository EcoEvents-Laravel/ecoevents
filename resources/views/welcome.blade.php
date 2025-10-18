@extends(auth()->user()->role === 'admin' ? 'layouts.app' : 'layouts.front')
@section('title', auth()->user()->role === 'admin' ? 'EcoEvents - Accueil' :  'EcoEvents - Accueil')
@section('content')

@if(auth()->user()->role === 'admin')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    @php
                        $userCount = class_exists(\App\Models\User::class) ? \App\Models\User::count() : 'N/A';
                        $eventCount = class_exists(\App\Models\Event::class) ? \App\Models\Event::count() : 'N/A';
                        $registrationCount = class_exists(\App\Models\Registration::class) ? \App\Models\Registration::count() : 'N/A';
                        $categoryCount = class_exists(\App\Models\Category::class) ? \App\Models\Category::count() : 'N/A';
                    @endphp

                    <div class="mt-4">
                        <h5 class="mb-3">Statistiques</h5>
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title">Utilisateurs</h6>
                                        <p class="display-4 mb-0">{{ $userCount }}</p>
                                        <small class="text-muted">Total enregistrés</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title">Événements</h6>
                                        <p class="display-4 mb-0">{{ $eventCount }}</p>
                                        <small class="text-muted">Événements publiés</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title">Inscriptions</h6>
                                        <p class="display-4 mb-0">{{ $registrationCount }}</p>
                                        <small class="text-muted">Participations</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title">Catégories</h6>
                                        <p class="display-4 mb-0">{{ $categoryCount }}</p>
                                        <small class="text-muted">Thèmes d'événements</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Répartition</h6>
                                            <canvas id="adminDoughnut"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Comparaison</h6>
                                            <canvas id="adminBar"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const rawCounts = {
                                    users: {{ json_encode($userCount) }},
                                    events: {{ json_encode($eventCount) }},
                                    registrations: {{ json_encode($registrationCount) }},
                                    categories: {{ json_encode($categoryCount) }}
                                };

                                const counts = Object.values(rawCounts).map(v => (typeof v === 'string' && v === 'N/A') ? 0 : Number(v));

                                const labels = ['Utilisateurs', 'Événements', 'Inscriptions', 'Catégories'];
                                const colors = ['#38a169', '#3182ce', '#dd6b20', '#805ad5'];

                                // Doughnut chart
                                const ctxD = document.getElementById('adminDoughnut').getContext('2d');
                                new Chart(ctxD, {
                                    type: 'doughnut',
                                    data: {
                                        labels,
                                        datasets: [{
                                            data: counts,
                                            backgroundColor: colors,
                                            hoverOffset: 6
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: { legend: { position: 'bottom' } }
                                    }
                                });

                                // Bar chart
                                const ctxB = document.getElementById('adminBar').getContext('2d');
                                new Chart(ctxB, {
                                    type: 'bar',
                                    data: {
                                        labels,
                                        datasets: [{
                                            label: 'Totaux',
                                            data: counts,
                                            backgroundColor: colors,
                                            borderRadius: 6
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: { y: { beginAtZero: true, precision: 0 } },
                                        plugins: { legend: { display: false } }
                                    }
                                });
                            });
                            </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-green-600 mb-6">Bienvenue sur EcoEvents</h1>
    <p class="text-lg text-gray-700 mb-4">
        Découvrez et participez à des événements éco-responsables près de chez vous. Rejoignez notre communauté engagée pour un avenir plus vert !
    </p>
    <a href="{{ route('events.index') }}" class="inline-block px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-all duration-300 shadow-md hover:shadow-lg">
        Explorer les Événements
    </a>
</div>
@endsection
@endif
