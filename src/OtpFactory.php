<?php

namespace Adresser\Smsbot; 

use GuzzleHttp\Client;
use Adresser\Smsbot\Enviroment;
use Adresser\Smsbot\OtpGenerator;
use Adresser\Smsbot\OtpValidator;
use Adresser\Smsbot\RequestDispatcher;

class OtpFactory 
{
    private RequestDispatcher $requestDispatcher; 

    private ?OtpGenerator $cachedOtpGenerator = null; 
    
    private ?OtpGenerator $cachedOtpValidator = null; 

    public function __construct (string $authenticationKey)
    {
        $serverUri = Enviroment::getConfiguration('server_uri'); 
        $httpClient = new Client(['base_uri' => $serverUri]); 

        $this->requestDispatcher = new RequestDispatcher($authenticationKey, $httpClient);
    }

    public function getOtpGenerator(): OtpGenerator
    {
        if ($cachedOtpGenerator == null) 
            $cachedOtpGenerator = new OtpGenerator($this->requestDispatcher); 

        return $this->cachedOtpGenerator; 
    }  

    public function getOtpValidator(): OtpValidator
    {
        if ($cachedOtpValidator == null) 
            $cachedOtpValidator = new OtpValidator($this->requestDispatcher); 

        return $this->cachedOtpValidator; 
    }  
}