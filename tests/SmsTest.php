<?php

namespace Adresser\Smsbot\Tests;

use Exception;
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
        $arrayOfSmsData = [
            'destinations'  => $sms->getDestinations(),
            'text'          => $sms->getText(),
            'encoding'      => $sms->getEncoding(),
        ];
        $arrayOfSmsData = array_merge($arrayOfSmsData, $sms->getDatabaseInfos());
        $expectedSmsString = json_encode($arrayOfSmsData);
        $this->assertEquals($expectedSmsString, (string) $sms);
    }

    public function testCannotUseNonformattedNumberAsDestination(): void
    {
        $this->expectException(Exception::class);
        $sms = new Sms('test', Sms::PLAIN_ENCODING);
        $sms->pushDestination('393403401234');
    }

    public function testCanUseWellFormattedNumberAsDestination(): void
    {
        $sms = new Sms('test', Sms::PLAIN_ENCODING);
        try {
            $sms->pushDestination('+393403401234');
        } catch (Exception $e) {
            $this->fail('Unexpected invalid number');
        }
        $this->assertEquals('+393403401234', $sms->getDestinations()->first());
    }

    public function testCanDetectBulkMessage(): void
    {
        $sms = new Sms('test', Sms::PLAIN_ENCODING);
        try {

            $sms->pushDestination('+393403401234');
            $sms->pushDestination('+393403401235');

        } catch (Exception $e) {
            $this->fail('Unexpected invalid number');
        }
        $this->assertTrue($sms->isBulk());
    }

    public function testCanAddDatabaseInfos(): void
    {
        $databaseInfos = [
            "id"            => 1024,
            "send_through"  => 'http',
            "gateway_id"    => 'AdresserHttpGateway',
            "sender_id"     => '97',
            "country_id"    => '19',
            "response_id"   => '1829-7653-1234-9802',
            "status"        => 'Delivered',
            "device_id"     => null,
            "sim_id"        => null,
            "campaign_id"   => null,
            "created_at"    => null
        ];

        $sms = new Sms('test', Sms::PLAIN_ENCODING);
        $sms->setDatabaseInfos($databaseInfos);
        $databaseInfoFetchedFromSms = $sms->getDatabaseInfos();
        $this->assertEqualsCanonicalizing($databaseInfos, $databaseInfoFetchedFromSms);
    }
}