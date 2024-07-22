# This is my package laravel-camunda-client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beyondcrud/laravel-camunda-client.svg?style=flat-square)](https://packagist.org/packages/beyondcrud/laravel-camunda-client)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/beyondcrud/laravel-camunda-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/beyondcrud/laravel-camunda-client/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Static Analysis Action Status](https://img.shields.io/github/actions/workflow/status/beyondcrud/laravel-camunda-client/phpstan.yml?branch=main&label=static%20analysis&style=flat-square)](https://github.com/beyondcrud/laravel-camunda-client/actions?query=workflow%3Aphpstan+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/beyondcrud/laravel-camunda-client/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/beyondcrud/laravel-camunda-client/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/beyondcrud/laravel-camunda-client.svg?style=flat-square)](https://packagist.org/packages/beyondcrud/laravel-camunda-client)

Introducing a convenient Laravel HTTP client wrapper designed to streamline your interactions with the Camunda REST API. This specialized tool simplifies the process of connecting your Laravel application to Camunda, allowing you to effortlessly send requests and retrieve data. With this wrapper, you can efficiently integrate Camunda's powerful workflow automation capabilities into your Laravel projects, making it easier than ever to orchestrate complex business processes and manage tasks seamlessly. Say goodbye to the hassles of manual API calls and hello to a smoother, more efficient workflow integration with Camunda.

## Installation

You can install the package via composer:

```bash
composer require beyondcrud/laravel-camunda-client
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag='laravel-camunda-client-config'
```

This is the contents of the published config file:

```php
return [
    'url' => env('CAMUNDA_URL', 'http://127.0.0.1:8080/engine-rest'),
    'user' => env('CAMUNDA_USER', 'demo'),
    'password' => env('CAMUNDA_PASSWORD', 'demo'),
    'tenant_id' => env('CAMUNDA_TENANT_ID', ''),
];
```

## Usage

### Process Definition

```php
use BeyondCRUD\LaravelCamundaClient\Http\ProcessDefinitionClient;

$variables = ['title' => ['value' => 'Sample Title', 'type' => 'string']];

// Start new process instance
$instance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables);

// Start new process instance with some business key
$instance = ProcessDefinitionClient::start(key: 'process_1', variables: $variables, businessKey: 'somekey');

// Get BPMN definition in XML format
ProcessDefinitionClient::xml(key: 'process_1');
ProcessDefinitionClient::xml(id: 'process_1:xxxx');

// Get all definition
ProcessDefinitionClient::get();

// Get definitions based on some parameters
$params = ['latestVersion' => true];
ProcessDefinitionClient::get($params);
```

Reference:

