@extends('layouts.site', [
    'seoTitle' => $homePage?->seo_title,
    'seoDescription' => $homePage?->seo_description,
])

@section('content')
<x-site.hero
    variant="home"
    eyebrow="Contempo luthiers"
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
    title="Atelier et vitrine de lutherie contemporaine"
    intro="À Lyon, Contempo réunit conseil, entretien, restauration, vente et location autour d'une même attention portée aux musiciens."
    heading-variant="accent">
    <x-site.grid columns="3">
        <x-site.card title="L'atelier" kicker="Savoir-faire" image="/media/contempo/atelier-hero.jpg" :url="route('pages.show', 'atelier')">
            Un lieu de travail et d'échange pour régler, restaurer et accompagner les instruments.
        </x-site.card>
        <x-site.card title="Instruments" kicker="Essais" image="/media/contempo/instrument.jpg" :url="route('pages.show', 'instruments')">
            Une sélection d'instruments et d'archets à découvrir dans de bonnes conditions.
        </x-site.card>
        <x-site.card title="Services" kicker="Accompagnement" image="/media/contempo/entretien.jpg" :url="route('pages.show', 'services')">
            Fabrication, entretien, restauration, location et conseil selon les besoins réels.
        </x-site.card>
    </x-site.grid>
</x-site.section>

<x-site.section
    variant="muted"
    title="Un choix accompagné"
    intro="Un instrument se choisit avec le corps, l'oreille et le temps. Contempo prépare les essais et aide à formuler les critères importants."
    heading-variant="underline">
    <x-site.grid columns="2-3">
        <x-site.quote author="Contempo luthiers" meta="Lyon">
            Le bon instrument n'est pas seulement celui qui sonne. C'est celui avec lequel le musicien peut travailler, chercher et progresser.
        </x-site.quote>
        <div class="stack stack--lg">
            <x-site.card title="Essais sur rendez-vous" kicker="Salon">
                Un espace est disponible pour essayer les instruments avec calme et recul.
            </x-site.card>
            <x-site.card title="Conseil musical" kicker="Écoute">
                Selon le besoin, un musicien peut accompagner la visite et aider à comparer les réponses.
            </x-site.card>
        </div>
    </x-site.grid>
</x-site.section>

<x-site.section>
    <x-site.cta
        title="Préparer votre visite"
        text="L'atelier est situé au 32 rue de la République, 69002 Lyon. Un message suffit pour organiser un rendez-vous."
        :href="$contactUrl"
        label="Contacter l'atelier"
        variant="brand"
        inline />
</x-site.section>
@endsection
