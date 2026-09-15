<?php

namespace App\Services;

use App\Models\Image;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class ImageService
{
    

public function processAndAttach(Model $model, array $photos): void
{
    $manager = new ImageManager(new Driver());

    $modelType = Str::plural(Str::lower(class_basename($model)));
    $modelId = $model->id;

    $titleSlug = Str::slug($model->title ?? $model->name ?? 'image');

    foreach ($photos as $index => $photo) {

        $filename = "{$titleSlug}-{$index}.jpg";

        $directory = "{$modelType}/{$modelId}";

        $smallDirectory = "{$directory}/" . Image::VARIANT_SMALL;
        $thumbnailsDirectory = "{$directory}/" . Image::VARIANT_THUMB;

        $image = $manager->decodePath($photo['realPath']);

        $image->scaleDown(width: 1200);
        $encoded = $image->encodeUsingFormat(
            Format::JPEG,
            quality: 80
        );

        Storage::disk('public')->put(
            "{$directory}/{$filename}",
            $encoded
        );

        $model->images()->create([
            'path' => "{$directory}/{$filename}",
        ]);

        $image->scaleDown(width: 800);
        $encoded = $image->encodeUsingFormat(
            Format::JPEG,
            quality: 80
        );

        Storage::disk('public')->put(
            "{$smallDirectory}/{$filename}",
            $encoded
        );

        $image->scaleDown(width: 400);
        $encoded = $image->encodeUsingFormat(
            Format::JPEG,
            quality: 80
        );

        Storage::disk('public')->put(
            "{$thumbnailsDirectory}/{$filename}",
            $encoded
        );
    }
}

    public function deleteFor(Model $model): void
    {
        Storage::disk('public')->deleteDirectory(
            $model->imageDirectory()
        );
        $model->images()->delete();
    }
    public function deleteImage(Image $image): void
    {
        $pathsToDelete = [];
        $pathsToDelete[] = $image->getThumbPath();
        $pathsToDelete[] = $image->getSmallPath();
        $pathsToDelete[] = $image->path;
        
        Storage::disk('public')->delete($pathsToDelete);
        $image->delete();
    }
}