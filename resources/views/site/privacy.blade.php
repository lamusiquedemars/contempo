@extends('layouts.site', [
    'seoTitle' => 'Confidentialité - Contempo luthiers',
    'seoDescription' => 'Politique de confidentialité du site Contempo luthiers.',
])

@section('content')
    <x-site.hero
        eyebrow="Légal"
        title="Confidentialité"
        subtitle="Données personnelles, relation client et droits des personnes."
    />

    <x-site.breadcrumb :items="[['label' => 'Confidentialité']]" />

    <x-site.section container="narrow" inner-class="prose">
        <h2>1. Responsable du traitement</h2>
        <p>
            Les données collectées sur ce site sont traitées par Contempo luthiers, sous la
            responsabilité de Giovanni Corazzol. Contact :
            <a href="mailto:atelier@contempoluthiers.fr">atelier@contempoluthiers.fr</a>.
        </p>

        <h2>2. Données collectées</h2>
        <p>
            Le site peut collecter les données transmises via le formulaire de contact : nom,
            adresse e-mail, téléphone, sujet et contenu du message. Le module relation client peut
            aussi conserver des informations de suivi utiles à la relation avec l'atelier.
        </p>

        <h2>3. Finalités</h2>
        <p>
            Ces données sont utilisées pour répondre aux demandes, préparer un rendez-vous, assurer
            le suivi des clients, informer les personnes concernées sur les horaires, les tarifs,
            les services de l'atelier ou les informations pratiques liées à la relation avec
            Contempo luthiers.
        </p>

        <h2>4. Bases légales</h2>
        <p>
            Le traitement repose, selon les cas, sur la demande de la personne lorsqu'elle contacte
            l'atelier, sur l'intérêt légitime de Contempo luthiers à assurer le suivi de sa relation
            client, ou sur le consentement lorsque celui-ci est nécessaire.
        </p>

        <h2>5. Messages d information et désinscription</h2>
        <p>
            Les messages envoyés depuis le module relation client sont destinés aux contacts concernés
            par l'activité de l'atelier. Chaque message ciblé comporte un lien permettant de ne plus
            recevoir ces communications. Les contacts désinscrits ne sont plus inclus dans les envois.
        </p>

        <h2>6. Durée de conservation</h2>
        <p>
            Les demandes de contact et informations de suivi sont conservées pendant la durée
            nécessaire à la relation avec l'atelier, puis peuvent être supprimées ou archivées
            lorsqu'elles ne sont plus utiles. Une personne peut demander la suppression de ses données
            à tout moment, sauf obligation légale contraire.
        </p>

        <h2>7. Destinataires</h2>
        <p>
            Les données sont destinées à Contempo luthiers. Elles peuvent être traitées par les
            prestataires techniques nécessaires au fonctionnement du site, notamment l'hébergeur,
            dans la limite de leurs missions.
        </p>

        <h2>8. Droits des personnes</h2>
        <p>
            Vous pouvez demander l'accès, la rectification, l'effacement ou l'opposition au traitement
            de vos données personnelles. Pour exercer ces droits, écrivez à
            <a href="mailto:atelier@contempoluthiers.fr">atelier@contempoluthiers.fr</a>. Vous pouvez
            également introduire une réclamation auprès de la CNIL.
        </p>

        <h2>9. Cookies</h2>
        <p>
            Le site n'utilise pas de cookies publicitaires ni de traceurs de mesure d'audience soumis
            au consentement. Les seuls cookies ou mécanismes techniques éventuels sont ceux strictement
            nécessaires au fonctionnement du site, à la sécurité ou à l administration.
        </p>

        <x-site.back-link :href="route('home')" label="Retour à l'accueil" />
    </x-site.section>
@endsection
