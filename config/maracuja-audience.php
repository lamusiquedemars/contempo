<?php

declare(strict_types=1);

return [
    // Contempo conserve ses tables historiques ; une migration additive les
    // aligne avec le socle sans jamais les recréer.
    'load_migrations' => env('MARACUJA_AUDIENCE_LOAD_MIGRATIONS', false),
];
