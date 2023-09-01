<?php

// config for BeyondCRUD/LaravelCamundaClient
return [
    'url' => env('CAMUNDA_CLIENT_URL', 'https://localhost:8080/engine-rest'),
    'user' => env('CAMUNDA_CLIENT_USER', 'demo'),
    'password' => env('CAMUNDA_CLIENT_PASSWORD', 'demo'),
    'tenant_id' => env('CAMUNDA_CLIENT_TENANT_ID', ''),
];
