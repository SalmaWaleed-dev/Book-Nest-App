<?php

// Third-party service credentials used by optional Laravel integrations.
return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    ],
];
