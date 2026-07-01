@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
])

@section('content')
<x-site.hero
    eyebrow="Contempo luthiers"
    :title="$page->hero_title"
    :subtitle="$page->hero_subtitle"
    image="/media/contempo/atelier-hero.jpg"
    :cta-url="$contactUrl"
    cta-label="Prendre rendez-vous" />

<x-site.section
    title="Un atelier au coeur de Lyon"
    intro="Contempo accompagne les musiciens dans le choix, l'entretien et la vie de leur instrument."
    heading-variant="accent">
    <x-site.grid columns="2-3">
        <x-site.quote author="Contempo luthiers" meta="Atelier">
            Une approche directe, exigeante et sensible de la lutherie contemporaine.
        </x-site.quote>
        <div class="stack stack--lg">
            <x-site.card title="Conseil" kicker="Écoute">
                Un échange pour comprendre le jeu, le niveau, le répertoire et les besoins concrets du musicien.
            </x-site.card>
            <x-site.card title="Réglage" kicker="Précision">
                Un travail d'ajustement au service du confort, de la projection et de la réponse de l'instrument.
            </x-site.card>
        </div>
    </x-site.grid>
</x-site.section>

<x-site.section variant="muted" title="Nous retrouver" intro="32 rue de la République, 69002 Lyon. Les essais se font idéalement sur rendez-vous.">
    <x-site.cta
        title="Préparer une visite"
        text="Un salon est disponible pour les essais. L'atelier peut aussi organiser l'accompagnement d'un musicien selon le besoin."
        :href="$contactUrl"
        label="Contacter l'atelier"
        inline />
</x-site.section>
@endsection
