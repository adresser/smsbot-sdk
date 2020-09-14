<?php

namespace Adresser\Smsbot; 

use Adresser\Smsbot\Sms;
use Adresser\Smsbot\BaseSmsClient;
use Adresser\Smsbot\RequestDispatcher;
use Adresser\Smsbot\SmsClientContract;

final class HttpSmsClient extends BaseSmsClient implements SmsClientContract
{
    const ADRESSER_GATEWAY_ID = 'AdresserApiHttp'; 

    private ?string  $senderId; 

    private ?string  $gatewayId; 

    private ?int     $countryId; 
    
    public function __construct (RequestDispatcher $requestDispatcher) 
    {
        parent::__construct($requestDispatcher);
        
        $this->gatewayId = self::ADRESSER_GATEWAY_ID; 
        $this->countryId = null; 
        $this->senderId  = null; 
    }

    public function send(Sms $sms): void
    {
        $this->assertMandatoryParametersAreSet(); 

        $route = $this->getRoute($sms); 

        // the route will get only one between mobile_no and 
        // mobile_numbers parameters. We can pass both so we
        // can avoid another if statement. 

        $requestPayload = [
            'mobile_no'      => $sms->getDestinations()->first(), 
            'mobile_numbers' => $sms->getDestinations()->toArray(), 
            'message'        => $sms->getText(), 
            'sender_id'      => $this->senderId, 
            'country_id'     => $this->countryId, 
            'gateway_id'     => $this->gatewayId, 
            'data_type'      => $sms->getEncoding() 
        ]; 

        $this->requestDispatcher->doRequest($route, 'GET', $requestPayload);         
    }

    public function getSenderId(): ?string 
    {
        return $this->senderId;
    }

    public function setSenderId(string $senderId): void 
    {
        $this->senderId = $senderId;
    }

    public function getGatewayId(): ?string 
    {
        return $this->gatewayId;
    }

    public function setGatewayId(string $gatewayId): void 
    {
        $this->gatewayId = $gatewayId;
    }

    public function getCountryId(): ?int 
    {
        return $this->countryId;
    }

    public function setCountryId(int $countryId): void 
    {
        $this->countryId = $countryId;
    }

    private function getRoute(Sms $sms): string 
    {
        return $sms->isBulk() ? 'api/sms/sendMultipleSMSviaHttp' : 'api/sms/sendViaHttp'; 
    }

    private function assertMandatoryParametersAreSet(): void 
    {
        if ($this->senderId == null) 
            throw new \Exception("Sender id not set on http client.", 1);

        if ($this->countryId == null) 
            throw new \Exception("Contry id not set on http client.", 1);

        if ($this->gatewayId == null) 
            throw new \Exception("Gateway id not set on http client.", 1);
    }
}