<?php

return [
    'paths' => ['api/*', 'api/auth/*'],
    'allowed_methods' => ['POST', 'GET', 'DELETE', 'PUT', 'OPTIONS'],
    'allowed_origins' => ['https://admin.ajcci.org.au'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization', 'Accept'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
