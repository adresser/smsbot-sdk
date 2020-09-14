<?php

namespace Adresser\Smsbot; 

use GuzzleHttp\Client;
use Adresser\Smsbot\Otp;

/**
 * Generate an OTP giving a lifetime in seconds. 
 */
class OtpGenerator
{
    protected RequestDispatcher $requestDispatcher;  

    public function __construct (RequestDispatcher $requestDispatcher)
    {
        $this->requestDispatcher = $requestDispatcher; 
    }

    public function generate(int $lifetimeInSeconds): Otp 
    {
        $payload = ['lifetime' => $lifetimeInSeconds];

        $response = $this->requestDispatcher->doRequest('api/otp/generate', 'POST', [], $payload); 

        $responseBodyArray = json_decode((string) $response->getBody(), true);

        return new Otp($responseBodyArray['otp'], $lifetimeInSeconds); 
    } 
}