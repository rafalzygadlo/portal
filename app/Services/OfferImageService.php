<?php

namespace App\Services;

use App\Models\Offer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class OfferImageService
{
    public function processAndAttach(Offer $offer, array $photos): void
    {
        $manager = new ImageManager(new Driver());
        $offerId = $offer->id;
        
        foreach ($photos as $index => $photo) 
        {
            
            //filename construction
            $titleSlug = Str::slug($offer->title); // Zamieni "Dom w Bolesławcu" na "dom-w-boleslawcu"
            $timestamp = now()->format('YmdHis');
            $filename = "{$titleSlug}-{$timestamp}-{$index}.jpg";

            //directory construction
            $directory = "offers/{$offerId}";

            $imageDirectory = "{$directory}";
            $smallDirectory = "{$directory}/small";
            $thumbnailsDirectory = "{$directory}/thumbnails";

            // Decode the image from the temporary path
            $image = $manager->decodePath($photo->getRealPath());

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
}