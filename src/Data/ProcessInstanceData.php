<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Spatie\LaravelData\Data;

class ProcessInstanceData extends Data
{
    public function __construct(
        public array $links,
        public string $id,
        public string $definitionId,
        public ?string $businessKey,
        public ?string $caseInstanceId,
        public bool $ended,
        public bool $suspended,
        public ?string $tenantId = null,
        public ?array $variables = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }
}
