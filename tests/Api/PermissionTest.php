<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Exception\InvalidArgumentException;
use RabbitMq\ManagementApi\Tests\TestCase;

class PermissionTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->permissions()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/permissions');
    }

    public function testGet(): void
    {
        $this->client()->permissions()->get('/', 'user');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/permissions/%2F/user');
    }

    public function testCreate(): void
    {
        $permission = ['configure' => '.*', 'write' => '.*', 'read' => '.*'];

        $this->client()->permissions()->create('/', 'user', $permission);

        $this->assertSentRequestParts('PUT', '/api/permissions/%2F/user');
        self::assertSame($permission, $this->lastRequestBody());
    }

    public function testCreateThrowsWhenWriteAndReadMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->client()->permissions()->create('/', 'user', ['configure' => '.*']);
    }

    public function testDelete(): void
    {
        $this->client()->permissions()->delete('/', 'user');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/permissions/%2F/user');
    }
}
