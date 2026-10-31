<?php

namespace App\Support;

use App\Models\User;
use App\Modules\Articles\Models\Article;
use App\Modules\Audience\Models\AudienceContact;
use App\Modules\Audience\Models\AudienceSegment;
use App\Modules\Audience\Models\SegmentMessage;
use App\Modules\ContentSlots\Models\ContentSlot;
use App\Modules\Events\Models\Event;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Inquiries\Models\Inquiry;
use App\Modules\Media\Models\MediaAsset;
use App\Modules\News\Models\NewsPost;
use App\Modules\Notices\Models\SiteNotice;
use App\Modules\Pages\Models\Page;
use App\Modules\Venues\Models\Venue;
use Illuminate\Database\Eloquent\Model;

final class ContentPermissions
{
    public static function labels(): array
    {
        return [
            'pages' => 'Pages', 'notices' => 'Annonces courtes',
            'content_slots' => 'Contenus courts', 'news' => 'Actualités',
            'articles' => 'Articles', 'media' => 'Médias', 'gallery' => 'Galeries',
            'events' => 'Événements', 'venues' => 'Lieux',
            'inquiries' => 'Demandes reçues', 'audience' => 'Audience et campagnes',
        ];
    }

    public static function labelsForForm(): array
    {
        $labels = self::labels();
        $modules = [];

        foreach ($labels as $key => $label) {
            // Older sites provide media permanently without a module flag.
            if ($key === 'media' && ! isset($modules[$key]) && config('maracuja.modules.media') === null) {
                continue;
            }

            if (! Modules::enabled($modules[$key] ?? $key)) {
                $labels[$key] = $label.' (module inactif)';
            }
        }

        return $labels;
    }

    public static function allows(User $user, ?string $model): bool
    {
        $permission = match ($model) {
            Page::class => 'pages', SiteNotice::class => 'notices', ContentSlot::class => 'content_slots',
            NewsPost::class => 'news', Article::class => 'articles', MediaAsset::class => 'media',
            Gallery::class => 'gallery', Event::class => 'events', Venue::class => 'venues',
            Inquiry::class => 'inquiries', AudienceContact::class, AudienceSegment::class, SegmentMessage::class => 'audience',
            default => null,
        };

        // These screens contain custom write actions that are not yet
        // available in a read-only form. Never expose them to a reader.
        if (! $user->isAdministrator() && $user->role === User::ROLE_VIEWER && in_array($permission, ['inquiries', 'audience', 'conversations'], true)) {
            return false;
        }

        if ($permission !== null) {
            return $user->canAccessContent($permission);
        }

        return $user->isAdministrator() || $user->content_permissions === null
            || ! $model || ! is_a($model, Model::class, true);
    }
}
