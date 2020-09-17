<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\OtpFactory;
use Adresser\Smsbot\OtpGenerator;
use Adresser\Smsbot\OtpValidator;
use PHPUnit\Framework\TestCase;

class OtpFactoryTest extends TestCase
{
    public function testCanGetOtpGenerator(): void
    {
        $factory = new OtpFactory('dummy auth key');
        $this->assertInstanceOf(OtpGenerator::class, $factory->getOtpGenerator());
    }

    public function testCanGetOtpValidator(): void
    {
        $factory = new OtpFactory('dummy auth key');
        $this->assertInstanceOf(OtpValidator::class, $factory->getOtpValidator());
    }
}
