<?php

namespace Tests\Feature;

use App\Modules\ContactForm\Mail\ContactMessageConfirmation;
use App\Modules\ContactForm\Mail\ContactMessageReceived;
use App\Modules\Inquiries\Models\Inquiry;
use App\Modules\ContentSlots\Models\ContentSlot;
use App\Modules\Gallery\Models\Gallery;
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

    public function test_home_page_renders_the_contempo_pitch(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'hero_title' => 'Atelier et vitrine de lutherie contemporaine à Lyon',
            'hero_subtitle' => 'Instruments, archets et entretien.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Atelier et vitrine de lutherie contemporaine')
            ->assertSee('Instruments')
            ->assertDontSee('Essence');
    }

    public function test_services_page_uses_dedicated_contempo_template(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'template' => 'services',
            'hero_title' => 'Services de lutherie pour musiciens',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/services')
            ->assertOk()
            ->assertSee('Accompagner la vie de l&#039;instrument', false)
            ->assertSee('Fabrication')
            ->assertSee('Restauration');
    }

    public function test_services_page_ignores_old_starter_offer_slots(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'template' => 'services',
            'hero_title' => 'Services de lutherie pour musiciens',
            'is_published' => true,
            'published_at' => now(),
        ]);

        ContentSlot::query()->create([
            'key' => 'services.essence.price',
            'label' => 'Prix Essence',
            'group' => 'Services',
            'type' => 'price',
            'value' => 'À partir de 1800',
            'is_locked' => true,
        ]);

        $this->get('/services')
            ->assertOk()
            ->assertDontSee('À partir de 1800')
            ->assertDontSee('À partir de 1500')
            ->assertSee('Prendre rendez-vous');
    }

    public function test_home_hides_contact_and_services_ctas_when_targets_are_unavailable(): void
    {
        config(['maracuja.modules.contact_form' => false]);

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'hero_title' => 'Un site clair',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('href="http://localhost/contact"', false)
            ->assertDontSee('href="http://localhost/services"', false);
    }

    public function test_services_page_hides_contact_ctas_when_contact_module_is_disabled(): void
    {
        config(['maracuja.modules.contact_form' => false]);

        SiteSetting::current();

        Page::query()->create([
            'title' => 'Services',
            'slug' => 'services',
            'template' => 'services',
            'type' => Page::TYPE_SYSTEM,
            'hero_title' => 'Des sites vitrines administrables',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/services')
            ->assertOk()
            ->assertDontSee('href="http://localhost/contact"', false);
    }

    public function test_home_uses_contempo_media_cards(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('/media/contempo/atelier-hero.jpg')
            ->assertSee('/media/contempo/instrument.jpg')
            ->assertSee('/media/contempo/entretien.jpg');
    }

    public function test_home_does_not_render_old_demo_gallery(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('/demo/admin-simple.svg');
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
            'title' => 'Horaires d’été',
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
            ->assertSee('Horaires d’été')
            ->assertSee('Ouverture exceptionnelle')
            ->assertDontSee('Ancienne annonce');
    }

    public function test_home_does_not_render_news_when_news_module_is_disabled(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Accueil',
            'slug' => 'accueil',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Actualités')
            ->assertDontSee('Actualité active');
    }

    public function test_text_page_is_available_by_slug(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Mentions légales',
            'slug' => 'mentions-legales',
            'type' => Page::TYPE_TEXT,
            'hero_title' => 'Mentions légales',
            'content' => '<p>Éditeur du site: Maracuja CMS.</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/mentions-legales')
            ->assertOk()
            ->assertSee('Fil d Ariane')
            ->assertSee('Mentions légales')
            ->assertSee('Éditeur du site: Maracuja CMS')
            ->assertSee('Retour à l&#039;accueil', false);
    }

    public function test_contact_page_uses_page_registry_metadata(): void
    {
        SiteSetting::current();

        Page::query()->create([
            'title' => 'Contact',
            'slug' => 'contact',
            'type' => Page::TYPE_SYSTEM,
            'template' => 'contact',
            'hero_title' => 'Parlons du projet',
            'hero_subtitle' => 'Un message suffit pour démarrer.',
            'seo_title' => 'Contact SEO',
            'seo_description' => 'Contacter le studio.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->get('/contact')
            ->assertOk()
            ->assertSee('<title>Contact SEO</title>', false)
            ->assertSee('Parlons du projet')
            ->assertSee('Un message suffit pour démarrer.');
    }

    public function test_contact_form_stores_inquiry_and_sends_mail_when_inquiries_module_is_enabled(): void
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

        $this->assertDatabaseHas(Inquiry::class, [
            'email' => 'ivo@example.test',
        ]);

        Mail::assertSent(ContactMessageReceived::class);
    }

    public function test_contact_form_sends_mail_without_storing_inquiry_when_inquiries_module_is_not_enabled(): void
    {
        config(['maracuja.modules.inquiries' => false]);

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

        $this->assertDatabaseCount(Inquiry::class, 0);

        Mail::assertSent(ContactMessageReceived::class);
    }

    public function test_contact_form_rejects_invalid_email_without_top_level_domain(): void
    {
        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => 'contact@maracuja.test',
        ]);

        $this->post('/contact', [
            'name' => 'Ivo',
            'email' => 'ivo@mail',
            'subject' => 'Projet',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount(Inquiry::class, 0);
    }

    public function test_contact_form_stores_inquiry_when_admin_email_is_not_configured(): void
    {
        Mail::fake();

        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => null,
        ]);

        $this->post('/contact', [
            'name' => 'Ivo',
            'email' => 'ivo@example.test',
            'subject' => 'Projet',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas(Inquiry::class, [
            'email' => 'ivo@example.test',
        ]);

        Mail::assertNothingSent();
    }

    public function test_contact_form_sends_user_confirmation_if_enabled(): void
    {
        Mail::fake();

        SiteSetting::query()->create([
            'site_name' => 'Maracuja CMS',
            'contact_email' => 'contact@maracuja.test',
            'contact_form_send_confirmation_email' => true,
        ]);

        $this->post('/contact', [
            'name' => 'Ivo',
            'email' => 'ivo@example.test',
            'subject' => 'Projet',
            'message' => 'Bonjour depuis le formulaire.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas(Inquiry::class, [
            'email' => 'ivo@example.test',
        ]);

        Mail::assertSent(ContactMessageConfirmation::class);
    }

    public function test_disabled_news_module_returns_not_found(): void
    {
        config(['maracuja.modules.news' => false]);

        $this->get('/actualites')->assertNotFound();
    }

    public function test_essence_offer_hides_signature_modules_from_public_navigation(): void
    {
        config([
            'maracuja.modules.news' => false,
            'maracuja.modules.articles' => false,
            'maracuja.modules.gallery' => false,
        ]);

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
            ->assertDontSee('href="http://localhost/articles"', false)
            ->assertDontSee('Galerie démo');

        $this->get('/actualites')->assertNotFound();
        $this->get('/articles')->assertNotFound();
    }

    public function test_expired_news_post_detail_returns_not_found(): void
    {
        SiteSetting::current();

        NewsPost::query()->create([
            'title' => 'Actualité expirée',
            'slug' => 'actualite-expiree',
            'excerpt' => 'Invisible maintenant.',
            'content' => '<p>Archive non visible.</p>',
            'is_published' => true,
            'has_detail_page' => true,
            'published_at' => now()->subDays(5),
            'expires_at' => now()->subDay(),
        ]);

        $this->get('/actualites/actualite-expiree')->assertNotFound();
    }

    public function test_news_list_is_not_available_for_contempo(): void
    {
        $this->get('/actualites')->assertNotFound();
    }

    public function test_news_detail_is_not_available_for_contempo(): void
    {
        $this->get('/actualites/annonce-simple')->assertNotFound();
    }

    public function test_articles_are_not_available_for_contempo(): void
    {
        $this->get('/articles')->assertNotFound();
        $this->get('/articles/bois-et-geste')->assertNotFound();
    }

    public function test_legacy_article_route_is_not_available_for_contempo(): void
    {
        $this->get('/article.php?slug=bois-et-geste')->assertNotFound();
    }
}
