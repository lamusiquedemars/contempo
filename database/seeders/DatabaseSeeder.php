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
            'default_og_image_path' => '/media/atelier-hero.jpg',
            'logo_path' => '/media/logo.jpg',
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
            [
                'slug' => 'mentions-legales',
                'title' => 'Mentions légales',
                'template' => 'default',
                'type' => Page::TYPE_TEXT,
                'excerpt' => 'Informations légales du site Contempo luthiers.',
                'hero_title' => 'Mentions légales',
                'hero_subtitle' => 'Informations légales du site Contempo luthiers.',
                'content' => '<h2>1. Éditeur du site</h2><p><strong>Éditeur :</strong> Contempo luthiers<br><strong>Responsable de publication :</strong> Giovanni Corazzol<br><strong>Adresse de l atelier :</strong> 32 rue de la République, 69002 Lyon<br><strong>E-mail :</strong> <a href="mailto:atelier@contempoluthiers.fr">atelier@contempoluthiers.fr</a></p><h2>2. Hébergeur</h2><p>Ce site est hébergé par :<br><strong>Ligne Web Services (LWS)</strong><br>Adresse : 10 rue de Penthièvre, 75008 Paris, France.<br>SIREN / SIRET : 851 993 683.</p><h2>3. Propriété intellectuelle</h2><p>L ensemble du contenu présent sur ce site, notamment les textes, photographies, images, logos et éléments graphiques, est protégé par le droit de la propriété intellectuelle. Toute reproduction, adaptation, diffusion ou réutilisation, partielle ou totale, est interdite sans autorisation écrite préalable.</p><h2>4. Données personnelles</h2><p>Les informations relatives aux données personnelles sont détaillées dans la page <a href="/confidentialite">Confidentialité</a>.</p><h2>5. Responsabilité</h2><p>Ce site présente l activité de Contempo luthiers et les informations pratiques de l atelier. Les informations publiées sont fournies avec soin, mais peuvent être modifiées ou corrigées à tout moment.</p><h2>6. Liens externes</h2><p>Le site peut contenir des liens vers d autres sites. Contempo luthiers n exerce pas de contrôle sur leur contenu, leur disponibilité ou leurs pratiques relatives aux données personnelles.</p><h2>7. Modification des mentions légales</h2><p>Ces mentions légales peuvent être modifiées à tout moment. La version en ligne actuelle prévaut sur toute version antérieure.</p>',
                'seo_title' => 'Mentions légales - Contempo luthiers',
                'seo_description' => 'Mentions légales du site Contempo luthiers.',
            ],
            [
                'slug' => 'confidentialite',
                'title' => 'Confidentialité',
                'template' => 'default',
                'type' => Page::TYPE_TEXT,
                'excerpt' => 'Traitement des données personnelles sur le site Contempo luthiers.',
                'hero_title' => 'Confidentialité',
                'hero_subtitle' => 'Données personnelles, relation client et droits des personnes.',
                'content' => '<h2>1. Responsable du traitement</h2><p>Les données collectées sur ce site sont traitées par Contempo luthiers, sous la responsabilité de Giovanni Corazzol. Contact : <a href="mailto:atelier@contempoluthiers.fr">atelier@contempoluthiers.fr</a>.</p><h2>2. Données collectées</h2><p>Le site peut collecter les données transmises via le formulaire de contact : nom, adresse e-mail, téléphone, sujet et contenu du message. Le module relation client peut aussi conserver des informations de suivi utiles à la relation avec l atelier.</p><h2>3. Finalités</h2><p>Ces données sont utilisées pour répondre aux demandes, préparer un rendez-vous, assurer le suivi des clients, informer les personnes concernées sur les horaires, les tarifs, les services de l atelier ou les informations pratiques liées à la relation avec Contempo luthiers.</p><h2>4. Bases légales</h2><p>Le traitement repose, selon les cas, sur la demande de la personne lorsqu elle contacte l atelier, sur l intérêt légitime de Contempo luthiers à assurer le suivi de sa relation client, ou sur le consentement lorsque celui-ci est nécessaire.</p><h2>5. Messages d information et désinscription</h2><p>Les messages envoyés depuis le module relation client sont destinés aux contacts concernés par l activité de l atelier. Chaque message cible comporte un lien permettant de ne plus recevoir ces communications. Les contacts désinscrits ne sont plus inclus dans les envois.</p><h2>6. Durée de conservation</h2><p>Les demandes de contact et informations de suivi sont conservées pendant la durée nécessaire à la relation avec l atelier, puis peuvent être supprimées ou archivées lorsqu elles ne sont plus utiles. Une personne peut demander la suppression de ses données à tout moment, sauf obligation légale contraire.</p><h2>7. Destinataires</h2><p>Les données sont destinées à Contempo luthiers. Elles peuvent être traitées par les prestataires techniques nécessaires au fonctionnement du site, notamment l hébergeur, dans la limite de leurs missions.</p><h2>8. Droits des personnes</h2><p>Vous pouvez demander l accès, la rectification, l effacement ou l opposition au traitement de vos données personnelles. Pour exercer ces droits, écrivez à <a href="mailto:atelier@contempoluthiers.fr">atelier@contempoluthiers.fr</a>. Vous pouvez également introduire une réclamation auprès de la CNIL.</p><h2>9. Cookies</h2><p>Le site n utilise pas de cookies publicitaires ni de traceurs de mesure d audience soumis au consentement. Les seuls cookies ou mécanismes techniques éventuels sont ceux strictement nécessaires au fonctionnement du site, à la sécurité ou à l administration.</p>',
                'seo_title' => 'Confidentialité - Contempo luthiers',
                'seo_description' => 'Politique de confidentialité du site Contempo luthiers.',
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
        SiteNotice::query()->delete();

        $gallery = Gallery::query()->updateOrCreate(['slug' => 'home'], [
            'title' => 'L atelier en images',
            'intro' => 'Quelques repères visuels issus du site WordPress actuel.',
            'position' => 1,
            'is_published' => true,
        ]);

        collect([
            ['title' => 'Atelier', 'path' => '/media/atelier-hero.jpg', 'alt' => 'Atelier Contempo luthiers à Lyon', 'position' => 1],
            ['title' => 'Archets', 'path' => '/media/archets.jpg', 'alt' => 'Archets présentés à l atelier', 'position' => 2],
            ['title' => 'Entretien', 'path' => '/media/entretien.jpg', 'alt' => 'Travail d entretien en atelier de lutherie', 'position' => 3],
            ['title' => 'Instrument', 'path' => '/media/instrument.jpg', 'alt' => 'Instrument à cordes frottées', 'position' => 4],
            ['title' => 'Giovanni', 'path' => '/media/giovanni.jpg', 'alt' => 'Giovanni dans l atelier Contempo luthiers', 'position' => 5],
            ['title' => 'Atelier général', 'path' => '/media/atelier-general.jpg', 'alt' => 'Vue générale de l atelier Contempo luthiers', 'position' => 6],
            ['title' => 'Rue de la République', 'path' => '/media/location.jpg', 'alt' => 'Accès à l atelier Contempo luthiers à Lyon', 'position' => 7],
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
