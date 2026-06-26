<?php

namespace RabbitMq\ManagementApi\Tests;

use RabbitMq\ManagementApi\Api;

class ClientTest extends TestCase
{
    public function testAlivenessTestEncodesVhost(): void
    {
        $result = $this->client('{"status":"ok"}')->alivenessTest('/');

        self::assertSame(['status' => 'ok'], $result);
        $this->assertSentRequest('GET', 'http://localhost:15672/api/aliveness-test/%2F');
    }

    public function testOverview(): void
    {
        $this->client()->overview();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/overview');
    }

    public function testExtensions(): void
    {
        $this->client()->extensions();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/extensions');
    }

    public function testDefinitions(): void
    {
        $this->client()->definitions();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/definitions');
    }

    public function testWhoami(): void
    {
        $this->client()->whoami();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/whoami');
    }

    public function testSendAppliesBasicAuthAndContentType(): void
    {
        $client = $this->client();
        $client->overview();
        $request = $this->mock->getLastRequest();

        self::assertSame('Basic ' . base64_encode('guest:guest'), $request->getHeaderLine('Authorization'));
        self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
    }

    public function testSendEncodesBodyAsJson(): void
    {
        $this->client()->send('/api/queues/%2F/test', 'PUT', [], ['durable' => true]);

        $request = $this->assertSentRequest('PUT', 'http://localhost:15672/api/queues/%2F/test');
        self::assertSame('{"durable":true}', (string) $request->getBody());
    }

    public function testSendDecodesJsonResponse(): void
    {
        self::assertSame(['messages' => 42], $this->client('{"messages":42}')->send('/api/overview'));
    }

    /**
     * @dataProvider provideApiFactories
     */
    public function testApiFactories(string $method, string $expectedClass): void
    {
        self::assertInstanceOf($expectedClass, $this->client()->{$method}());
    }

    public function provideApiFactories(): array
    {
        return [
            ['connections', Api\Connection::class],
            ['channels', Api\Channel::class],
            ['consumers', Api\Consumer::class],
            ['exchanges', Api\Exchange::class],
            ['queues', Api\Queue::class],
            ['vhosts', Api\Vhost::class],
            ['bindings', Api\Binding::class],
            ['users', Api\User::class],
            ['permissions', Api\Permission::class],
            ['parameters', Api\Parameter::class],
            ['policies', Api\Policy::class],
        ];
    }
}
