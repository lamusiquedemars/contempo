<?php

namespace Tests\Feature;

use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_page_outputs_core_seo_tags(): void
    {
        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'default_seo_title' => 'Maracuja default',
            'default_seo_description' => 'Description par défaut du starter.',
            'default_og_image_path' => '/demo/theme-system.svg',
        ]);

        Page::query()->create([
            'title' => 'Page SEO',
            'slug' => 'page-seo',
            'type' => Page::TYPE_TEXT,
            'content' => '<p>Informations SEO.</p>',
            'seo_title' => 'Page SEO',
            'seo_description' => 'Une description SEO claire.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/page-seo')
            ->assertOk()
            ->assertSee('<title>Page SEO</title>', false)
            ->assertSee('<meta name="description" content="Une description SEO claire.">', false)
            ->assertSee('<link rel="canonical" href="'.url('/page-seo').'">', false)
            ->assertSee('<meta property="og:title" content="Page SEO">', false)
            ->assertSee('<meta property="og:image" content="'.url('/demo/theme-system.svg').'">', false)
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_static_legal_pages_output_core_seo_tags(): void
    {
        SiteSetting::query()->create([
            'site_name' => 'Contempo luthiers',
            'default_seo_title' => 'Contempo default',
            'default_seo_description' => 'Description par défaut.',
            'default_og_image_path' => '/media/atelier-hero.jpg',
        ]);

        $this->get('/mentions-legales')
            ->assertOk()
            ->assertSee('<title>Mentions légales - Contempo luthiers</title>', false)
            ->assertSee('<link rel="canonical" href="'.url('/mentions-legales').'">', false)
            ->assertSee('<meta property="og:title" content="Mentions légales - Contempo luthiers">', false);

        $this->get('/confidentialite')
            ->assertOk()
            ->assertSee('<title>Confidentialité - Contempo luthiers</title>', false)
            ->assertSee('<link rel="canonical" href="'.url('/confidentialite').'">', false)
            ->assertSee('<meta property="og:title" content="Confidentialité - Contempo luthiers">', false);
    }

    public function test_robots_blocks_indexing_by_default(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /');
    }

    public function test_sitemap_lists_public_content(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('content-type', 'application/xml')
            ->assertSee('<loc>'.url('/').'</loc>', false)
            ->assertSee('<loc>'.url('/services').'</loc>', false)
            ->assertDontSee('<loc>'.url('/actualites/demo').'</loc>', false);
    }
}
