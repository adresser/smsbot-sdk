<?php


namespace Adresser\Smsbot\Tests\Fake;


use Adresser\Smsbot\RequestDispatcher;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;

/**
 * The purpose of this class is to fake the request
 * dispatcher using the Guzzle\Http debug classes.
 * @package Adresser\Smsbot\Tests\Fake
 */
class FakeRequestDispatcher extends RequestDispatcher
{
    private static string $dummyAuthenticationKey = 'dummy-key';

    private array $httpTransactionContainer = [];

    private MockHandler $mockHandler;

    public function __construct()
    {
        $httpClient = $this->getFakeHttpClient();
        parent::__construct(self::$dummyAuthenticationKey, $httpClient);
    }

    public function appendResponse(Response $response): void
    {
        $this->mockHandler->append($response);
    }

    public function flushTransactionContainer(): void
    {
        $this->httpTransactionContainer = [];
    }

    public function getTransactionContainer(): array
    {
        return $this->httpTransactionContainer;
    }

    private function getFakeHttpClient(): ClientInterface
    {
        $this->initializeMockHandler();
        $handlerStack = new HandlerStack($this->mockHandler);
        $handlerStack->push(Middleware::history($this->httpTransactionContainer));
        return new Client(['handler' => $handlerStack]);
    }

    private function initializeMockHandler(): void
    {
        $this->mockHandler = new MockHandler([]);
    }
}