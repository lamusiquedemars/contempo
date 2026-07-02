@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
])

@section('content')
<x-site.hero
    eyebrow="Contempo luthiers"
    :title="$page->hero_title"
    :subtitle="$page->hero_subtitle"
    :image="\App\Support\MediaFiles::url($page->hero_image_path) ?? '/media/giovanni.jpg'"
    :cta-url="$contactUrl"
    cta-label="Nous rejoindre" />

<x-site.section
    title="Le luthier"
    intro="Un parcours formé à Crémone, nourri par l'atelier, la restauration et l'accompagnement quotidien des musiciens."
    heading-variant="accent">
    <div class="prose">
        <p>Je suis né à Aoste le 9 février 1970. Après le Liceo Scientifico d'Aoste et un début d'études d'ingénierie au Politecnico de Milan, j'ai interrompu mon parcours pour m'inscrire en 1994 à la Scuola statale di Liuteria « Antonio Stradivari » à Crémone, où j'ai obtenu mon diplôme de luthier en 1998 dans la classe du maître Stefano Conia.</p>
        <p>Après l'école, j'ai consolidé mon expérience en fréquentant différents ateliers et en vendant mes premiers instruments réalisés à la maison. L'expérience dans le laboratoire d'Alessandro Voltini a été déterminante : j'y ai approfondi la construction d'instruments neufs, les techniques de restauration, la mise au point acoustique et la finition des vernis.</p>
        <p>En 2007, je me suis installé à Syracuse. Pendant ces années, je me suis consacré principalement aux réparations, aux restaurations et à la maintenance des instruments, en particulier des contrebasses et des archets. Cette longue expérience m'a permis de perfectionner mon savoir-faire et d'accompagner de nombreux musiciens dans leur pratique.</p>
        <p>Aujourd'hui, j'ai repris l'atelier Tranin pour fonder Contempo Lutherie, avec l'objectif de proposer des instruments de lutherie contemporaine, alliant tradition et exigence moderne.</p>
    </div>
</x-site.section>

<x-site.section
    variant="muted"
    title="Le lieu et le savoir-faire : une histoire lyonnaise depuis 1876"
    intro="Contempo prolonge près de cent cinquante ans d'histoire lyonnaise, en inscrivant la main du luthier dans le présent."
    heading-variant="underline">
    <x-site.grid columns="3">
        <x-site.card title="Paul François Blanchard" kicker="1876">
            Formé à Mirecourt puis à Paris, Blanchard s'installe à Lyon et fonde un atelier réputé pour la qualité de ses instruments, souvent signés Lugdunum.
        </x-site.card>
        <x-site.card title="Émile Boulangeot" kicker="1913">
            Ancien élève de Gustave Bernardel puis de Caressa & Français, il conserve les modèles de Blanchard tout en développant sa propre facture.
        </x-site.card>
        <x-site.card title="Georges et Robert Coné" kicker="1928">
            L'atelier devient l'un des plus actifs de la région, avec une continuité de production et de maîtrise jusqu'à la fin des années 1970.
        </x-site.card>
        <x-site.card title="Dominique Camard" kicker="1983">
            L'atelier déménage au 32 rue de la République et se consacre davantage à la vente, la location et la restauration.
        </x-site.card>
        <x-site.card title="Frédéric Tranin" kicker="2012">
            Après près de vingt ans de collaboration, il perpétue l'esprit d'exigence et de proximité avec les musiciens.
        </x-site.card>
        <x-site.card title="Contempo" kicker="Aujourd'hui">
            La maison affirme une orientation résolument contemporaine, artisanale, exigeante et ouverte.
        </x-site.card>
    </x-site.grid>
</x-site.section>

<x-site.section>
    <x-site.cta
        title="Nous rejoindre"
        text="L'atelier vous accueille au 32 rue de la République, 69002 Lyon. Un message permet de préparer votre visite dans de bonnes conditions."
        :href="$contactUrl"
        label="Contacter l'atelier"
        variant="brand"
        inline />
</x-site.section>
@endsection
