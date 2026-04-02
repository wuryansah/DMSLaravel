<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($isAdmin ?? false ? 'Total Documents' : 'My Documents'); ?></p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($stats['total_documents']); ?></p>
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
                <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($stats['shared_count'] ?? 0); ?></p>
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
                <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($stats['shared_with_me'] ?? 0); ?></p>
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
                <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($stats['categories']); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-colors">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($isAdmin ?? false ? 'All Recent Documents' : 'My Recent Documents'); ?></h2>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <?php $__empty_1 = true; $__currentLoopData = $stats['recent_documents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="px-6 py-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                        <i class="fas fa-file text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1 min-w-0">
                    <a href="<?php echo e(route('documents.show', $document)); ?>" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 truncate">
                        <?php echo e($document->title); ?>

                    </a>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?php echo e($document->category->name ?? 'Uncategorized'); ?><?php if($isAdmin ?? false): ?> • <?php echo e($document->user->name); ?><?php endif; ?>
                    </p>
                </div>
                <div class="ml-4 text-sm text-gray-500 dark:text-gray-400">
                    <?php echo e($document->created_at->diffForHumans()); ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                No documents found. <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Document::class)): ?><a href="<?php echo e(route('documents.create')); ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">Upload your first document</a><?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DMSLaravel\resources\views/dashboard/index.blade.php ENDPATH**/ ?>