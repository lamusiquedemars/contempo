@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
])

@section('content')
<x-site.hero
    eyebrow="Tradition"
    :title="$page->hero_title ?? $page->title"
    :subtitle="$page->hero_subtitle ?? $page->excerpt"
    :image="\App\Support\MediaFiles::url($page->hero_image_path) ?? '/media/atelier-hero.jpg'"
    :cta-url="$contactUrl"
    cta-label="Prendre rendez-vous" />

<x-site.section
    title="Une sélection d'instruments et d'archets anciens"
    intro="Chaque pièce est choisie pour ses qualités, son état et son potentiel musical, puis préparée pour être essayée dans de bonnes conditions."
    heading-variant="accent">
    <div class="prose">
        <p>Les instruments et archets anciens demandent une attention particulière. Je les sélectionne avec exigence et vous accompagne pour trouver une pièce adaptée à votre jeu, à votre niveau et à votre projet musical.</p>
        <p>Un rendez-vous permet de prendre le temps de les découvrir, de les comparer et d’en apprécier les particularités.</p>
    </div>
</x-site.section>

@if ($page->publicGallery?->images->isNotEmpty())
    <x-site.section
        title="La sélection en images"
        intro="Photos de la sélection actuellement présentée à l'atelier."
        heading-variant="accent">
        <x-site.lightbox-gallery :images="$page->publicGallery->images" />
    </x-site.section>
@endif

<x-site.section variant="surface">
    <x-site.cta
        title="Essayer à l'atelier"
        text="Parlons de votre recherche et préparons un rendez-vous pour découvrir les instruments et archets disponibles."
        :href="$contactUrl"
        label="Prendre rendez-vous"
        variant="brand"
        inline />
</x-site.section>
@endsection
