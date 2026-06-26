<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class BindingTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->bindings()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/bindings');
    }

    public function testAllForVhost(): void
    {
        $this->client()->bindings()->all('/');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/bindings/%2F');
    }

    public function testBinding(): void
    {
        $this->client()->bindings()->binding('/', 'ex', 'q');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/bindings/%2F/e/ex/q/q');
    }

    public function testExchangeBinding(): void
    {
        $this->client()->bindings()->exchangeBinding('/', 'src', 'dst');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/bindings/%2F/e/src/e/dst');
    }

    public function testCreate(): void
    {
        $this->client()->bindings()->create('/', 'ex', 'q');

        $this->assertSentRequestParts('POST', '/api/bindings/%2F/e/ex/q/q');
        self::assertSame(['routing_key' => ''], $this->lastRequestBody());
    }

    public function testCreateWithRoutingKeyAndArguments(): void
    {
        $this->client()->bindings()->create('/', 'ex', 'q', 'rk', ['x' => 1]);

        $this->assertSentRequestParts('POST', '/api/bindings/%2F/e/ex/q/q');
        self::assertSame(['routing_key' => 'rk', 'arguments' => ['x' => 1]], $this->lastRequestBody());
    }

    public function testCreateExchange(): void
    {
        $this->client()->bindings()->createExchange('/', 'src', 'dst', 'rk');

        $this->assertSentRequestParts('POST', '/api/bindings/%2F/e/src/e/dst');
        self::assertSame(['routing_key' => 'rk'], $this->lastRequestBody());
    }

    public function testGet(): void
    {
        $this->client()->bindings()->get('/', 'ex', 'q', 'props');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/bindings/%2F/e/ex/q/q/props');
    }

    public function testGetExchange(): void
    {
        $this->client()->bindings()->getExchange('/', 'src', 'dst', 'props');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/bindings/%2F/e/src/e/dst/props');
    }

    public function testDelete(): void
    {
        $this->client()->bindings()->delete('/', 'ex', 'q', 'props');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/bindings/%2F/e/ex/q/q/props');
    }

    public function testDeleteExchange(): void
    {
        $this->client()->bindings()->deleteExchange('/', 'src', 'dst', 'props');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/bindings/%2F/e/src/e/dst/props');
    }
}
