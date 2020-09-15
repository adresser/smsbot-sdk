<?php

namespace Adresser\Smsbot;

final class DeviceSmsClient extends BaseSmsClient implements SmsClientContract
{
    private ?int $deviceId; 

    private ?int $simId; 

    public function __construct (RequestDispatcher $requestDispatcher) 
    {
        parent::__construct($requestDispatcher);
        
        $this->deviceId = null; 
        $this->simId  = null; 
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
            'device_id'      => $this->deviceId, 
            'sim_id'         => $this->simId, 
            'data_type'      => $sms->getEncoding() 
        ]; 

        $this->requestDispatcher->doRequest($route, 'GET', $requestPayload);  
    }

    public function getDeviceId(): ?int 
    {
        return $this->deviceId;
    }

    public function setDeviceId(int $deviceId): void 
    {
        $this->deviceId = $deviceId;
    }

    public function getSimId(): ?int
    {
        return $this->simId;
    }

    public function setSimId(int $simId): void
    {
        $this->simId = $simId;
    }

    private function getRoute(Sms $sms): string 
    {
        return $sms->isBulk() ? 'api/sms/sendMultipleSms' : 'api/sms/send'; 
    }

    private function assertMandatoryParametersAreSet(): void 
    {
        if ($this->deviceId == null) 
            throw new \Exception("Device id not set on device client.", 1);

        if ($this->simId == null) 
            throw new \Exception("SIM id not set on device client.", 1);
    }
}