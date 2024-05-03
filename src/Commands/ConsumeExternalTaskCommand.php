<?php

namespace RapidKit\LaravelCamundaClient\Commands;

use Illuminate\Console\Command;
use RapidKit\LaravelCamundaClient\Http\ExternalTaskClient;

class ConsumeExternalTaskCommand extends Command
{
    protected $signature = 'camunda:consume-external-task {--workerId=} {--topic=*}';

    protected $description = '
        Consume Camunda external task by topic
        {--workerId : A worker identifier}
        {--topic : A Camunda external task topic name to be fetched}
    ';

    public function handle(): int
    {
        $subscribers = ExternalTaskClient::subscribers();
        $topics = [];
        $summary = [];
        /** @var string */
        $workerId = $this->option('workerId');

        try {
            foreach ($subscribers as $topicName => $subscriber) {
                /** @var array */
                $array = $subscriber;
                $topics[$topicName] = collect($array)->only(['topicName', 'lockDuration'])->toArray();
                $summary[$topicName] = [$topicName, $subscriber['job'] ?? '-', 0];
            }
            $externalTasks = ExternalTaskClient::fetchAndLock($workerId, array_values($topics));

            foreach ($externalTasks as $task) {
                $jobClass = $subscribers[$task->topicName]['job'] ?? false;
                if ($jobClass) {
                    $jobClass::dispatch($workerId, $task);
                    $summary[$task->topicName][2]++;
                }
            }

            $this->table(['topic', 'Job', 'Job Dispatched'], $summary);
        } catch (\Throwable $th) {
            report($th);
        }

        return self::SUCCESS;
    }
}
