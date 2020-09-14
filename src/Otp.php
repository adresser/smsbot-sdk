<?php

namespace Adresser\Smsbot; 

use Adresser\Smsbot\Enviroment;
use Adresser\Smsbot\RequestDispatcher;

class Otp 
{
    protected string $otpCode; 

    protected int $lifetimeInSeconds;  

    protected string $expirationDate; 

    public function __construct (string $otpCode, int $lifetimeInSeconds)
    {
        $this->otpCode = $otpCode; 
        $this->lifetimeInSeconds = $lifetimeInSeconds; 
        $this->expirationDate = date("m/d/Y h:i:s a", time() + $lifetimeInSeconds); 
    }

    public function __toString (): string 
    {
        return $this->otpCode; 
    }

    public function getLifetimeInSeconds (): int
    {
        return $this->lifetimeInSeconds; 
    }

    /**
     * To get the correct expiration date, the time zone must me properly set. 
     */
    public function getExpiration (): string 
    {
        return $this->expirationDate; 
    }
}