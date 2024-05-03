<?php

namespace RapidKit\LaravelCamundaClient\Data;

use Spatie\LaravelData\Data;

class TenantData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
