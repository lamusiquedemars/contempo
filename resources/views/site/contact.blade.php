@extends('layouts.site', [
    'seoTitle' => 'Contact - ' . $settings->site_name,
    'seoDescription' => 'Contacter ' . $settings->site_name,
])

@section('content')
    <x-site.hero title="Contact" subtitle="Un formulaire simple, stocke en admin et envoye par email." />

    <x-site.section inner-class="contact-layout">
        @if (session('status'))
            <p class="notice">{{ session('status') }}</p>
        @endif

        <form method="post" action="{{ route('contact.store') }}" class="contact-form" data-form>
            @csrf
            <label>
                Nom
                <input name="name" value="{{ old('name') }}" required>
                @error('name') <small>{{ $message }}</small> @enderror
            </label>
            <label>
                Email
                <input name="email" type="email" value="{{ old('email') }}" required>
                @error('email') <small>{{ $message }}</small> @enderror
            </label>
            <label>
                Telephone
                <input name="phone" value="{{ old('phone') }}">
            </label>
            <label>
                Sujet
                <input name="subject" value="{{ old('subject') }}">
            </label>
            <label class="full">
                Message
                <textarea name="message" rows="7" required>{{ old('message') }}</textarea>
                @error('message') <small>{{ $message }}</small> @enderror
            </label>
            <x-site.button type="submit">Envoyer</x-site.button>
        </form>
    </x-site.section>
@endsection
