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
        public ?Carbon $createTime,
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
    ) {
    }

    public static function fromArray(array $data): self
    {
        $data['createTime'] = Carbon::parse($data['createTime']);
        $data['lockExpirationTime'] = Carbon::parse($data['lockExpirationTime']);

        return new self(...$data);
    }
}
