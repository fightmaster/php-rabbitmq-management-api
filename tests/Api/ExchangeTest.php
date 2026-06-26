<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Exception\InvalidArgumentException;
use RabbitMq\ManagementApi\Tests\TestCase;

class ExchangeTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->exchanges()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/exchanges');
    }

    public function testAllForVhost(): void
    {
        $this->client()->exchanges()->all('/');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/exchanges/%2F');
    }

    public function testGet(): void
    {
        $this->client()->exchanges()->get('/', 'ex');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/exchanges/%2F/ex');
    }

    public function testCreate(): void
    {
        $exchange = ['type' => 'direct', 'durable' => true];

        $this->client()->exchanges()->create('/', 'ex', $exchange);

        $this->assertSentRequestParts('PUT', '/api/exchanges/%2F/ex');
        self::assertSame($exchange, $this->lastRequestBody());
    }

    public function testCreateThrowsWhenTypeMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client()->exchanges()->create('/', 'ex', ['durable' => true]);
    }

    public function testDelete(): void
    {
        $this->client()->exchanges()->delete('/', 'ex');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/exchanges/%2F/ex');
    }

    public function testSourceBindings(): void
    {
        $this->client()->exchanges()->sourceBindings('/', 'ex');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/exchanges/%2F/ex/bindings/source');
    }

    public function testDestinationBindings(): void
    {
        $this->client()->exchanges()->destinationBindings('/', 'ex');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/exchanges/%2F/ex/bindings/destination');
    }

    public function testPublish(): void
    {
        $message = [
            'properties' => [],
            'routing_key' => 'k',
            'payload' => 'p',
            'payload_encoding' => 'string',
        ];

        $this->client()->exchanges()->publish('/', 'ex', $message);

        $this->assertSentRequestParts('POST', '/api/exchanges/%2F/ex/publish');
        self::assertSame($message, $this->lastRequestBody());
    }

    public function testPublishThrowsWhenEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client()->exchanges()->publish('/', 'ex', []);
    }

    public function testPublishThrowsWhenPayloadEncodingMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client()->exchanges()->publish('/', 'ex', [
            'properties' => [],
            'routing_key' => 'k',
            'payload' => 'p',
        ]);
    }
}
