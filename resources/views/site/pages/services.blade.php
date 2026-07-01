@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
])

@section('content')
<x-site.hero
    eyebrow="Services"
    :title="$page->hero_title ?? $page->title"
    :subtitle="$page->hero_subtitle ?? $page->excerpt"
    image="/media/entretien-detail.jpg"
    :cta-url="$contactUrl"
    cta-label="Prendre rendez-vous" />

<x-site.section
    title="C'est un plaisir de me mettre au service de vos instruments et de votre expérience musicale."
    intro="Le conseil reste mon fil conducteur : chaque choix est guidé pour vous apporter satisfaction et qualité."
    heading-variant="accent">
    <div class="prose">
        <h3>Vente</h3>
        <p>Contempo Luthiers vous propose une sélection d'instruments contemporains faits main par des luthiers artisans français et d'autres provenances. Mon atelier devient leur vitrine en centre-ville, et je suis heureux de présenter leurs créations à ma clientèle.</p>
        <p>Une collection d'instruments contemporains qui viennent de différentes approches techniques et artistiques permet idéalement aux musiciens de prendre conscience du niveau atteint par les artisans luthiers de notre époque, et de trouver plus facilement un instrument qui leur corresponde.</p>
        <p>Une gamme d'instruments et d'archets d'étude est disponible pour les étudiants et les amateurs. Tous les instruments de fabrication commerciale proposés ont été montés et réglés dans mon atelier, avec une première révision offerte.</p>
        <p>Un salon est disponible pour vos essais, sur rendez-vous conseillé. Vous pouvez aussi vous accorder avec l'atelier si vous souhaitez emprunter un instrument qui vous intéresse particulièrement.</p>
    </div>
</x-site.section>

<x-site.section variant="muted" title="Location, restauration et maintenance" heading-variant="underline">
    <x-site.grid columns="2">
        <x-site.card title="Location" kicker="Souplesse">
            Une alternative pratique, notamment pour les enfants ou pour des passionné.es qui souhaitent démarrer sur une période d'essai sans besoin d'un investissement immédiat.
        </x-site.card>
        <x-site.card title="Restauration et maintenance" kicker="Précision">
            Les interventions vont de la maintenance courante au réglage de sonorité avec le musicien, jusqu'aux réparations, avec écoute et respect des bonnes pratiques professionnelles.
        </x-site.card>
    </x-site.grid>
</x-site.section>

<x-site.section title="Cordes et accessoires">
    <div class="prose">
        <p>Nous proposons une sélection d'accessoires pour violon, alto, violoncelle et contrebasse, soigneusement choisis pour leur rapport qualité/prix favorable et selon les retours de nos clients : colophanes, épaulières toutes tailles, mentonnières, cordiers, et une petite gamme de housses et étuis de transport.</p>
        <p>La gamme de cordes en stock comprend un ensemble de marques et de séries adapté à tous les budgets et à plusieurs styles de musique. D'autres cordes spéciales sont disponibles sur commande, notamment les cordes en boyau.</p>
    </div>
</x-site.section>

<x-site.section variant="surface">
    <x-site.cta
        title="Prendre rendez-vous"
        text="Expliquez votre besoin, votre niveau et votre calendrier. L'atelier vous orientera vers le service le plus juste."
        :href="$contactUrl"
        label="Contacter l'atelier"
        variant="brand"
        inline />
</x-site.section>
@endsection
