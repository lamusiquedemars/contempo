@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
])

@section('content')
<x-site.hero
    eyebrow="Instruments"
    :title="$page->hero_title"
    :subtitle="$page->hero_subtitle"
    image="/media/contempo/instrument.jpg"
    :cta-url="$contactUrl"
    cta-label="Demander conseil" />

<x-site.section
    title="Choisir, essayer, comparer"
    intro="L'atelier présente une sélection d'instruments et d'archets pour accompagner les musiciens dans leur recherche."
    heading-variant="accent">
    <x-site.grid columns="3">
        <x-site.card title="Instruments" kicker="Sélection" image="/media/contempo/instrument.jpg">
            Violons, altos, violoncelles et instruments d'étude ou de progression selon les disponibilités.
        </x-site.card>
        <x-site.card title="Archets" kicker="Équilibre" image="/media/contempo/archets.jpg">
            Des archets à essayer avec son instrument pour sentir la réponse, l'attaque et la couleur.
        </x-site.card>
        <x-site.card title="Location" kicker="Souplesse" image="/media/contempo/entretien.jpg">
            Des solutions de location et d'accompagnement pour les étapes de travail ou de formation.
        </x-site.card>
    </x-site.grid>
</x-site.section>

<x-site.section variant="surface">
    <x-site.cta
        title="Essayer dans de bonnes conditions"
        text="Le choix d'un instrument demande du temps, de l'écoute et parfois plusieurs essais. Un rendez-vous permet de préparer la sélection."
        :href="$contactUrl"
        label="Prendre rendez-vous"
        variant="brand"
        inline />
</x-site.section>
@endsection
