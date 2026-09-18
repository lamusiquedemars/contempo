@extends('layouts.site', ['seoTitle' => $instrument->name, 'seoDescription' => $instrument->description])
@section('content')
@php
    $characteristics = collect($instrument->attributes)
        ->map(function ($attribute, $key) {
            if (! is_array($attribute)) {
                return ['label' => $key, 'value' => $attribute];
            }

            if (! ($attribute['is_public'] ?? false)) {
                return null;
            }

            $label = ($attribute['label'] ?? null) === '__other__'
                ? ($attribute['custom_label'] ?? null)
                : ($attribute['label'] ?? null);

            return filled($label) && filled($attribute['value'] ?? null)
                ? ['label' => $label, 'value' => $attribute['value']]
                : null;
        })
        ->filter();
    $photos = collect($instrument->media)->filter(fn ($media) => filled($media['url'] ?? null));
    $heroPhoto = $photos->first();
@endphp

<section class="instrument-detail__hero">
    <div class="container instrument-detail__hero-inner">
        <div class="instrument-detail__intro">
            <p class="eyebrow">{{ $instrument->family ?: 'Instrument' }}</p>
            <h1 class="instrument-detail__name">{{ $instrument->name }}</h1>
            @if ($instrument->maker)
                <p class="instrument-detail__maker">{{ $instrument->maker }}</p>
            @endif
            <p class="instrument-detail__price">{{ $instrument->displayPrice() }}</p>
            @if ($instrument->description)
                <p class="instrument-detail__description">{{ $instrument->description }}</p>
            @endif
            <a class="btn btn--primary" href="{{ route('contact') }}">Demander un essai</a>
        </div>
        @if ($heroPhoto)
            <figure class="instrument-detail__hero-media">
                <img src="{{ $heroPhoto['url'] }}" alt="{{ $heroPhoto['caption'] ?? $instrument->name }}">
            </figure>
        @endif
    </div>
</section>

@if ($characteristics->isNotEmpty())
    <x-site.section title="Caractéristiques" variant="muted">
        <dl class="instrument-detail__characteristics">
            @foreach ($characteristics as $characteristic)
                <div>
                    <dt>{{ $characteristic['label'] }}</dt>
                    <dd>{{ $characteristic['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    </x-site.section>
@endif

@if ($photos->isNotEmpty())
    <x-site.section title="Photos de l’instrument" intro="Cliquez sur une image pour l’agrandir et zoomer.">
        <div class="instrument-detail__gallery" data-lightbox>
            @foreach ($photos as $photo)
                <a
                    href="{{ $photo['url'] }}"
                    data-pswp-width="{{ $photo['width'] ?? 1200 }}"
                    data-pswp-height="{{ $photo['height'] ?? 1800 }}"
                    aria-label="Agrandir {{ $photo['caption'] ?? $instrument->name }}"
                >
                    <img src="{{ $photo['url'] }}" alt="{{ $photo['caption'] ?? $instrument->name }}" loading="lazy">
                    @if ($photo['caption'] ?? null)
                        <span>{{ $photo['caption'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </x-site.section>
@endif
@endsection
