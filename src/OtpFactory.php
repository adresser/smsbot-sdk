<?php

namespace Adresser\Smsbot; 

use GuzzleHttp\Client;

class OtpFactory 
{
    private RequestDispatcher $requestDispatcher; 

    private ?OtpGenerator $cachedOtpGenerator = null; 
    
    private ?OtpValidator $cachedOtpValidator = null;

    public function __construct (string $authenticationKey)
    {
        $serverUri = Enviroment::getConfiguration('server_uri'); 
        $httpClient = new Client(['base_uri' => $serverUri]); 

        $this->requestDispatcher = new RequestDispatcher($authenticationKey, $httpClient);
    }

    public function getOtpGenerator(): OtpGenerator
    {
        if ($this->cachedOtpGenerator == null)
            $this->cachedOtpGenerator = new OtpGenerator($this->requestDispatcher);

        return $this->cachedOtpGenerator; 
    }  

    public function getOtpValidator(): OtpValidator
    {
        if ($this->cachedOtpValidator == null)
            $this->cachedOtpValidator = new OtpValidator($this->requestDispatcher);

        return $this->cachedOtpValidator; 
    }  
}