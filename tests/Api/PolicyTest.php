<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class PolicyTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->policies()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/policies');
    }

    public function testGetForVhost(): void
    {
        $this->client()->policies()->get('/');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/policies/%2F');
    }

    public function testGetPolicy(): void
    {
        $this->client()->policies()->get('/', 'p');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/policies/%2F/p');
    }

    public function testCreate(): void
    {
        $this->client()->policies()->create('/', 'p', ['pattern' => '^a']);

        $this->assertSentRequestParts('PUT', '/api/policies/%2F/p');
        self::assertSame(['pattern' => '^a'], $this->lastRequestBody());
    }

    public function testDelete(): void
    {
        $this->client()->policies()->delete('/', 'p');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/policies/%2F/p');
    }
}
