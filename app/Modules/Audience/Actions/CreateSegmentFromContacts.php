<?php

namespace App\Modules\Audience\Actions;

use App\Modules\Audience\Models\AudienceSegment;
use Illuminate\Support\Collection;

class CreateSegmentFromContacts
{
    /**
     * @param  Collection<int, int>  $contactIds
     */
    public static function run(string $name, ?string $description, Collection $contactIds): AudienceSegment
    {
        $segment = AudienceSegment::query()->create([
            'name' => trim($name),
            'description' => $description !== null ? trim($description) : null,
        ]);

        $segment->contacts()->syncWithoutDetaching(
            $contactIds
                ->filter()
                ->map(fn (int $id) => (int) $id)
                ->unique()
                ->values()
                ->all()
        );

        return $segment;
    }
}
