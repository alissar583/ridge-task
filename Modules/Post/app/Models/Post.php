<?php

namespace Modules\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Post\Database\Factories\PostFactory;

// use Modules\Post\Database\Factories\PostFactory;

class Post extends Model
{
    use HasFactory;
    protected $table = 'p_posts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}
