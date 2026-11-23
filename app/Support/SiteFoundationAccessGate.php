<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maracuja\SiteCore\Foundation\Contracts\FoundationAccessGate;

final class SiteFoundationAccessGate implements FoundationAccessGate
{
    public function allows(string $ability): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->isAdministrator()) {
            return true;
        }

        $permission = match ($ability) {
            'media.manage' => 'media',
            'gallery.manage' => 'gallery',
            default => null,
        };

        return $permission !== null && $user->canEditContent() && $user->canAccessContent($permission);
    }
}
