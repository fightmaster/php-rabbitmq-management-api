<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class VhostTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->vhosts()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/vhosts');
    }

    public function testGet(): void
    {
        $this->client()->vhosts()->get('foo');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/vhosts/foo');
    }

    public function testCreate(): void
    {
        $this->client()->vhosts()->create('foo');

        $this->assertSentRequest('PUT', 'http://localhost:15672/api/vhosts/foo');
    }

    public function testDelete(): void
    {
        $this->client()->vhosts()->delete('foo');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/vhosts/foo');
    }

    public function testPermissions(): void
    {
        $this->client()->vhosts()->permissions('foo');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/vhosts/foo/permissions');
    }
}
