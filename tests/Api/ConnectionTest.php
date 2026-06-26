<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class ConnectionTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->connections()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/connections');
    }

    public function testGet(): void
    {
        $this->client()->connections()->get('c');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/connections/c');
    }

    public function testDelete(): void
    {
        $this->client()->connections()->delete('c');

        $this->assertSentRequest('DELETE', 'http://localhost:15672/api/connections/c');
    }
}
