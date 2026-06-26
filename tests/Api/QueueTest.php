<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class QueueTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->queues()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/queues');
    }

    public function testAllForVhost(): void
    {
        $this->client()->queues()->all('/');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/queues/%2F');
    }

    public function testGet(): void
    {
        $this->client()->queues()->get('/', 'my-queue');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/queues/%2F/my-queue');
    }

    public function testCreate(): void
    {
        $this->client()->queues()->create('/', 'my-queue', ['durable' => true]);

        $this->assertSentRequestParts('PUT', '/api/queues/%2F/my-queue');
        self::assertSame(['durable' => true], $this->lastRequestBody());
    }

    public function testDelete(): void
    {
        $this->client()->queues()->delete('/', 'my-queue');

        $this->assertSentRequestParts('DELETE', '/api/queues/%2F/my-queue');
    }

    public function testDeleteWithConditions(): void
    {
        $this->client()->queues()->delete('/', 'my-queue', true, true);

        $this->assertSentRequestParts('DELETE', '/api/queues/%2F/my-queue', 'if-empty=true&if-unused=true');
    }

    public function testBindings(): void
    {
        $this->client()->queues()->bindings('/', 'my-queue');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/queues/%2F/my-queue/bindings');
    }

    public function testPurgeMessages(): void
    {
        $this->client()->queues()->purgeMessages('/', 'my-queue');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/queues/%2F/my-queue/contents');
    }

    public function testRetrieveMessages(): void
    {
        $this->client()->queues()->retrieveMessages('/', 'my-queue');

        $this->assertSentRequestParts('POST', '/api/queues/%2F/my-queue/get');
        self::assertSame(['count' => 5, 'requeue' => true, 'encoding' => 'auto'], $this->lastRequestBody());
    }
}
