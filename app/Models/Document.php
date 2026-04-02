<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'user_id',
        'category_id',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function versions()
    {
        return $this->hasMany(Version::class)->orderBy('version_number', 'desc');
    }

    public function sharedFolders()
    {
        return $this->hasMany(SharedFolder::class);
    }

    public function sharedWithUsers()
    {
        return $this->belongsToMany(User::class, 'shared_folders', 'document_id', 'shared_with_id')
            ->withPivot('owner_id');
    }

    public function latestVersion()
    {
        return $this->hasOne(Version::class)->latestOfMany('version_number');
    }

    public function getCurrentVersionAttribute()
    {
        return $this->versions()->max('version_number') ?? 1;
    }
}
