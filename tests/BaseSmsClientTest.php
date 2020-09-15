<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\BaseSmsClient;
use Adresser\Smsbot\RequestDispatcher;
use Adresser\Smsbot\Sms;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class BaseSmsClientTest extends TestCase
{
    private MockHandler $mockResponseStack;
    private Client $httpClient;
    private RequestDispatcher $requestDispatcher;

    private string $smsIndexResponseSample = <<<JSON
        {
            "status": "Success",
            "data": [
                {
                    "id": 1,
                    "send_through": "Http",
                    "gateway_id": "adresserapihttp",
                    "sender_id": "wed63478u",
                    "country_id": 89,
                    "response_id": "",
                    "status": "Sent",
                    "device_id": null,
                    "sim_id": null,
                    "mobile_no": "+393402417822",
                    "data_type": "Plain",
                    "message": "test",
                    "campaign_id": null,
                    "user_id": 1,
                    "created_by": 1,
                    "created_at": "2020-08-01 14:31:46",
                    "updated_at": "2020-08-01 14:31:46"
                }
            ]
        }
    JSON;

    /**
     * The mock response stack will simulate
     * http responses from the server and will
     * be used by the http client.
     */
    public function __construct()
    {
        parent::__construct();
        $this->mockResponseStack = new MockHandler([]);
        $handlerStack = new HandlerStack($this->mockResponseStack);
        $this->httpClient = new Client(['handler' => $handlerStack]);
        $this->requestDispatcher =
            new RequestDispatcher('fake-key', $this->httpClient);
    }

    /**
     * Since we're testing an abstract class (that contains a lot
     * of useful methods), we need to provide some dummy implementation
     * of the abstract methods.
     * @return BaseSmsClient
     */
    private function getClassImplementation(): BaseSmsClient
    {
        return new Class($this->requestDispatcher) extends BaseSmsClient {

            public function __construct(RequestDispatcher $requestDispatcher)
            {
                parent::__construct($requestDispatcher);
            }

            public function send(Sms $sms): void {}
        };
    }

    private function getSmsCollectionFromSampleResponse(): Collection
    {
        $responseAsArray = json_decode($this->smsIndexResponseSample, true)['data'];
        $responseAsArray = array_map(function ($element) {
            $sms = new Sms($element['message'], $element['data_type']);
            $sms->pushDestination($element['mobile_no']);
            $sms->setDatabaseInfos($element);
            return $sms;
        }, $responseAsArray);
        return new Collection($responseAsArray);
    }

    public function testCanGetQueuedSms(): void
    {
        $queuedSmsResponse = new Response(200, [], $this->smsIndexResponseSample);
        $this->mockResponseStack->append($queuedSmsResponse);

        $smsClient = $this->getClassImplementation();
        $smsArrayFromClient = $smsClient->getQueuedSms()->toArray();
        $smsArrayFromSample = $this->getSmsCollectionFromSampleResponse()->toArray();
        $this->assertEqualsCanonicalizing($smsArrayFromSample, $smsArrayFromClient);
    }

    public function testCanGetSmsHistory(): void
    {
        $smsHistoryResponse = new Response(200, [], $this->smsIndexResponseSample);
        $this->mockResponseStack->append($smsHistoryResponse);

        $smsClient = $this->getClassImplementation();
        $smsArrayFromClient = $smsClient->getSmsHistory()->toArray();
        $smsArrayFromSample = $this->getSmsCollectionFromSampleResponse()->toArray();
        $this->assertEqualsCanonicalizing($smsArrayFromSample, $smsArrayFromClient);
    }
}
