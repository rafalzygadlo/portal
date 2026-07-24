<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Image;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Illuminate\Support\Facades\Storage;

class OfferImageService
{
    public function processAndAttach(Offer $offer, array $photos): void
    {
        $manager = new ImageManager(new Driver());
        $offerId = $offer->id;
        
        foreach ($photos as $index => $photo) 
        {
            
            //filename construction
            $titleSlug = Str::slug($offer->title); 
            
            $filename = "{$titleSlug}-{$index}.jpg";

            //directory construction
            $directory = "offers/{$offerId}";

            $imageDirectory = "{$directory}";
            $smallDirectory = "{$directory}/small";
            $thumbnailsDirectory = "{$directory}/thumbnails";

            // Decode the image from the temporary path
            $image = $manager->decodePath($photo['realPath']);

            // 1200px is a common width for web images to balance quality and performance
            $image->scaleDown(width: 1200);
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);
            Storage::disk('public')->put($imageDirectory . '/' . $filename, $encoded);
            $offer->images()->create(['path' => $imageDirectory . '/'. $filename]);

            
            // 800px is a common width for web images to balance quality and performance
            $image->scaleDown(width: 800);
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);
            Storage::disk('public')->put($smallDirectory . '/' . $filename, $encoded);
            

            //400px is a common width for web images to balance quality and performance
            $image->scaleDown(width: 400);  
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);
            Storage::disk('public')->put($thumbnailsDirectory . '/' . $filename, $encoded);
            
        
        }
    }

    /**
     * Deletes all images and directories associated with a given offer.
     *
     * @param Offer $offer
     * @return void
     */
    public function deleteForOffer(Offer $offer): void
    {
        $directory = "offers/{$offer->id}";
        Storage::disk('public')->deleteDirectory($directory);
    }

    /**
     * Deletes a single image and its variants (small, thumbnail) from storage,
     * and removes its record from the database.
     *
     * @param Image $image
     * @return void
     */
    public function deleteImage(Image $image): void
    {
        $basePath = dirname($image->path); // np. "offers/123"
        $filename = basename($image->path); // np. "tytul-oferty-0.jpg"

        $pathsToDelete = [
            $image->path, // "offers/123/tytul-oferty-0.jpg"
            "{$basePath}/small/{$filename}",
            "{$basePath}/thumbnails/{$filename}",
        ];

        Storage::disk('public')->delete($pathsToDelete);
        $image->delete();
    }
}