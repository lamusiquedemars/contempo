@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
])

@section('content')
<x-site.hero
    eyebrow="Instruments"
    :title="$page->hero_title"
    :subtitle="$page->hero_subtitle"
    :image="\App\Support\MediaFiles::url($page->hero_image_path) ?? '/media/instrument.jpg'"
    :cta-url="$contactUrl"
    cta-label="Demander conseil" />

<x-site.section
    title="Entre tradition, création et étude : ma sélection"
    intro="Je propose une sélection d'instruments choisis avec soin : des pièces anciennes, comme il est de tradition en lutherie ; des instruments d'auteur contemporains, réalisés par des confrères dont j'apprécie le travail ; et des instruments d'étude réglés pour accompagner sereinement les élèves."
    heading-variant="accent">
    <x-site.grid columns="3">
        <x-site.card title="Instruments contemporains" kicker="Auteurs" image="/media/instrument.jpg">
            Choisis pour la qualité du travail de mes confrères artisans, ces instruments offrent un toucher et une sonorité exceptionnels.
        </x-site.card>
        <x-site.card title="Instruments anciens" kicker="Tradition" image="/media/atelier-hero.jpg">
            Ces instruments respectent des prérequis de qualité précis et sont adaptés au niveau du musicien, pour une expérience authentique.
        </x-site.card>
        <x-site.card title="Instruments d'étude" kicker="Apprentissage" image="/media/entretien.jpg">
            Réglés pour être joués dans les meilleures conditions, ces instruments permettent aux élèves et étudiants de progresser sereinement.
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
