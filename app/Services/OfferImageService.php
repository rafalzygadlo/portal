<?php

namespace App\Services;

use App\Models\Offer\Offer;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class OfferImageService
{
    public function processAndAttach(Offer $offer, array $photos): void
    {
        $manager = new ImageManager(new Driver());

        foreach ($photos as $photo) 
        {
            $filename = $photo->hashName();

            // Decode the image from the temporary path
            $image = $manager->decodePath($photo->getRealPath());

            // 1200px is a common width for web images to balance quality and performance
            $image->scaleDown(width: 1200);
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);
            Storage::disk('public')->put('offers/' . $filename, $encoded);
            $offer->images()->create(['path' => 'offers/' . $filename]);

            
            // 800px is a common width for web images to balance quality and performance
            $image->scaleDown(width: 800);
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);
            Storage::disk('public')->put('offers/small/' . $filename, $encoded);
            $offer->images()->create(['path' => 'offers/small/' . $filename]);

            //400px is a common width for web images to balance quality and performance
            $image->scaleDown(width: 400);  
            $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 80);
            Storage::disk('public')->put('offers/thumbnails/' . $filename, $encoded);
            $offer->images()->create(['path' => 'offers/thumbnails/' . $filename]);
        
        }
    }
}