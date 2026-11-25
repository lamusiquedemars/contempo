<?php

declare(strict_types=1);

return [
    // Les réglages et événements Brevo existants sont conservés et alignés par
    // une migration additive propre à Contempo.
    'load_migrations' => env('MARACUJA_BREVO_LOAD_MIGRATIONS', false),
];
