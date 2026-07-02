@extends('layouts.site', [
    'seoTitle' => 'Mentions légales - Contempo luthiers',
    'seoDescription' => 'Mentions légales du site Contempo luthiers.',
])

@section('content')
    <x-site.hero
        eyebrow="Légal"
        title="Mentions légales"
        subtitle="Informations légales du site Contempo luthiers."
    />

    <x-site.breadcrumb :items="[['label' => 'Mentions légales']]" />

    <x-site.section container="narrow" inner-class="prose">
        <h2>1. Éditeur du site</h2>
        <p>
            <strong>Éditeur :</strong> Contempo luthiers<br>
            <strong>Responsable de publication :</strong> Giovanni Corazzol<br>
            <strong>Adresse de l'atelier :</strong> 32 rue de la République, 69002 Lyon<br>
            <strong>E-mail :</strong> <a href="mailto:atelier@contempoluthiers.fr">atelier@contempoluthiers.fr</a>
        </p>

        <h2>2. Hébergeur</h2>
        <p>
            Ce site est hébergé par :<br>
            <strong>Ligne Web Services (LWS)</strong><br>
            Adresse : 10 rue de Penthièvre, 75008 Paris, France.<br>
            SIREN / SIRET : 851 993 683.
        </p>

        <h2>3. Propriété intellectuelle</h2>
        <p>
            L'ensemble du contenu présent sur ce site, notamment les textes, photographies, images,
            logos et éléments graphiques, est protégé par le droit de la propriété intellectuelle.
            Toute reproduction, adaptation, diffusion ou réutilisation, partielle ou totale, est
            interdite sans autorisation écrite préalable.
        </p>

        <h2>4. Données personnelles</h2>
        <p>
            Les informations relatives aux données personnelles sont détaillées dans la page
            <a href="{{ route('legal.privacy') }}">Confidentialité</a>.
        </p>

        <h2>5. Responsabilité</h2>
        <p>
            Ce site présente l'activité de Contempo luthiers et les informations pratiques de l'atelier.
            Les informations publiées sont fournies avec soin, mais peuvent être modifiées ou corrigées
            à tout moment.
        </p>

        <h2>6. Liens externes</h2>
        <p>
            Le site peut contenir des liens vers d'autres sites. Contempo luthiers n'exerce pas de
            contrôle sur leur contenu, leur disponibilité ou leurs pratiques relatives aux données
            personnelles.
        </p>

        <h2>7. Modification des mentions légales</h2>
        <p>
            Ces mentions légales peuvent être modifiées à tout moment. La version en ligne actuelle
            prévaut sur toute version antérieure.
        </p>

        <x-site.back-link :href="route('home')" label="Retour à l'accueil" />
    </x-site.section>
@endsection
