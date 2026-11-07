<?php

return [
    'enabled' => (bool) env('CREMONA_SSO_ENABLED', false),
    'issuer' => rtrim((string) env('CREMONA_SSO_ISSUER', 'https://cremona.maracujadigital.fr'), '/'),
    'client_id' => env('CREMONA_SSO_CLIENT_ID'),
    'shared_secret' => env('CREMONA_SSO_SHARED_SECRET'),
];
