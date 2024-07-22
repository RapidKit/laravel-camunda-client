<?php

// config for RapidKit/LaravelCamundaClient
return [
    'url' => env('CAMUNDA_URL', 'http://127.0.0.1:8080/engine-rest'),
    'user' => env('CAMUNDA_USER', 'demo'),
    'password' => env('CAMUNDA_PASSWORD', 'demo'),
    'tenant_id' => env('CAMUNDA_TENANT_ID', ''),
];
