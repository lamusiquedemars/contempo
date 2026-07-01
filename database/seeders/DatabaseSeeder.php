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
            'default_seo_description' => 'Contempo Luthiers, atelier et vitrine de lutherie contemporaine à Lyon: instruments, entretien, restauration, location et conseil.',
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
                'hero_title' => 'Contempo Lutherie',
                'hero_subtitle' => 'L héritage de l atelier Tranin et le savoir-faire crémonais de Giovanni pour une lutherie résolument contemporaine.',
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

        $heroImages = [
            'accueil' => '/media/contempo/atelier-hero.jpg',
            'atelier' => '/media/contempo/giovanni.jpg',
            'instruments' => '/media/contempo/instrument.jpg',
            'services' => '/media/contempo/entretien-detail.jpg',
            'contact' => '/media/contempo/location.jpg',
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
            ['title' => 'Giovanni', 'path' => '/media/contempo/giovanni.jpg', 'alt' => 'Giovanni dans l atelier Contempo luthiers', 'position' => 5],
            ['title' => 'Atelier général', 'path' => '/media/contempo/atelier-general.jpg', 'alt' => 'Vue générale de l atelier Contempo luthiers', 'position' => 6],
            ['title' => 'Rue de la République', 'path' => '/media/contempo/location.jpg', 'alt' => 'Accès à l atelier Contempo luthiers à Lyon', 'position' => 7],
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
