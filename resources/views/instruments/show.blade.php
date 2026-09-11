@extends('layouts.site', ['seoTitle' => $instrument->name, 'seoDescription' => $instrument->description])
@section('content')
<x-site.hero :eyebrow="$instrument->family" :title="$instrument->name" :subtitle="$instrument->maker" />
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
@endphp

<x-site.section :title="$instrument->price_label ?: 'Sur demande'">
    <div class="prose">
        <p>{{ $instrument->description }}</p>
        @if ($characteristics->isNotEmpty())
            <dl>
                @foreach ($characteristics as $characteristic)
                    <dt>{{ $characteristic['label'] }}</dt>
                    <dd>{{ $characteristic['value'] }}</dd>
                @endforeach
            </dl>
        @endif
    </div>
    <a class="btn btn--primary" href="{{ route('contact') }}">Demander un essai</a>
</x-site.section>

@if ($photos->isNotEmpty())
    <x-site.section title="Photos de l’instrument">
        <x-site.grid columns="2">
            @foreach ($photos as $photo)
                <x-site.card :title="$photo['caption'] ?? $instrument->name" :image="$photo['url']">
                    {{ $photo['caption'] ?? 'Vue de l’instrument' }}
                </x-site.card>
            @endforeach
        </x-site.grid>
    </x-site.section>
@endif
@endsection
