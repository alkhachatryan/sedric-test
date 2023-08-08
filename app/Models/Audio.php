<?php

namespace App\Models;

use App\Enums\Status;
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
        'status',
    ];

    protected $casts = [
        'sentences' => 'json',
        'status' => Status::class
    ];

    public function setStatus(Status $status): void
    {
        $this->status = $status;
        $this->save();
    }
}
