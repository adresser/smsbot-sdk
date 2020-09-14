<?php

namespace Adresser\Smsbot; 

use GuzzleHttp\Client;
use Adresser\Smsbot\Enviroment;
use Adresser\Smsbot\HttpSmsClient;
use Adresser\Smsbot\DeviceSmsClient;

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

    public function getClient (string $driver, string $authenticationKey) 
    {
        if (!$this->driverExists($driver)) 
            throw new \Exception("Driver doesn't exists", 1);

        $serverUri = Enviroment::getConfiguration('server_uri'); 
        $httpClient = new Client(['base_uri' => $serverUri]); 
        $requestDispatcher = new RequestDispatcher($authenticationKey, $httpClient); 

        $clientClass = self::$driverClassMap[$driver]; 
        return new $clientClass($requestDispatcher); 
    } 

    private function driverExists (string $driver) 
    {
        return in_array($driver, self::$existingDrivers); 
    }
}