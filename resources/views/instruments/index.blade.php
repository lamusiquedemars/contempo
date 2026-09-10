@extends('layouts.site', ['seoTitle' => 'Instruments'])
@section('content')
<x-site.hero eyebrow="Catalogue" title="Instruments" subtitle="Une sélection disponible à l’essai." />
<x-site.section title="Instruments disponibles"><x-site.grid columns="3">@forelse($instruments as $instrument)<x-site.card :title="$instrument->name" :kicker="$instrument->family"><p>{{ $instrument->maker }}</p><p>{{ $instrument->price_label ?: 'Sur demande' }}</p><a class="btn btn--secondary" href="{{ route('instruments.show', $instrument->slug) }}">Découvrir</a></x-site.card>@empty<p>Aucun instrument n’est actuellement présenté.</p>@endforelse</x-site.grid></x-site.section>
@endsection
