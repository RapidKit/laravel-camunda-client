<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ProcessInstanceHistoryData extends Data
{
    public function __construct(
        public string $id,
        public ?string $businessKey,
        public string $processDefinitionId,
        public string $processDefinitionKey,
        public ?string $processDefinitionName,
        public int $processDefinitionVersion,
        public Carbon $startTime,
        public ?Carbon $endTime,
        public ?Carbon $removalTime,
        public ?int $durationInMillis,
        public ?string $startUserId,
        public string $startActivityId,
        public ?string $deleteReason,
        public ?string $rootProcessInstanceId,
        public ?string $superProcessInstanceId,
        public ?string $superCaseInstanceId,
        public ?string $caseInstanceId,
        public ?string $tenantId,
        public string $state,
    ) {}

    public static function fromArray(array $data): self
    {
        $data['startTime'] = Carbon::parse($data['startTime']);
        $data['endTime'] = isset($data['endTime']) ? Carbon::parse($data['endTime']) : null;
        $data['removalTime'] = isset($data['removalTime']) ? Carbon::parse($data['removalTime']) : null;

        return new self(...$data);
    }
}
