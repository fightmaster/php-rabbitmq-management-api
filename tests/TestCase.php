<?php

namespace RabbitMq\ManagementApi\Tests;

use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\RequestInterface;
use RabbitMq\ManagementApi\Client;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var MockClient
     */
    protected $mock;

    /**
     * Build a Client backed by a mock HTTP client that records every request and
     * answers every call with $body.
     */
    protected function client(string $body = '{}', int $status = 200): Client
    {
        $this->mock = new MockClient();
        $this->mock->setDefaultResponse(new Response($status, [], $body));

        return new Client($this->mock, 'http://localhost:15672', 'guest', 'guest');
    }

    /**
     * Assert the most recently sent request used the given method and full URI, and return it.
     */
    protected function assertSentRequest(string $method, string $uri): RequestInterface
    {
        $request = $this->mock->getLastRequest();

        self::assertInstanceOf(RequestInterface::class, $request, 'No request was sent.');
        self::assertSame($method, $request->getMethod());
        self::assertSame($uri, (string) $request->getUri());

        return $request;
    }

    /**
     * Assert the most recently sent request used the given method, path and (raw) query string.
     */
    protected function assertSentRequestParts(string $method, string $path, string $query = ''): RequestInterface
    {
        $request = $this->mock->getLastRequest();

        self::assertInstanceOf(RequestInterface::class, $request, 'No request was sent.');
        self::assertSame($method, $request->getMethod());
        self::assertSame($path, $request->getUri()->getPath());
        self::assertSame($query, $request->getUri()->getQuery());

        return $request;
    }

    /**
     * Decode the JSON body of the most recently sent request.
     */
    protected function lastRequestBody(): ?array
    {
        $body = (string) $this->mock->getLastRequest()->getBody();

        return '' === $body ? null : json_decode($body, true);
    }
}
