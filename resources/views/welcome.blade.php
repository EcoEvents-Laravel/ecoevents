@extends(auth()->user() && auth()->user()->is_admin ? 'layouts.app' : 'layouts.front')
@section('title', auth()->user() && auth()->user()->is_admin ? 'EcoEvents - Accueil' :  'EcoEvents - Accueil')
@section('content')

@if(auth()->user() && auth()->user()->is_admin)
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