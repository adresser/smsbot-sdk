<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\Otp;
use PHPUnit\Framework\TestCase;

class OtpTest extends TestCase
{
    public function testCanCastOtpAsString(): void
    {
        $otp = new Otp('test', 60);
        $this->assertEquals('test', (string) $otp);
    }

    public function testExpirationDateWorks(): void
    {
        $otp = new Otp('test', 60);

        $expirationTime = strtotime($otp->getExpiration());
        $currentTime = time();
        $this->assertTrue($expirationTime > $currentTime);
    }
}
