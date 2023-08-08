<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;

    protected $table = 'audios';

    protected $fillable = [
        'audio_url',
        'sentences',
        'request_id',
    ];

    protected $casts = [
      'sentences' => 'json'
    ];
}
