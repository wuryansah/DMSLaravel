<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function sharedDocuments()
    {
        return $this->belongsToMany(Document::class, 'shared_folders', 'shared_with_id', 'document_id')
            ->withPivot('owner_id')
            ->withTimestamps();
    }

    public function sharedWithUsers()
    {
        return $this->hasMany(SharedFolder::class, 'owner_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isViewer()
    {
        return $this->role === 'viewer';
    }
}
