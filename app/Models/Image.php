<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = ['path', 'imageable_id', 'imageable_type'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getThumbnailPath()
    {
        $filename = basename($this->path); // wyciąga samą nazwę pliku, np. 'zdjecie.jpg'
        return 'offers/' . $this->imageable_id . '/thumbnails/' . $filename;
    }
    public function getSmallPath()
    {
        return 'small/' . $this->path;

    }
}