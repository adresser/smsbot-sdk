<?php

namespace Adresser\Smsbot\Facade; 

use Adresser\Smsbot\OtpFactory;
use Adresser\Smsbot\SmsClientFactory;

/**
 * Class that uses the Facade design pattern to provide an
 * ingress to third-part applications.
 *  
 */
class Smsbot
{
    private static ?string $authenticationKey = null; 

    public static function setAuthenticationKey(string $authenticationKey): void 
    {
        self::$authenticationKey = $authenticationKey; 
    }

    public static function getSmsClientFactory(): SmsClientFactory
    {
        self::assertAuthenticationKeyIsSet(); 

        return new SmsClientFactory(self::$authenticationKey); 
    }

    public static function getOtpFactory(): OtpFactory
    {
        self::assertAuthenticationKeyIsSet(); 

        return new OtpFactory(self::$authenticationKey); 
    }

    private static function assertAuthenticationKeyIsSet(): void 
    {
        if (self::$authenticationKey == null) {
            throw new \Exception("Must set the authentication key first", 1);
        }
    }
}