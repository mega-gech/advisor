<?php

return [
    'name' => 'AdvisorHub',
    'url' => '', // e.g. http://localhost/advisor/public
    'timezone' => 'Africa/Addis_Ababa',

    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'advisorhub',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
];
