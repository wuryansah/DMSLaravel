@extends('layouts.app')

@section('title', 'Documents')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $isAdmin ?? false ? 'All Documents' : 'My Documents' }}</h1>
    @can('create', App\Models\Document::class)
    <a href="{{ route('documents.create') }}" class="bg-indigo-600 dark:bg-indigo-700 text-white px-4 py-2 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
        <i class="fas fa-upload mr-2"></i>Upload Document
    </a>
    @endcan
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4 transition-colors">
    <form method="GET" action="{{ route('documents.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
            <input type="text" name="search" placeholder="Search documents..." 
                   value="{{ request('search') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="date" name="date_from" placeholder="From date" 
                   value="{{ request('date_from') }}"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-indigo-600 dark:bg-indigo-700 text-white px-4 py-2 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                Filter
            </button>
            <a href="{{ route('documents.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 dark:bg-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Clear
            </a>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                @if($isAdmin ?? false)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Owner</th>
                @endif
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Shared</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Version</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($documents as $document)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4">
                        <a href="{{ route('documents.show', $document) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">
                            {{ $document->title }}
                        </a>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $document->description }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                            {{ $document->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    @if($isAdmin ?? false)
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $document->user->name }}
                    </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($document->sharedFolders->count() > 0)
                            <span class="text-blue-500 dark:text-blue-400" title="Shared with {{ $document->sharedFolders->count() }} user(s)">
                                <i class="fas fa-share-alt"></i> {{ $document->sharedFolders->count() }}
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">
                                <i class="fas fa-lock"></i>
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        v{{ $document->current_version }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $document->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('documents.show', $document) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('documents.download', $document) }}" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">
                            <i class="fas fa-download"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $isAdmin ?? false ? '7' : '6' }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        No documents found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $documents->links() }}
</div>
@endsection
