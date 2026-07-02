<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\News\Models\NewsPost;
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
            'default_seo_description' => 'Contempo Luthiers, atelier et vitrine de lutherie contemporaine à Lyon: instruments, entretien, restauration, location et conseil.',
            'default_og_image_path' => '/media/atelier-hero.jpg',
            'logo_path' => '/media/logo.jpg',
            'favicon_path' => null,
            'contact_email' => 'atelier@contempoluthiers.fr',
            'phone' => '04 78 42 40 65',
            'address' => '32 rue de la République, 69002 Lyon',
            'contact_form_send_admin_email' => true,
            'contact_form_send_confirmation_email' => false,
            'social_links' => [],
        ]);

        $pages = [
            [
                'slug' => 'accueil',
                'title' => 'Accueil',
                'template' => 'landing',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Atelier et vitrine de lutherie contemporaine à Lyon.',
                'hero_title' => 'Contempo Luthiers',
                'hero_subtitle' => 'Atelier et vitrine de la lutherie contemporaine à Lyon',
                'seo_title' => 'Contempo luthiers - Lutherie contemporaine à Lyon',
                'seo_description' => 'Contempo Luthiers à Lyon: lutherie contemporaine, instruments anciens et d étude, entretien, restauration, location et conseil.',
            ],
            [
                'slug' => 'atelier',
                'title' => 'L atelier',
                'template' => 'atelier',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Le luthier, le lieu et l histoire lyonnaise de l atelier.',
                'hero_title' => 'Le luthier',
                'hero_subtitle' => 'Un parcours formé à Crémone, aujourd hui au service d une lutherie contemporaine ancrée dans l histoire lyonnaise.',
                'seo_title' => 'L atelier - Contempo luthiers',
                'seo_description' => 'Découvrez le luthier de Contempo Luthiers et l histoire de l atelier lyonnais depuis 1876.',
            ],
            [
                'slug' => 'instruments',
                'title' => 'Instruments',
                'template' => 'instruments',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Instruments contemporains, anciens et d étude à essayer à l atelier.',
                'hero_title' => 'Entre tradition, création et étude',
                'hero_subtitle' => 'Une sélection simple, cohérente, pensée pour chaque musicien.',
                'seo_title' => 'Instruments - Contempo luthiers',
                'seo_description' => 'Instruments contemporains, instruments anciens et instruments d étude chez Contempo Luthiers à Lyon.',
            ],
            [
                'slug' => 'services',
                'title' => 'Services',
                'template' => 'services',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Fabrication, entretien, restauration, location et conseil.',
                'hero_title' => 'Services',
                'hero_subtitle' => 'Vente, location, restauration, maintenance, cordes et accessoires pour accompagner la vie de votre instrument.',
                'seo_title' => 'Services - Contempo luthiers',
                'seo_description' => 'Services de lutherie à Lyon: vente, location, restauration, maintenance, cordes, accessoires et conseil.',
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact',
                'template' => 'contact',
                'type' => Page::TYPE_SYSTEM,
                'excerpt' => 'Contacter l atelier Contempo luthiers.',
                'hero_title' => 'Contact',
                'hero_subtitle' => 'Nous écrire, nous rejoindre et préparer votre visite à l atelier.',
                'seo_title' => 'Contact - Contempo luthiers',
                'seo_description' => 'Contacter Contempo luthiers, 32 rue de la République, 69002 Lyon.',
            ],
        ];

        $heroImages = [
            'accueil' => 'media/atelier-hero.jpg',
            'atelier' => 'media/giovanni.jpg',
            'instruments' => 'media/instrument.jpg',
            'services' => 'media/entretien-detail.jpg',
            'contact' => 'media/location.jpg',
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(['slug' => $page['slug']], [
                'title' => $page['title'],
                'template' => $page['template'],
                'type' => $page['type'],
                'excerpt' => $page['excerpt'],
                'hero_title' => $page['hero_title'],
                'hero_subtitle' => $page['hero_subtitle'],
                'hero_image_path' => $heroImages[$page['slug']] ?? null,
                'content' => $page['content'] ?? null,
                'seo_title' => $page['seo_title'],
                'seo_description' => $page['seo_description'],
                'is_published' => true,
                'published_at' => now(),
            ]);
        }

        Page::query()->whereIn('slug', ['actualites', 'blog'])->delete();
        NewsPost::query()->delete();
    }
}
