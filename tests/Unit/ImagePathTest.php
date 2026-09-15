<?php

namespace Tests\Unit;

use App\Models\Image;
use PHPUnit\Framework\TestCase;

class ImagePathTest extends TestCase
{
    public function test_it_returns_correct_thumbnail_path(): void
    {
        $image = new Image([
            'path' => 'articles/101/zdjecie.jpg',
        ]);

        $this->assertEquals(
            'articles/101/thumbnails/zdjecie.jpg',
            $image->getThumbPath()
        );
    }

    public function test_it_returns_correct_small_path(): void
    {
        $image = new Image([
            'path' => 'offers/123/zdjecie.jpg',
        ]);

        $this->assertEquals(
            'offers/123/small/zdjecie.jpg',
            $image->getSmallPath()
        );
    }
}