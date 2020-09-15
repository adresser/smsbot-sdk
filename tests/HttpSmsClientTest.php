<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\HttpSmsClient;
use Adresser\Smsbot\RequestDispatcher;
use Adresser\Smsbot\Sms;
use Adresser\Smsbot\Tests\Fake\FakeRequestDispatcher;
use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class HttpSmsClientTest extends TestCase
{
    private RequestDispatcher $requestDispatcher;

    public function __construct()
    {
        parent::__construct();
        $this->requestDispatcher = new FakeRequestDispatcher();
    }

    public function testCannotSendMessageWithoutMandatoryArguments(): void
    {
        $this->expectException(Exception::class);
        $smsClient = new HttpSmsClient($this->requestDispatcher);
        $sms = new Sms('test');
        $sms->pushDestination('+393332224444');
        $smsClient->send($sms);
    }

    public function testCanSendMessage(): void
    {
        $serverResponse = new Response(200, [], '[{}]');
        $this->requestDispatcher->getTransactionContainer();
        $this->requestDispatcher->appendResponse($serverResponse);

        $sms = new Sms('test');
        $sms->pushDestination('+393332224444');

        $smsClient = new HttpSmsClient($this->requestDispatcher);
        $smsClient->setSenderId('test');
        $smsClient->setCountryId(1);
        $smsClient->send($sms);

        $transactionContainer = $this->requestDispatcher->getTransactionContainer();
        $this->assertTrue(isset($transactionContainer[0]));
        $this->assertEquals('GET', $transactionContainer[0]['request']->getMethod());
    }
}
