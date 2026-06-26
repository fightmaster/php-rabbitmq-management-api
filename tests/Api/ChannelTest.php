<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Tests\TestCase;

class ChannelTest extends TestCase
{
    public function testAll(): void
    {
        $this->client()->channels()->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/channels');
    }

    public function testGet(): void
    {
        $this->client()->channels()->get('ch');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/channels/ch');
    }
}
