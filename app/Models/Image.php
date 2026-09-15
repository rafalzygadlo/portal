<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{

    public const VARIANT_SMALL = 'small';
    public const VARIANT_THUMB = 'thumb';

    protected $table = 'images';
    protected $fillable = ['path', 'imageable_id', 'imageable_type'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getPath(): string
    {
        return $this->path;
    }
    public function getThumbPath(): string
    {
        return dirname($this->path) . '/' . self::VARIANT_THUMB . '/' . basename($this->path);
    }

    public function getSmallPath(): string
    {
        return dirname($this->path) . '/' . self::VARIANT_SMALL . '/' . basename($this->path);
    }
}