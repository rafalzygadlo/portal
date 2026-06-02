<?php

return [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o'), // gpt-4o, gpt-4-turbo, gpt-3.5-turbo
    ],

    'image_analysis' => [
        'enabled' => env('IMAGE_ANALYSIS_ENABLED', false),
        'provider' => env('IMAGE_ANALYSIS_PROVIDER', 'openai'), // openai, google, azure
        'mock' => env('IMAGE_ANALYSIS_MOCK', false), // Use mock data instead of real API
    ],
];
