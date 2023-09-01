<?php

namespace Laravolt\Camunda\Dto;

use Illuminate\Support\Carbon;
use Laravolt\Camunda\Dto\Casters\CarbonCaster;
use Laravolt\Camunda\Dto\Casters\VariablesCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class ExternalTask extends DataTransferObject
{
    public string $id;

    public string $topicName;

    public ?string $workerId;

    public ?string $activityId;

    public ?string $activityInstanceId;

    public ?string $errorMessage;

    public ?string $errorDetails;

    public ?string $executionId;

    public ?string $businessKey;

    #[CastWith(CarbonCaster::class)]
    public ?Carbon $lockExpirationTime;

    public string $processDefinitionId;

    public string $processDefinitionKey;

    public ?string $processDefinitionVersionTag;

    public string $processInstanceId;

    public ?string $tenantId;

    public ?int $retries;

    public int $priority;

    /** @var \Laravolt\Camunda\Dto\Variable[] */
    #[CastWith(VariablesCaster::class, Variable::class)]
    public ?array $variables;

    public bool $suspended;

    public ?array $extensionProperties;
}
