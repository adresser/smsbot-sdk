<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\Sms;
use PHPUnit\Framework\TestCase;

final class SmsTest extends TestCase
{
    public function testCanDecideSmsEncoding(): void
    {
        $plainTextSms = new Sms('test', Sms::PLAIN_ENCODING);
        $unicodeTextSms = new Sms('test', Sms::UNICODE_ENCODING);

        $this->assertEquals(Sms::PLAIN_ENCODING, $plainTextSms->getEncoding());
        $this->assertEquals(Sms::UNICODE_ENCODING, $unicodeTextSms->getEncoding());
    }

    public function testCanBeUsedAsString(): void
    {
        $sms = new Sms('test', Sms::UNICODE_ENCODING);
        $sms->pushDestination('+391232323100');
        $arrayOfSmsData = [
            'destinations'  => $sms->getDestinations(),
            'text'          => $sms->getText(),
            'encoding'      => $sms->getEncoding(),
        ];
        $arrayOfSmsData = array_merge($arrayOfSmsData, $sms->getDatabaseInfos());
        $expectedSmsString = json_encode($arrayOfSmsData);
        $this->assertEquals($expectedSmsString, (string) $sms);
    }
}