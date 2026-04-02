<?php

return [
    'disk' => env('FILESYSTEM_DISK', 'local'),
    'default' => env('FILESYSTEM_DISK', 'local'),
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
