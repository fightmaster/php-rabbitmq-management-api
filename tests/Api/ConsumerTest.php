<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class ConsumerTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->consumers()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/consumers');
    }

    public function testGet(): void
    {
        $this->client()->consumers()->get('/');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/consumers/%2F');
    }
}
