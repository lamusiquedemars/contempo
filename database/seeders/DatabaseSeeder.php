<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\ContentSlots\Models\ContentSlot;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryImage;
use App\Modules\News\Models\NewsPost;
use App\Modules\Notices\Models\SiteNotice;
use App\Modules\Pages\Models\Page;
use App\Modules\SiteSettings\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'admin@contempoluthiers.test',
        ], [
            'name' => 'Contempo Admin',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'site_name' => 'Contempo luthiers',
            'baseline' => 'La vitrine de lutherie contemporaine à Lyon',
            'default_seo_title' => 'Contempo luthiers - Atelier et vitrine de lutherie contemporaine à Lyon',
            'default_seo_description' => 'Contempo accompagne les musiciens à Lyon: atelier, instruments, archets, entretien, restauration, location et conseil.',
            'default_og_image_path' => '/media/contempo/atelier-hero.jpg',
            'logo_path' => '/media/contempo/logo.jpg',
            'favicon_path' => null,
            'contact_email' => 'atelier@contempoluthiers.fr',
            'phone' => '04 78 42 40 65',
            'address' => '32 rue de la République, 69002 Lyon',
            'contact_form_send_admin_email' => false,
            'contact_form_send_confirmation_email' => false,
            'social_links' => [],
        ]);

        collect([
            [
                'key' => 'gallery.title',
                'label' => 'Titre galerie',
                'group' => 'Galerie',
                'type' => 'text',
                'value' => 'L atelier en images',
                'help_text' => 'Titre de secours de la galerie.',
            ],
            [
                'key' => 'gallery.intro',
                'label' => 'Introduction galerie',
                'group' => 'Galerie',
                'type' => 'textarea',
                'value' => 'Atelier, archets, instruments et gestes de lutherie.',
                'help_text' => 'Introduction de secours de la galerie.',
            ],
        ])->each(fn (array $slot) => ContentSlot::query()->updateOrCreate(
            ['key' => $slot['key']],
            $slot + ['is_locked' => true],
        ));

        $pages = [
            [
                'slug' => 'accueil',
                'title' => 'Accueil',
                'template' => 'landing',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Atelier et vitrine de lutherie contemporaine à Lyon.',
                'hero_title' => 'Atelier et vitrine de lutherie contemporaine à Lyon',
                'hero_subtitle' => 'Contempo accompagne les musiciens dans le choix, l entretien, la restauration et la vie de leurs instruments.',
                'seo_title' => 'Contempo luthiers - Lutherie contemporaine à Lyon',
                'seo_description' => 'Atelier de lutherie contemporaine à Lyon: instruments, archets, entretien, restauration, location et conseil.',
            ],
            [
                'slug' => 'atelier',
                'title' => 'L atelier',
                'template' => 'atelier',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Un atelier de lutherie au coeur de Lyon.',
                'hero_title' => 'Un atelier de lutherie au coeur de Lyon',
                'hero_subtitle' => 'Un lieu d écoute, de précision et de conseil pour prendre soin des instruments et accompagner les musiciens.',
                'seo_title' => 'L atelier - Contempo luthiers',
                'seo_description' => 'Découvrez l atelier Contempo luthiers à Lyon, dédié au réglage, à l entretien, à la restauration et au conseil.',
            ],
            [
                'slug' => 'instruments',
                'title' => 'Instruments',
                'template' => 'instruments',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Instruments et archets à essayer à l atelier.',
                'hero_title' => 'Instruments et archets à essayer',
                'hero_subtitle' => 'Une sélection à découvrir avec le temps nécessaire pour comparer, écouter et choisir.',
                'seo_title' => 'Instruments - Contempo luthiers',
                'seo_description' => 'Instruments et archets à essayer chez Contempo luthiers, atelier de lutherie contemporaine à Lyon.',
            ],
            [
                'slug' => 'services',
                'title' => 'Services',
                'template' => 'services',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Fabrication, entretien, restauration, location et conseil.',
                'hero_title' => 'Services de lutherie pour musiciens',
                'hero_subtitle' => 'Fabrication sur mesure, vente, location, entretien et restauration, avec une même exigence de justesse.',
                'seo_title' => 'Services - Contempo luthiers',
                'seo_description' => 'Services de lutherie à Lyon: fabrication, réglage, entretien, restauration, location, vente et conseil.',
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact',
                'template' => 'contact',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Contacter l atelier Contempo luthiers.',
                'hero_title' => 'Contact',
                'hero_subtitle' => 'Un message suffit pour préparer une visite, un essai ou une demande d entretien.',
                'seo_title' => 'Contact - Contempo luthiers',
                'seo_description' => 'Contacter Contempo luthiers, 32 rue de la République, 69002 Lyon.',
            ],
            [
                'slug' => 'mentions-legales',
                'title' => 'Mentions légales',
                'template' => 'default',
                'type' => Page::TYPE_TEXT,
                'excerpt' => 'Informations légales.',
                'hero_title' => 'Mentions légales',
                'hero_subtitle' => 'Informations légales du site Contempo luthiers.',
                'content' => '<p>Site de Contempo luthiers.</p><p>Adresse: 32 rue de la République, 69002 Lyon.</p><p>Contact: atelier@contempoluthiers.fr.</p>',
                'seo_title' => 'Mentions légales - Contempo luthiers',
                'seo_description' => 'Mentions légales du site Contempo luthiers.',
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(['slug' => $page['slug']], [
                'title' => $page['title'],
                'template' => $page['template'],
                'type' => $page['type'],
                'excerpt' => $page['excerpt'],
                'hero_title' => $page['hero_title'],
                'hero_subtitle' => $page['hero_subtitle'],
                'hero_image_path' => $page['slug'] === 'accueil' ? '/media/contempo/atelier-hero.jpg' : null,
                'content' => $page['content'] ?? null,
                'seo_title' => $page['seo_title'],
                'seo_description' => $page['seo_description'],
                'is_published' => true,
                'published_at' => now(),
            ]);
        }

        Page::query()->whereIn('slug', ['actualites', 'blog'])->delete();
        NewsPost::query()->delete();
        SiteNotice::query()->delete();

        $gallery = Gallery::query()->updateOrCreate(['slug' => 'home'], [
            'title' => 'L atelier en images',
            'intro' => 'Quelques repères visuels issus du site WordPress actuel.',
            'position' => 1,
            'is_published' => true,
        ]);

        collect([
            ['title' => 'Atelier', 'path' => '/media/contempo/atelier-hero.jpg', 'alt' => 'Atelier Contempo luthiers à Lyon', 'position' => 1],
            ['title' => 'Archets', 'path' => '/media/contempo/archets.jpg', 'alt' => 'Archets présentés à l atelier', 'position' => 2],
            ['title' => 'Entretien', 'path' => '/media/contempo/entretien.jpg', 'alt' => 'Travail d entretien en atelier de lutherie', 'position' => 3],
            ['title' => 'Instrument', 'path' => '/media/contempo/instrument.jpg', 'alt' => 'Instrument à cordes frottées', 'position' => 4],
        ])->each(fn (array $image) => GalleryImage::query()->updateOrCreate([
            'title' => $image['title'],
        ], [
            'gallery_id' => $gallery->id,
            'caption' => null,
            'alt_text' => $image['alt'],
            'credit' => 'Contempo luthiers',
            'image_path' => $image['path'],
            'width' => 1536,
            'height' => 1024,
            'position' => $image['position'],
            'is_published' => true,
        ]));
    }
}
