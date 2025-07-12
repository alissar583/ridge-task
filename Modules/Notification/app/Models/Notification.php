<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Notification\Database\Factories\NotificationFactory;

// use Modules\Notification\Database\Factories\NotificationFactory;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'n_notifications';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'body'
    ];

    protected static function newFactory(): NotificationFactory
    {
        return NotificationFactory::new();
    }
}
