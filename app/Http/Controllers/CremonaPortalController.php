<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CremonaPortalAuthenticator;
use App\Support\CremonaPortal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class CremonaPortalController extends Controller
{
    public function __invoke(string $item): RedirectResponse
    {
        abort_unless(CremonaPortal::has($item), Response::HTTP_NOT_FOUND);
        $user = Auth::user();
        abort_unless($user instanceof User, Response::HTTP_UNAUTHORIZED);
        $url = app(CremonaPortalAuthenticator::class)->sessionLink($user, $item);
        abort_unless($url !== null, Response::HTTP_FORBIDDEN);

        return redirect()->away($url);
    }
}
