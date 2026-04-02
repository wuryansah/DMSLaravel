@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="mb-6">
    <a href="{{ route('documents.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
        <i class="fas fa-arrow-left mr-2"></i>Back to Documents
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-colors">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $document->title }}</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Uploaded by {{ $document->user->name }} on {{ $document->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('documents.download', $document) }}" 
                           class="bg-green-600 dark:bg-green-700 text-white px-4 py-2 rounded-md hover:bg-green-700 dark:hover:bg-green-600 transition-colors">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                        @can('update', $document)
                        <a href="{{ route('documents.edit', $document) }}" 
                           class="bg-indigo-600 dark:bg-indigo-700 text-white px-4 py-2 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        @endcan
                        @can('delete', $document)
                        <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this document?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 dark:bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-700 dark:hover:bg-red-600 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4">
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $document->description ?: 'No description provided.' }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Category</h3>
                        <span class="px-2 py-1 text-sm rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-300">
                            {{ $document->category->name ?? 'N/A' }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Current Version</h3>
                        <span class="px-2 py-1 text-sm rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                            v{{ $document->current_version }}
                        </span>
                    </div>
                </div>
                
                @if($document->tags && count($document->tags) > 0)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($document->tags as $tag)
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($canShare ?? false)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mt-6 transition-colors">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Share Document</h2>
            </div>
            <div class="px-6 py-4">
                <form method="POST" action="{{ route('documents.share', $document) }}" class="flex gap-4">
                    @csrf
                    <select name="user_id" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">Select a user to share with...</option>
                        @foreach($users as $user)
                            @if($user->id !== auth()->id())
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 dark:bg-indigo-700 text-white px-4 py-2 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                        <i class="fas fa-share mr-2"></i>Share
                    </button>
                </form>
                
                @if($document->sharedWithUsers->count() > 0)
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Shared with:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($document->sharedWithUsers as $sharedUser)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-sm">
                                {{ $sharedUser->name }}
                                <form method="POST" action="{{ route('documents.unshare', [$document, $sharedUser->id]) }}" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-colors">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Version History</h2>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                @forelse($document->versions as $version)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">Version {{ $version->version_number }}</span>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $version->notes ?: 'No notes' }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ $version->user->name }} • {{ $version->created_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                            <a href="{{ route('documents.versions.download', [$document, $version]) }}" 
                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No version history available.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
