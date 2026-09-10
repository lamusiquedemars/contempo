@extends('layouts.site', ['seoTitle' => $instrument->name, 'seoDescription' => $instrument->description])
@section('content')
<x-site.hero :eyebrow="$instrument->family" :title="$instrument->name" :subtitle="$instrument->maker" />
<x-site.section :title="$instrument->price_label ?: 'Sur demande'"><div class="prose"><p>{{ $instrument->description }}</p>@if($instrument->attributes)<dl>@foreach($instrument->attributes as $key => $value)<dt>{{ $key }}</dt><dd>{{ $value }}</dd>@endforeach</dl>@endif</div><a class="btn btn--primary" href="{{ route('contact') }}">Demander un essai</a></x-site.section>
@endsection
