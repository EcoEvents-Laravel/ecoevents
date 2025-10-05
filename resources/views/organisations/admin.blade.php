@extends('layouts.app')

@section('title', 'Administration - Organisations')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Administration des Organisations</h1>
        <a href="{{ route('organisations.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Nouvelle Organisation
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="mb-4 flex justify-between items-center">
                <p class="text-gray-600">
                    Total: <span class="font-semibold">{{ $organisations->total() }}</span> organisations
                </p>
                <div class="flex space-x-2">
                    <select class="border border-gray-300 rounded px-3 py-1 text-sm">
                        <option>Trier par nom</option>
                        <option>Trier par date</option>
                        <option>Trier par email</option>
                    </select>
                </div>
            </div>

            @if($organisations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Organisation
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Site Web
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Créée le
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($organisations as $organisation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($organisation->logo_url)
                                                <img class="h-10 w-10 rounded-full object-cover mr-3"
                                                     src="{{ $organisation->logo_url }}"
                                                     alt="{{ $organisation->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 mr-3 flex items-center justify-center">
                                                    <i class="fas fa-building text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $organisation->name }}</div>
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($organisation->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $organisation->contact_email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($organisation->website)
                                            <a href="{{ $organisation->website }}"
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                <i class="fas fa-external-link-alt mr-1"></i>Visiter
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm">Non spécifié</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $organisation->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('organisations.show', $organisation) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('organisations.edit', $organisation) }}"
                                               class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('organisations.destroy', $organisation) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette organisation ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $organisations->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-building text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune organisation trouvée</h3>
                    <p class="text-gray-600 mb-6">Créez la première organisation pour commencer.</p>
                    <a href="{{ route('organisations.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                        Créer la première organisation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection