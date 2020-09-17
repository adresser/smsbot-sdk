<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\DeviceSmsClient;
use Adresser\Smsbot\HttpSmsClient;
use Adresser\Smsbot\SmsClientFactory;
use PHPUnit\Framework\TestCase;

class SmsClientFactoryTest extends TestCase
{
    public function testCanGetHttpClient(): void
    {
        $clientFactory = new SmsClientFactory('dummy auth key');
        $httpSmsClient = $clientFactory->getClient('http');
        $this->assertInstanceOf(HttpSmsClient::class, $httpSmsClient);
    }

    public function testCanGetDeviceClient(): void
    {
        $clientFactory = new SmsClientFactory('dummy auth key');
        $deviceSmsClient = $clientFactory->getClient('device');
        $this->assertInstanceOf(DeviceSmsClient::class, $deviceSmsClient);
    }
}
