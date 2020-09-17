<?php

namespace Adresser\Smsbot;

/**
 * Validate an OTP previously generated making
 * a request to the smsbot endpoint. 
 */
class OtpValidator
{
    protected RequestDispatcher $requestDispatcher;  

    public function __construct (RequestDispatcher $requestDispatcher)
    {
        $this->requestDispatcher = $requestDispatcher; 
    }

    public function validate(Otp $otp): bool 
    {
        $payload = ['otp' => (string) $otp]; 

        $response = $this->requestDispatcher->doRequest('api/otp/validate', 'POST', [], $payload); 

        $responseBodyArray = json_decode((string) $response->getBody(), true);

        return $responseBodyArray['status'] == 'Success'; 
    } 
}