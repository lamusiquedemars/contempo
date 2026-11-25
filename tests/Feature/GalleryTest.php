<?php

namespace Tests\Feature;

use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryImage;
use App\Modules\Media\Enums\MediaType;
use App\Modules\Media\Models\MediaAsset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_slug_is_generated_from_title(): void
    {
        $gallery = Gallery::query()->create([
            'title' => 'Galerie Atelier',
        ]);

        $this->assertSame('galerie-atelier', $gallery->slug);
    }

    public function test_system_gallery_can_be_identified(): void
    {
        $gallery = Gallery::query()->updateOrCreate(['slug' => 'home'], [
            'title' => 'Accueil',
        ]);

        $this->assertTrue($gallery->isSystemGallery());
    }

    public function test_gallery_images_can_reference_the_central_media_library(): void
    {
        $gallery = Gallery::query()->create(['title' => 'Atelier']);
        $media = MediaAsset::query()->create([
            'type' => MediaType::Image,
            'disk' => 'public',
            'path' => 'media/images/atelier.webp',
            'original_name' => 'atelier.webp',
            'display_name' => 'Atelier',
            'mime_type' => 'image/webp',
            'extension' => 'webp',
            'size' => 1024,
            'checksum' => hash('sha256', 'atelier.webp'),
            'width' => 1440,
            'height' => 960,
        ]);

        $image = GalleryImage::query()->create([
            'gallery_id' => $gallery->id,
            'media_asset_id' => $media->id,
            'title' => 'Établi',
            'position' => 1,
            'is_published' => true,
        ]);

        $image->refresh();

        $this->assertSame($media->url(), $image->resolved_image_url);
        $this->assertSame(1440, $image->width);
        $this->assertSame(960, $image->height);
        $this->assertDatabaseHas('media_usages', [
            'media_asset_id' => $media->id,
            'usable_type' => GalleryImage::class,
            'usable_id' => $image->id,
            'field' => 'media_asset_id',
        ]);
    }
}
