<?php $__env->startSection('title', $document->title); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('documents.index')); ?>" class="text-indigo-600 hover:text-indigo-500">
        <i class="fas fa-arrow-left mr-2"></i>Back to Documents
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo e($document->title); ?></h1>
                        <p class="text-sm text-gray-500 mt-1">
                            Uploaded by <?php echo e($document->user->name); ?> on <?php echo e($document->created_at->format('M d, Y')); ?>

                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('documents.download', $document)); ?>" 
                           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $document)): ?>
                        <a href="<?php echo e(route('documents.edit', $document)); ?>" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $document)): ?>
                        <form method="POST" action="<?php echo e(route('documents.destroy', $document)); ?>" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this document?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4">
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                    <p class="text-gray-700"><?php echo e($document->description ?: 'No description provided.'); ?></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Category</h3>
                        <span class="px-2 py-1 text-sm rounded-full bg-indigo-100 text-indigo-800">
                            <?php echo e($document->category->name ?? 'N/A'); ?>

                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Current Version</h3>
                        <span class="px-2 py-1 text-sm rounded-full bg-green-100 text-green-800">
                            v<?php echo e($document->current_version); ?>

                        </span>
                    </div>
                </div>
                
                <?php if($document->tags && count($document->tags) > 0): ?>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $document->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700"><?php echo e($tag); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($canShare ?? false): ?>
        <div class="bg-white rounded-lg shadow mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Share Document</h2>
            </div>
            <div class="px-6 py-4">
                <form method="POST" action="<?php echo e(route('documents.share', $document)); ?>" class="flex gap-4">
                    <?php echo csrf_field(); ?>
                    <select name="user_id" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <option value="">Select a user to share with...</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($user->id !== auth()->id()): ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->role); ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        <i class="fas fa-share mr-2"></i>Share
                    </button>
                </form>
                
                <?php if($document->sharedWithUsers->count() > 0): ?>
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Shared with:</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $document->sharedWithUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sharedUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm">
                                <?php echo e($sharedUser->name); ?>

                                <form method="POST" action="<?php echo e(route('documents.unshare', [$document, $sharedUser->id])); ?>" class="ml-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Version History</h2>
            </div>
            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $document->versions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $version): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-medium text-gray-900">Version <?php echo e($version->version_number); ?></span>
                                <p class="text-sm text-gray-500"><?php echo e($version->notes ?: 'No notes'); ?></p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <?php echo e($version->user->name); ?> • <?php echo e($version->created_at->format('M d, Y H:i')); ?>

                                </p>
                            </div>
                            <a href="<?php echo e(route('documents.versions.download', [$document, $version])); ?>" 
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-6 py-4 text-center text-gray-500">
                        No version history available.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\DMSLaravel\resources\views/documents/show.blade.php ENDPATH**/ ?>