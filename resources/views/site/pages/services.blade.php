@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
])

@section('content')
<x-site.hero
    eyebrow="Services"
    :title="$page->hero_title ?? $page->title"
    :subtitle="$page->hero_subtitle ?? $page->excerpt"
    image="/media/contempo/archets.jpg"
    :cta-url="$contactUrl"
    cta-label="Contacter l'atelier" />

<x-site.breadcrumb :items="[
    ['label' => $page->title],
]" />

<x-site.section
    title="Accompagner la vie de l'instrument"
    intro="De la fabrication sur mesure à la restauration, Contempo met son expérience au service des musiciens et de leurs besoins réels."
    heading-variant="accent">
    <x-site.grid columns="3">
        <x-site.card title="Fabrication" kicker="Sur mesure">
            Un projet d'instrument se construit par étapes, avec écoute, choix des matériaux et ajustements progressifs.
        </x-site.card>
        <x-site.card title="Entretien" kicker="Réglages">
            Chevalet, âme, cordes, confort de jeu et réponse sonore sont ajustés selon l'instrument et le musicien.
        </x-site.card>
        <x-site.card title="Restauration" kicker="Transmission">
            Les interventions respectent l'histoire de l'instrument et visent sa stabilité, sa justesse et sa musicalité.
        </x-site.card>
    </x-site.grid>
</x-site.section>

<x-site.section variant="muted" title="Essais, vente et location" heading-variant="underline">
    <x-site.grid columns="2">
        <x-site.feature-card title="Essayer" icon="01">
            Un salon permet de comparer plusieurs instruments ou archets dans de bonnes conditions, sur rendez-vous.
        </x-site.feature-card>
        <x-site.feature-card title="Être conseillé" icon="02">
            L'atelier peut organiser le support d'un musicien pour accompagner l'écoute et le choix final.
        </x-site.feature-card>
    </x-site.grid>
</x-site.section>

<x-site.section>
    <x-site.cta
        title="Parler d'un instrument"
        text="Expliquez votre besoin, votre niveau et votre calendrier. L'atelier vous orientera vers le service le plus juste."
        :href="$contactUrl"
        label="Prendre rendez-vous"
        variant="brand"
        inline />
</x-site.section>
@endsection
