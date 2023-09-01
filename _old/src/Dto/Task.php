<?php

namespace Laravolt\Camunda\Dto;

use Illuminate\Support\Carbon;
use Laravolt\Camunda\Dto\Casters\CarbonCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class Task extends DataTransferObject
{
    public string $id;

    public string $name;

    public ?string $assignee;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public ?Carbon $lastUpdated;

    public ?string $due;

    public ?string $followUp;

    public ?string $delegationState;

    public ?string $description;

    public string $executionId;

    public ?string $owner;

    public ?string $parentTaskId;

    public string $priority;

    public string $processDefinitionId;

    public string $processInstanceId;

    public string $taskDefinitionKey;

    public ?string $caseExecutionId;

    public ?string $caseInstanceId;

    public ?string $caseDefinitionId;

    public bool $suspended;

    public ?string $formKey;

    public ?array $camundaFormRef;

    public ?string $tenantId;
}
