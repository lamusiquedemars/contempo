@extends('layouts.site', [
    'seoTitle' => $page?->seo_title ?? ('Contact - ' . $settings->site_name),
    'seoDescription' => $page?->seo_description ?? ('Contacter ' . $settings->site_name),
    'seoImage' => $page?->hero_image_path,
])

@section('content')
    <x-site.hero
        :title="$page?->hero_title ?? $page?->title ?? 'Contact'"
        :subtitle="$page?->hero_subtitle ?? $page?->excerpt ?? 'Un formulaire simple pour envoyer un message.'"
        :image="\App\Support\MediaFiles::url($page?->hero_image_path) ?? '/media/location.jpg'"
    />

    <x-site.section inner-class="contact-layout">
        @if (session('status'))
            <p class="notice">{{ session('status') }}</p>
        @endif

        <div class="prose">
            <p>Je suis heureux de vous accueillir à l'atelier Contempo Luthiers, un lieu de travail ouvert et informel où je prends soin de vos instruments à cordes frottées et de vos archets, tout en restant à l'écoute de vos besoins et de vos questions.</p>
            <p>Quel que soit votre niveau, que vous soyez musicien ou que vous accompagniez un enfant qui souhaite découvrir la musique, n'hésitez pas à passer me voir.</p>
        </div>

        <x-site.grid columns="3">
            <x-site.card title="Nous écrire" kicker="Contact">
                +33 (0)4 78 42 40 65<br>
                atelier@contempoluthiers.fr<br>
                instagram.com/contempo_luthiers
            </x-site.card>
            <x-site.card title="Nous rejoindre" kicker="Atelier">
                32 rue de la République<br>
                69002 Lyon
            </x-site.card>
            <x-site.card title="Comment venir" kicker="Accès">
                Métro Cordeliers, bus et tram à proximité. Parkings Cordeliers ou République.
            </x-site.card>
        </x-site.grid>

        <div class="prose">
            <h3>Horaires & rendez-vous</h3>
            <p>Merci de me contacter à l'avance pour fixer votre visite : cela me permettra de vous accueillir dans les meilleures conditions, surtout s'il s'agit d'un essai d'instrument.</p>
            <p>Un rendez-vous n'est pas nécessaire pour les petits achats d'accessoires ou de cordes. N'hésitez pas à venir avec votre instrument si vous rencontrez des difficultés d'accordage ou si vous observez une usure ou une casse anormale des cordes.</p>
            <table class="table table--simple">
                <tbody>
                    <tr><th>Jour</th><th>Horaire</th></tr>
                    <tr><td>Lundi</td><td>9h30 - 12h30 / 14h - 19h</td></tr>
                    <tr><td>Mardi</td><td>14h - 19h</td></tr>
                    <tr><td>Mercredi</td><td>9h30 - 12h30 / 14h - 19h</td></tr>
                    <tr><td>Jeudi</td><td>9h30 - 12h30</td></tr>
                    <tr><td>Vendredi</td><td>14h - 19h</td></tr>
                    <tr><td>Samedi</td><td>9h30 - 12h30 / 14h - 19h</td></tr>
                    <tr><td>Dimanche</td><td>Fermé</td></tr>
                </tbody>
            </table>
        </div>

        <form method="post" action="{{ route('contact.store') }}" class="contact-form" data-form>
            @csrf
            <input type="text" name="website" value="" autocomplète="off" tabindex="-1" style="position:absolute; left:-9999px; top:auto; width:1px; height:1px; overflow:hidden;">

            @if ($settings->contact_form_show_name)
                <label>
                    Nom
                    <input name="name" value="{{ old('name') }}" required>
                    @error('name') <small>{{ $message }}</small> @enderror
                </label>
            @endif

            <label>
                Email
                <input name="email" type="email" value="{{ old('email') }}" required>
                @error('email') <small>{{ $message }}</small> @enderror
            </label>

            @if ($settings->contact_form_show_phone)
                <label>
                    Téléphone
                    <input name="phone" value="{{ old('phone') }}">
                </label>
            @endif

            @if ($settings->contact_form_show_subject)
                <label>
                    Sujet
                    <input name="subject" value="{{ old('subject') }}">
                </label>
            @endif

            <label class="full">
                Message
                <textarea name="message" rows="7" required>{{ old('message') }}</textarea>
                @error('message') <small>{{ $message }}</small> @enderror
            </label>
            <p class="full text-muted">
                Les informations envoyées via ce formulaire sont utilisées pour répondre à votre demande
                et assurer le suivi de la relation avec l'atelier. Vous pouvez exercer vos droits à
                <a href="mailto:atelier@contempoluthiers.fr">atelier@contempoluthiers.fr</a>.
                Plus d'informations dans la
                <a href="{{ route('pages.show', 'confidentialite') }}">politique de confidentialité</a>.
            </p>
            <x-site.button type="submit">Envoyer</x-site.button>
        </form>
    </x-site.section>
@endsection