- [Camunda API: Process Definition](https://docs.camunda.org/manual/latest/reference/rest/process-definition)

### Process Instance

```php
use BeyondCRUD\LaravelCamundaClient\Http\ProcessInstanceClient;

// Find by ID
$processInstance = ProcessInstanceClient::find(id: 'some-id');

// Get all instances
ProcessInstanceClient::get();

// Get instances based on some parameters
$params = ['businessKeyLike' => 'somekey'];
ProcessInstanceClient::get($params);

ProcessInstanceClient::variables(id: 'some-id');
ProcessInstanceClient::delete(id: 'some-id');
```

### Message Start Event

```php
use BeyondCRUD\LaravelCamundaClient\Http\MessageEventClient;

MessageEventClient::start(messageName: 'testing',  businessKey: 'businessKey');
```

### Message Event

```php
use Laravolt\Camunda\Http\MessageEventClient;
// Start processinstance with message event
// Required
// messageName : message event name
// businessKey : Busniess key for process instance

// Rerturn Process insntance from message event

MessageEventClient::start(messageName: "testing",  businessKey: "businessKey")


$vars = ['title' => ['type' => 'String', 'value' => 'Sample Title']];
MessageEventClient::start(messageName: 'testing',  businessKey: 'businessKey', variables: $vars);
```

### Task

```php
use BeyondCRUD\LaravelCamundaClient\Http\TaskClient;

$task = TaskClient::find(id: 'task-id');
$tasks = TaskClient::getByProcessInstanceId(id: 'process-instance-id');
$tasks = TaskClient::getByProcessInstanceIds(ids: 'arrayof-process-instance-ids');
TaskClient::submit(id: 'task-id', variables: ['name' => ['value' => 'Foo', 'type' => 'String']]); // will return true or false
$variables = TaskClient::submitAndReturnVariables(id: 'task-id', variables: ['name' => ['value' => 'Foo', 'type' => 'String']]) // will return array of variable

// Claim a Task
$tasks = TaskClient::claim($task_id,  $user_id);
// Unclaim a Task
$tasks = TaskClient::unclaim($task_id);
// Assign a Task
$tasks = TaskClient::assign($task_id,  $user_id);
```

### External Task

```php
use BeyondCRUD\LaravelCamundaClient\Http\ExternalTaskClient;

$topics = [['topicName' => 'pdf', 'lockDuration' => 600_000]];
$externalTasks = ExternalTaskClient::fetchAndLock('worker1', $topics);
foreach ($externalTasks as $externalTask) {
    // do something with $externalTask
    // Mark as complete after finished
    ExternalTaskClient::complete($externalTask->id);
}

// Release some task
ExternalTaskClient::unlock($task->id)

// Get task locked
$externalTaskLocked = ExternalTaskClient::getTaskLocked();
```

### Consume External Task

Create a new job to consume external task via `php artisan make:job <JobName>` and modify the skeleton:

```php
use BeyondCRUD\LaravelCamundaClient\Data\ExternalTaskData;
use BeyondCRUD\LaravelCamundaClient\Http\ExternalTaskClient;

public function __construct(
    public string $workerId,
    public ExternalTaskData $task
) {
}

public function handle()
{
    // Do something with $this->task, e.g: get the variables and generate PDF
    $variables = \BeyondCRUD\LaravelCamundaClient\Http\ProcessInstanceClient::variables($this->task->processDefinitionId);
    // PdfService::generate()

    // Complete the task
    $status = ExternalTaskClient::complete($this->task->id, $this->workerId);
}
```

Subscribe to some topic:

```php
// AppServiceProvider.php
use BeyondCRUD\LaravelCamundaClient\Http\ExternalTaskClient;

public function boot()
{
    ExternalTaskClient::subscribe('pdf', GeneratePdf::class);
}
```

Register the scheduler:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('camunda:consume-external-task --workerId=worker1')->everyMinute();
}
```

If you need shorter pooling time (sub-minute frequency), please check [Laravel Short Schedule](https://github.com/spatie/laravel-short-schedule).

References:

- https://laravel.com/docs/master/scheduling
- https://laravel.com/docs/master/queues
- https://github.com/spatie/laravel-short-schedule

### Task History (Completed Task)

```php
use BeyondCRUD\LaravelCamundaClient\Http\TaskHistoryClient;

$completedTask = TaskHistoryClient::find(id: 'task-id');
$completedTasks = TaskHistoryClient::getByProcessInstanceId(id: 'process-instance-id');
```

### Deployment

```php
use BeyondCRUD\LaravelCamundaClient\Http\DeploymentClient;

// Deploy bpmn file(s)
DeploymentClient::create('test-deploy', '/path/to/file.bpmn');
DeploymentClient::create('test-deploy', ['/path/to/file1.bpmn', '/path/to/file2.bpmn']);

// Get deployment list
DeploymentClient::get();

// Find detailed info about some deployment
DeploymentClient::find($id);

// Truncate (delete all) deployments
$cascade = true;
DeploymentClient::truncate($cascade);

// Delete single deployment
DeploymentClient::delete(id: 'test-deploy', cascade: $cascade);
```

### Raw Endpoint

You can utilize `BeyondCRUD\LaravelCamundaClient\CamundaClient` to call any Camunda REST endpoint.

```php
use BeyondCRUD\LaravelCamundaClient\CamundaClient;

$response = CamundaClient::make()->get('version');
echo $response->status(); // 200
echo $response->object(); // sdtClass
echo $response->json(); // array, something like ['version' => '7.14.0']
```

> `CamundaClient::make()` is a wrapper for [Laravel HTTP Client](https://laravel.com/docs/master/http-client) with base URL already set based on your Camunda services configuration. Take a look at the documentation for more information.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [rama](https://github.com/ramaID)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## References

- [Deployment](https://docs.camunda.org/rest/camunda-bpm-platform/7.19/#tag/Deployment)
- [External Task](https://docs.camunda.org/rest/camunda-bpm-platform/7.19/#tag/External-Task)
- [Message](https://docs.camunda.org/rest/camunda-bpm-platform/7.19/#tag/Message)
- [Process Definition](https://docs.camunda.org/rest/camunda-bpm-platform/7.19/#tag/Process-Definition)
- [Task](https://docs.camunda.org/rest/camunda-bpm-platform/7.19/#tag/Task)
- [Task History](https://docs.camunda.org/rest/camunda-bpm-platform/7.19/#tag/Historic-Task-Instance)
