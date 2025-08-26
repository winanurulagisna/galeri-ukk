<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';
    
    protected $fillable = [
        'title',
        'caption',
        'file_path',
        'post_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Temporarily disable timestamps until database columns are added
    public $timestamps = false;

    // Relationship with Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
