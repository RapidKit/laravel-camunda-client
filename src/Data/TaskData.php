<?php

namespace RapidKit\LaravelCamundaClient\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class TaskData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $assignee,
        public Carbon $created,
        public ?string $due,
        public ?string $followUp,
        public ?Carbon $lastUpdated,
        public ?string $delegationState,
        public ?string $description,
        public string $executionId,
        public ?string $owner,
        public ?string $parentTaskId,
        public string $priority,
        public string $processDefinitionId,
        public string $processInstanceId,
        public string $taskDefinitionKey,
        public ?string $caseExecutionId,
        public ?string $caseInstanceId,
        public ?string $caseDefinitionId,
        public bool $suspended,
        public ?string $formKey = null,
        public ?array $camundaFormRef = null,
        public ?string $tenantId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        $data['created'] = Carbon::parse($data['created']);

        return new self(...$data);
    }
}
