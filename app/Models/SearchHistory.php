<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchHistory extends Model
{
    protected $fillable = [
        'user_id',
        'genre_id',
        'year',
        'rating',
        'searched_at',
    ];

    protected $casts = [
        'searched_at' => 'datetime',
    ];

    public $timestamps = false;

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
}