<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ExternalTaskData extends Data
{
    public function __construct(
        public ?string $activityId,
        public ?string $activityInstanceId,
        public ?string $errorMessage,
        public ?string $errorDetails,
        public ?string $executionId,
        public string $id,
        public ?Carbon $lockExpirationTime,
        public string $processDefinitionId,
        public string $processDefinitionKey,
        public ?string $processDefinitionVersionTag,
        public string $processInstanceId,
        public ?int $retries,
        public bool $suspended,
        public ?string $workerId,
        public string $topicName,
        public ?string $tenantId,
        public ?array $variables,
        public int $priority,
        public ?string $businessKey = null,
        public ?array $extensionProperties = [],
        public ?string $createTime = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $data['lockExpirationTime'] = Carbon::parse($data['lockExpirationTime']);

        return new self(...$data);
    }
}
