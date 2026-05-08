<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'mime_type',
        'size',
        'path',
        'alt_text',
    ];

    public function getUrlAttribute()
    {
        return \Illuminate\Support\Facades\Storage::disk('nawat_uploads')->url($this->path);
    }
}
