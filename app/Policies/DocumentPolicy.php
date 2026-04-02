<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $document->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->role === 'staff' && $document->user_id === $user->id;
    }

    public function delete(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->role === 'staff' && $document->user_id === $user->id;
    }

    public function download(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $document->user_id === $user->id;
    }
}
