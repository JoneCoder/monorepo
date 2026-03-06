<?php

declare(strict_types=1);

use Spiral\RoadRunner\Environment\Mode;
use Spiral\RoadRunnerLaravel\Grpc\GrpcWorker;
use Spiral\RoadRunnerLaravel\Http\HttpWorker;
use Spiral\RoadRunnerLaravel\Queue\QueueWorker;
use Spiral\RoadRunnerLaravel\Temporal\TemporalWorker;
use Temporal\Worker\WorkerFactoryInterface as TemporalWorkerFactoryInterface;

return [
    'cache' => [
        'storage' => 'cache',
    ],

    'grpc' => [
        'services' => [
            // Simple service configuration
            \App\GRPC\EchoServiceInterface::class => \App\GRPC\EchoService::class,

            // Service with specific interceptors
            \App\GRPC\UserServiceInterface::class => [
                'service' => \App\GRPC\UserService::class,
                'interceptors' => [
                    \App\GRPC\Interceptors\ValidationInterceptor::class,
                    \App\GRPC\Interceptors\CacheInterceptor::class,
                ],
            ],
        ],
        // Global interceptors - applied to all services
        'interceptors' => [
            \App\GRPC\Interceptors\LoggingInterceptor::class,
            \App\GRPC\Interceptors\AuthenticationInterceptor::class,
        ],
    ],

    'temporal' => [
        'address' => env('TEMPORAL_ADDRESS', '127.0.0.1:7233'),
        'defaultWorker' => env('TEMPORAL_TASK_QUEUE', TemporalWorkerFactoryInterface::DEFAULT_TASK_QUEUE),
        'workers' => [],
        'declarations' => [
            // 'App\Temporal\GreeterWorkflow'
        ],
    ],

    'workers' => [
        Mode::MODE_HTTP => HttpWorker::class,
        Mode::MODE_JOBS => QueueWorker::class,
        Mode::MODE_GRPC => GrpcWorker::class,
        Mode::MODE_TEMPORAL => TemporalWorker::class,
    ],
];
