<?php

namespace Adresser\Smsbot; 

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Adresser\Smsbot\RequestDispatcher;
use Adresser\Smsbot\SmsClientContract;

abstract class BaseSmsClient implements SmsClientContract
{
    protected RequestDispatcher $requestDispatcher; 

    public function __construct (RequestDispatcher $requestDispatcher) 
    {
        $this->requestDispatcher = $requestDispatcher; 
    }

    public abstract function send(Sms $sms): void; 

    public function getSmsHistory(): Collection
    {
        $response = $this->requestDispatcher->doRequest('api/sms/getHistory', 'GET'); 
        
        $responseBodyArray = json_decode((string) $response->getBody(), true);

        return $this->makeSmsCollectionFromArray($responseBodyArray['data']); 
    } 

    public function getQueuedSms(): Collection
    {
        $response = $this->requestDispatcher->doRequest('api/sms/getSmsInQueue', 'GET'); 
        
        $responseBodyArray = json_decode((string) $response->getBody(), true);

        return $this->makeSmsCollectionFromArray($responseBodyArray['data']); 
    }

    protected function makeSmsCollectionFromArray(array $array)
    {
        $arrayOfSms = array_map(function ($element) {
            $sms = new Sms($element['message'], $element['data_type']); 
            $sms->pushDestination($element['mobile_no']); 
            $sms->setDatabaseInformations($element);
            return $sms; 
        }, $array);

        return new Collection($arrayOfSms); 
    }
}