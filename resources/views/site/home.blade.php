@extends('layouts.site', [
    'seoTitle' => $homePage?->seo_title,
    'seoDescription' => $homePage?->seo_description,
])

@section('content')
<x-site.hero
    variant="home"
    eyebrow="Giovanni Corazzol, maître luthier"
    :title="$homePage?->hero_title ?? $settings->site_name"
    :subtitle="$homePage?->hero_subtitle ?? $settings->baseline"
    image="/media/contempo/atelier-hero.jpg"
    :cta-url="$contactUrl"
    cta-label="Prendre rendez-vous"
    :secondary-cta-url="$servicesUrl"
    secondary-cta-label="Voir les services" />

@if ($homeNotice)
<div class="container notice-wrap">
    <x-site.notice :notice="$homeNotice" />
</div>
@endif

<x-site.section
    title="L'atelier"
    intro="Contempo Lutherie poursuit l'histoire de l'atelier Tranin, héritier de la maison Blanchard (1876), et y associe le savoir-faire crémonais de Giovanni pour une lutherie résolument contemporaine."
    heading-variant="accent">
    <x-site.grid columns="3">
        <x-site.card title="L'atelier" kicker="Héritage" image="/media/contempo/atelier-hero.jpg" :url="route('pages.show', 'atelier')">
            Je fabrique, je vends et j'entretiens des instruments pensés pour les musiciens d'aujourd'hui : leurs gestes, leurs besoins, leurs manières de jouer.
        </x-site.card>
        <x-site.card title="Les instruments" kicker="Sélection" image="/media/contempo/instrument.jpg" :url="route('pages.show', 'instruments')">
            Des modèles contemporains, des instruments anciens soigneusement sélectionnés, et des instruments d'étude réglés pour accompagner chaque parcours.
        </x-site.card>
        <x-site.card title="Les services" kicker="Accompagnement" image="/media/contempo/entretien.jpg" :url="route('pages.show', 'services')">
            De la fabrication sur mesure à la vente, de la location à la restauration, l'expérience de l'atelier sert les besoins réels des musiciens.
        </x-site.card>
    </x-site.grid>
</x-site.section>

<x-site.section
    variant="muted"
    title="Nous retrouver"
    intro="Au coeur de Lyon, nous prenons soin de vos instruments avec un savoir-faire artisanal : entretien, restauration, réglages et fabrication."
    heading-variant="underline">
    <x-site.grid columns="2-3">
        <x-site.cta
            title="Poussez la porte de l'atelier"
            text="Nous vous accueillons pour un échange simple et chaleureux."
            :href="$contactUrl"
            label="Horaires et accès"
            inline />
        <div class="stack stack--lg">
            <x-site.card title="Nous contacter" kicker="Téléphone et email">
                04 78 42 40 65<br>
                atelier@contempoluthiers.fr
            </x-site.card>
            <x-site.card title="Nous retrouver" kicker="Atelier">
                32 rue de la République, 69002 Lyon
            </x-site.card>
        </div>
    </x-site.grid>
</x-site.section>
@endsection
