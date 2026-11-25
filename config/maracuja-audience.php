<?php

declare(strict_types=1);

return [
    // Contempo possède déjà les tables Audience historiques. La migration de
    // compatibilité applicative les aligne sans jamais les recréer.
    'load_migrations' => env('MARACUJA_AUDIENCE_LOAD_MIGRATIONS', false),
];
