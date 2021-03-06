<?php

namespace Adresser\Smsbot; 

use GuzzleHttp\Client;

/**
 * Factory method pattern to get a SmsClient based on
 * the type of driver (http gateway or cellphone (device)). 
 */
class SmsClientFactory 
{
    private static array $existingDrivers = [
        'http', 
        'device'
    ]; 

    private static array $driverClassMap = [
        'http'   => HttpSmsClient::class, 
        'device' => DeviceSmsClient::class
    ];

    private string $authenticationKey; 

    public function __construct (string $authenticationKey)
    {
        $this->authenticationKey = $authenticationKey; 
    }

    public function getClient (string $driver) 
    {
        if (!$this->driverExists($driver)) 
            throw new \Exception("Driver doesn't exists", 1);

        $serverUri = Environment::getConfiguration('server_uri');
        $httpClient = new Client(['base_uri' => $serverUri]); 
        $requestDispatcher = new RequestDispatcher($this->authenticationKey, $httpClient); 

        $clientClass = self::$driverClassMap[$driver]; 
        return new $clientClass($requestDispatcher); 
    } 

    private function driverExists (string $driver) 
    {
        return in_array($driver, self::$existingDrivers); 
    }
}