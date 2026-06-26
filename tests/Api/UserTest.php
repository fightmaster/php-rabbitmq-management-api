<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class UserTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->users()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/users');
    }

    public function testGet(): void
    {
        $this->client()->users()->get('bob');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/users/bob');
    }

    public function testCreate(): void
    {
        $this->client()->users()->create('bob', ['tags' => 'administrator']);

        $this->assertSentRequestParts('PUT', '/api/users/bob');
        self::assertSame(['tags' => 'administrator'], $this->lastRequestBody());
    }

    public function testDelete(): void
    {
        $this->client()->users()->delete('bob');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/users/bob');
    }

    public function testPermissions(): void
    {
        $this->client()->users()->permissions('bob');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/users/bob/permissions');
    }
}
