<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\Tests\Fake\FakeRequestDispatcher;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Exception;

class RequestDispatcherTest extends TestCase
{
    /**
     * All the tests will use the FakeRequestDispatcher, because
     * is basically the same, but it has a fake http client embedded
     * inside. So every http request can be analyzed.
     */

    public function testCanSendRequestCorrectly(): void
    {
        $requestDispatcher = new FakeRequestDispatcher();
        $requestDispatcher->flushTransactionContainer();
        $requestDispatcher->appendResponse(new Response(200, [], '[{}]'));

        $requestDispatcher->doRequest('api/test/request', 'GET');
        $expectedRequestTarget = '/?auth_key=dummy-key&route=' . urlencode('api/test/request');

        $transactionContainer = $requestDispatcher->getTransactionContainer();
        $requestCaught = $transactionContainer[0]['request'] ?? null;

        $this->assertInstanceOf(Request::class, $requestCaught);
        $this->assertEquals($expectedRequestTarget, $requestCaught->getRequestTarget());
        $this->assertEquals('GET', $requestCaught->getMethod());
    }

    public function testCanDetectUnauthenticatedErrorPage(): void
    {
        $this->expectException(Exception::class);
        $requestDispatcher = new FakeRequestDispatcher();
        $requestDispatcher->flushTransactionContainer();

        // Error page simply cannot be translated to a json object.
        // In order to simulate that response, we can pass a blank
        // body to the fake response.
        $requestDispatcher->appendResponse(new Response(200));
        $requestDispatcher->doRequest('api/test/request', 'GET');
    }
}
