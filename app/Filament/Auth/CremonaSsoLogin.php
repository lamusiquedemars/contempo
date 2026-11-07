<?php

namespace App\Filament\Auth;

use App\Models\User;
use App\Services\CremonaPortalAuthenticator;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class CremonaSsoLogin extends Login
{
    public function getHeading(): string|Htmlable|null
    {
        return 'Accès à votre espace de gestion';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Connectez-vous pour gérer le site Contempo et vos outils associés.';
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $user = User::query()->whereRaw('lower(email) = ?', [$email])->first();

        if (! $user instanceof User || ! $user->is_active || ! Hash::check((string) ($data['password'] ?? ''), $user->password)) {
            throw ValidationException::withMessages(['data.email' => 'Adresse e-mail ou mot de passe incorrect.']);
        }

        $identity = app(CremonaPortalAuthenticator::class)->authenticate($user);
        if ($identity === null) {
            throw ValidationException::withMessages(['data.email' => 'Adresse e-mail ou mot de passe incorrect.']);
        }

        Auth::login($user, (bool) ($data['remember'] ?? false));
        session()->regenerate();
        session()->put('cremona_portal.navigation', $identity['navigation']);

        return app(LoginResponse::class);
    }
}
