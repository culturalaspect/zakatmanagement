<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wheat Distribution API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to the Wheat Distribution Application API
    | to fetch verified member information.
    |
    */
    
    'base_url' => env('WHEAT_API_BASE_URL', 'http://localhost:8001/api'),
    
    'username' => env('WHEAT_API_USERNAME', ''),
    
    'password' => env('WHEAT_API_PASSWORD', ''),
    
    'token' => env('WHEAT_API_TOKEN', ''),
    
    'timeout' => env('WHEAT_API_TIMEOUT', 30),
];


