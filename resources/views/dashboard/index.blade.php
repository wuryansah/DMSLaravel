@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $isAdmin ?? false ? 'Total Documents' : 'My Documents' }}</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_documents'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                <i class="fas fa-share-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Shared</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['shared_count'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                <i class="fas fa-download text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Shared with Me</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['shared_with_me'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                <i class="fas fa-folder text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Categories</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['categories'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-colors">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $isAdmin ?? false ? 'All Recent Documents' : 'My Recent Documents' }}</h2>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($stats['recent_documents'] as $document)
            <div class="px-6 py-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                        <i class="fas fa-file text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <a href="{{ route('documents.show', $document) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 truncate">
                        {{ $document->title }}
                    </a>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $document->category->name ?? 'Uncategorized' }}@if($isAdmin ?? false) • {{ $document->user->name }}@endif
                    </p>
                </div>
                <div class="ml-4 text-sm text-gray-500 dark:text-gray-400">
                    {{ $document->created_at->diffForHumans() }}
                </div>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                No documents found. @can('create', App\Models\Document::class)<a href="{{ route('documents.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">Upload your first document</a>@endcan
            </div>
        @endforelse
    </div>
</div>
@endsection
