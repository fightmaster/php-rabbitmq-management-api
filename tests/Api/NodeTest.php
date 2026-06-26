<?php

namespace RabbitMq\ManagementApi\Tests\Api;

use RabbitMq\ManagementApi\Api\Node;
use RabbitMq\ManagementApi\Tests\TestCase;

class NodeTest extends TestCase
{
    public function testAll(): void
    {
        (new Node($this->client()))->all();

        $this->assertSentRequest('GET', 'http://localhost:15672/api/nodes');
    }

    public function testGet(): void
    {
        (new Node($this->client()))->get('rabbit@host');

        $this->assertSentRequest('GET', 'http://localhost:15672/api/nodes/rabbit%40host');
    }

    public function testGetWithMemory(): void
    {
        (new Node($this->client()))->get('rabbit@host', true);

        $this->assertSentRequestParts('GET', '/api/nodes/rabbit%40host', 'memory=true');
    }
}
