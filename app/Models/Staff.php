<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'stafdanguru';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false; // Disable timestamps karena tabel tidak punya created_at dan updated_at

    protected $fillable = [
        'name',
        'position',
        'bio',
        'photo',
        'user_id'
    ];

    protected $hidden = [
        // Tidak ada field yang perlu disembunyikan
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
