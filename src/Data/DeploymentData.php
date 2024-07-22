<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class DeploymentData extends Data
{
    public function __construct(
        public string $id,
        public ?string $tenantId,
        public ?string $name,
        public ?string $source,
        public Carbon $deploymentTime,
        public ?array $processDefinitions = [],
        public ?array $links = [],
        public ?array $deployedProcessDefinitions = [],
        public ?array $deployedCaseDefinitions = [],
        public ?array $deployedDecisionDefinitions = [],
        public ?array $deployedDecisionRequirementsDefinitions = [],
    ) {}

    public static function fromResponse(Response $response): self
    {
        /** @var array[string, mixed] */
        $payloads = $response->json();

        $payloads['deploymentTime'] = Carbon::parse($payloads['deploymentTime']);

        if (! isset($payloads['processDefinitions'])) {
            $payloads['processDefinitions'] = [];
        }

        return new self(...$payloads);
    }

    public static function fromArray(array $data): self
    {
        $data['deploymentTime'] = Carbon::parse($data['deploymentTime']);

        if (! isset($data['processDefinitions'])) {
            $data['processDefinitions'] = [];
        }

        return new self(...$data);
    }
}
