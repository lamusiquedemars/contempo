@extends('layouts.site', [
    'seoTitle' => 'Actualites - ' . $settings->site_name,
    'seoDescription' => 'Dernieres actualites publiees.',
])

@section('content')
    <x-site.hero title="Actualites" subtitle="Les contenus recurrents publies depuis l admin." />

    <x-site.breadcrumb :items="[
        ['label' => 'Actualites'],
    ]" />

    <x-site.section>
        <x-site.grid columns="3" class="news-list">
            @foreach ($posts as $post)
                <x-site.card :title="$post->title" :url="route('news.show', $post->slug)">
                    {{ $post->excerpt }}
                </x-site.card>
            @endforeach
        </x-site.grid>
        {{ $posts->links() }}
    </x-site.section>
@endsection
