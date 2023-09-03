<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class TaskHistoryData extends Data
{
    public function __construct(
        public string $id,
        public string $processDefinitionKey,
        public string $processDefinitionId,
        public string $processInstanceId,
        public string $executionId,
        public ?string $caseDefinitionKey,
        public ?string $caseDefinitionId,
        public ?string $caseInstanceId,
        public ?string $caseExecutionId,
        public string $activityInstanceId,
        public string $name,
        public ?string $description,
        public ?string $deleteReason,
        public ?string $owner,
        public ?string $assignee,
        public Carbon $startTime,
        public ?Carbon $endTime,
        public ?int $duration,
        public string $taskDefinitionKey,
        public int $priority,
        public ?Carbon $due,
        public ?string $parentTaskId,
        public ?Carbon $followUp,
        public ?string $tenantId,
        public ?Carbon $removalTime,
        public string $rootProcessInstanceId,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $data['startTime'] = Carbon::parse($data['startTime']);

        if (isset($data['endTime'])) {
            $data['endTime'] = Carbon::parse($data['endTime']);
        }

        if (isset($data['due'])) {
            $data['due'] = Carbon::parse($data['due']);
        }

        if (isset($data['followUp'])) {
            $data['followUp'] = Carbon::parse($data['followUp']);
        }

        if (isset($data['removalTime'])) {
            $data['removalTime'] = Carbon::parse($data['removalTime']);
        }

        return new self(...$data);
    }
}
