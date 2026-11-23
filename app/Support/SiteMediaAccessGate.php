<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maracuja\SiteCore\Media\Contracts\MediaAccessGate;

final class SiteMediaAccessGate implements MediaAccessGate
{
    public function allowsMediaManagement(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->canAccessContent('media');
    }
}
