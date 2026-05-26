@extends('layouts.site', [
    'seoTitle' => $homePage?->seo_title,
    'seoDescription' => $homePage?->seo_description,
])

@section('content')
    <x-site.hero
        variant="home"
        :title="$homePage?->hero_title ?? $settings->site_name"
        :subtitle="$homePage?->hero_subtitle ?? $settings->baseline"
        cta-url="{{ route('contact') }}"
        cta-label="{{ \App\Support\ContentSlots::value('home.hero.cta_label', 'Presenter un projet') }}"
        secondary-cta-url="{{ route('pages.show', 'services') }}"
        secondary-cta-label="{{ \App\Support\ContentSlots::value('home.hero.secondary_cta_label', 'Voir les services') }}"
    />

    @if ($homeNotice)
        <div class="container notice-wrap">
            <x-site.notice :notice="$homeNotice" />
        </div>
    @endif

    <x-site.section
        :title="\App\Support\ContentSlots::value('home.intro.title', 'Le socle des offres Essence et Signature')"
        :intro="\App\Support\ContentSlots::value('home.intro.text', 'Un site vitrine administre, sans surcharge, avec les modules utiles au client et une base front propre.')"
        heading-variant="accent"
    >
        <x-site.grid columns="3">
            <x-site.feature-card title="Essence" icon="01" data-reveal>
                Un site vitrine clair, rapide a produire, avec pages structurees, contact et SEO de base.
            </x-site.feature-card>
            <x-site.feature-card title="Signature" icon="02" data-reveal data-reveal-delay="120">
                Une presence plus complete avec actualites, galerie, contenus plus riches et theme affirme.
            </x-site.feature-card>
            <x-site.feature-card title="Sur mesure" icon="03" data-reveal data-reveal-delay="240">
                Un module metier est ajoute seulement quand le client a un vrai besoin specifique.
            </x-site.feature-card>
        </x-site.grid>
    </x-site.section>

    <x-site.section variant="muted" title="Une admin courte" intro="Le client voit ses contenus, pas un cockpit inutile." heading-variant="underline">
        <x-site.grid columns="2-3">
            <x-site.quote author="Maracuja CMS" meta="Principe produit">
                Moins d'options visibles, plus de structure derriere.
            </x-site.quote>

            <div class="stack stack--lg">
                <x-site.card title="Modules activables" kicker="Admin">
                    Pages, Actualites, Galerie, Contact et Parametres s'affichent seulement si le projet en a besoin.
                </x-site.card>
                <x-site.card title="Pages cadrées" kicker="Front">
                    Le developpeur garde la structure en Blade. Le client modifie uniquement les contenus prevus.
                </x-site.card>
            </div>
        </x-site.grid>
    </x-site.section>

    @if ($galleryImages->isNotEmpty())
        <x-site.section :title="config('maracuja.gallery.title')" :intro="config('maracuja.gallery.intro')" heading-variant="decorated">
            <x-site.gallery
                :images="$galleryImages"
                :layout="config('maracuja.gallery.layout')"
                :lightbox="config('maracuja.gallery.lightbox')"
            />
        </x-site.section>
    @endif

    @if ($newsPosts->isNotEmpty())
        <x-site.section variant="surface" title="Actualites demo" intro="Un module contenu recurrent pour animer le site." heading-variant="accent">
            <x-site.grid columns="3">
                @foreach ($newsPosts as $post)
                    <x-site.card :title="$post->title" :url="route('news.show', $post->slug)">
                        {{ $post->excerpt }}
                    </x-site.card>
                @endforeach
            </x-site.grid>
        </x-site.section>
    @endif

    <x-site.section>
        <x-site.cta
            title="Pret pour une demo client"
            text="Cette installation montre le socle Essence / Signature: contenu administrable, front system, media system et admin modulee."
            href="{{ route('contact') }}"
            label="Demander une demo"
            variant="brand"
            inline
        />
    </x-site.section>
@endsection
