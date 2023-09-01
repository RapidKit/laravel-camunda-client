<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class TaskData extends Data
{
    public function __construct(
        public string $name,
        public ?string $assignee,
        public Carbon $created,
        public ?Carbon $lastUpdated,
        public ?string $due,
        public ?string $followUp,
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
    ) {
    }
}
