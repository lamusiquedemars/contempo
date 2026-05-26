<?php

namespace Tests\Feature;

use App\Mail\ContactSubmissionReceived;
use App\Modules\Contact\Models\ContactSubmission;
use App\Modules\Gallery\Models\GalleryImage;
use App\Modules\News\Models\NewsPost;
use App\Modules\Notices\Models\SiteNotice;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_home_page_renders_the_starter_pitch(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'hero_title' => 'Un site clair',
            'hero_subtitle' => 'Une administration simple',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Un site clair')
            ->assertSee('Essence');
    }

    public function test_services_page_uses_dedicated_demo_template(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'template' => 'services',
            'hero_title' => 'Des sites vitrines administrables',
            'body_blocks' => [
                'essence_price' => 'A partir de 1500',
            ],
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/services')
            ->assertOk()
            ->assertSee('Trois niveaux')
            ->assertSee('A partir de 1500');
    }

    public function test_home_gallery_uses_configured_layout(): void
    {
        config(['maracuja.gallery.layout' => 'featured']);

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        GalleryImage::query()->create([
            'title' => 'Image demo',
            'caption' => 'Legende demo',
            'image_path' => '/demo/admin-simple.svg',
            'width' => 1200,
            'height' => 800,
            'position' => 1,
            'is_published' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('showcase--featured')
            ->assertSee('/demo/admin-simple.svg');
    }

    public function test_home_renders_active_notice_only(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        SiteNotice::query()->create([
            'title' => 'Horaires d ete',
            'message' => 'Ouverture exceptionnelle sur rendez-vous.',
            'placement' => 'home',
            'tone' => 'warning',
            'is_published' => true,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        SiteNotice::query()->create([
            'title' => 'Ancienne annonce',
            'message' => 'Message expire.',
            'placement' => 'home',
            'tone' => 'info',
            'is_published' => true,
            'starts_at' => now()->subDays(3),
            'ends_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Horaires d ete')
            ->assertSee('Ouverture exceptionnelle')
            ->assertDontSee('Ancienne annonce');
    }

    public function test_home_news_listing_hides_expired_posts(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        NewsPost::query()->create([
            'title' => 'Actualite active',
            'slug' => 'actualite-active',
            'excerpt' => 'Visible maintenant.',
            'is_published' => true,
            'published_at' => now()->subHour(),
            'expires_at' => now()->addDay(),
        ]);

        NewsPost::query()->create([
            'title' => 'Actualite expiree',
            'slug' => 'actualite-expiree',
            'excerpt' => 'Invisible maintenant.',
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'expires_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Actualite active')
            ->assertDontSee('Actualite expiree');
    }

    public function test_published_page_is_available_by_slug(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Methode',
            'slug' => 'methode',
            'hero_title' => 'Une structure avant les options',
            'body_blocks' => ['section' => 'Admin simple'],
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/methode')
            ->assertOk()
            ->assertSee('Fil d Ariane')
            ->assertSee('Une structure avant les options')
            ->assertSee('Admin simple')
            ->assertSee('Retour a l accueil');
    }

    public function test_contact_form_stores_submission_and_sends_mail(): void
    {
        Mail::fake();

        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => 'contact@maracuja.test',
        ]);

        $this->post('/contact', [
            'name' => 'Ivo',
            'email' => 'ivo@example.test',
            'subject' => 'Projet',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas(ContactSubmission::class, [
            'email' => 'ivo@example.test',
        ]);

        Mail::assertSent(ContactSubmissionReceived::class);
    }

    public function test_disabled_news_module_returns_not_found(): void
    {
        config(['maracuja.modules.news' => false]);

        $this->get('/actualites')->assertNotFound();
    }

    public function test_essence_offer_hides_signature_modules_from_public_navigation(): void
    {
        config(['maracuja.offer' => 'essence']);

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('href="http://localhost/actualites"', false)
            ->assertDontSee('Galerie demo');

        $this->get('/actualites')->assertNotFound();
    }

    public function test_expired_news_post_detail_returns_not_found(): void
    {
        SiteSetting::current();

        NewsPost::query()->create([
            'title' => 'Actualite expiree',
            'slug' => 'actualite-expiree',
            'excerpt' => 'Invisible maintenant.',
            'content' => '<p>Archive non visible.</p>',
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'expires_at' => now()->subDay(),
        ]);

        $this->get('/actualites/actualite-expiree')->assertNotFound();
    }

    public function test_news_detail_shows_breadcrumb_and_back_link(): void
    {
        SiteSetting::current();

        NewsPost::query()->create([
            'title' => 'Actualite active',
            'slug' => 'actualite-active',
            'excerpt' => 'Visible maintenant.',
            'content' => '<p>Detail de l actualite.</p>',
            'is_published' => true,
            'published_at' => now()->subHour(),
            'expires_at' => now()->addDay(),
        ]);

        $this->get('/actualites/actualite-active')
            ->assertOk()
            ->assertSee('Fil d Ariane')
            ->assertSee('Actualites')
            ->assertSee('Retour aux actualites');
    }
}
