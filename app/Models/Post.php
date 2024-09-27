<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory, Sluggable;
    protected $guarded = [];
    protected $appends = ['post_avatar'];
    public function sluggable(): array
    {
        return [
            'post_name' => [
                'source' => 'post_title'
            ]
        ];
    }
    protected function postAuthor(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \App\Models\User::find($value)?->name,
        );
    }
    protected function postAvatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \App\Models\User::find($value)?->photo,
        );
    }
    public function categories()
    {
        return $this->hasManyThrough(
            Category::class,
            PostCategory::class,
            'post_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'category_id' // Local key on the environments table...
        );
    }
    public function tags()
    {
        return $this->hasManyThrough(
            Tag::class,
            PostTag::class,
            'post_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'tag_id' // Local key on the environments table...
        );
    }
}
