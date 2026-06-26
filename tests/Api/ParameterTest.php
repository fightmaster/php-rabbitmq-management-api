<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class ParameterTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->parameters()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/parameters');
    }

    public function testGetComponent(): void
    {
        $this->client()->parameters()->get('comp');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/parameters/comp');
    }

    public function testGetComponentAndVhost(): void
    {
        $this->client()->parameters()->get('comp', '/');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/parameters/comp/%2F');
    }

    public function testGetComponentVhostAndName(): void
    {
        $this->client()->parameters()->get('comp', '/', 'n');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/parameters/comp/%2F/n');
    }

    public function testCreate(): void
    {
        $this->client()->parameters()->create('comp', '/', 'n', ['value' => 1]);

        $this->assertSentRequestParts('PUT', '/api/parameters/comp/%2F/n');
        self::assertSame(['value' => 1], $this->lastRequestBody());
    }

    public function testDelete(): void
    {
        $this->client()->parameters()->delete('comp', '/', 'n');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/parameters/comp/%2F/n');
    }
}
