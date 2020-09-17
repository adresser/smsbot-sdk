<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\Otp;
use Adresser\Smsbot\OtpValidator;
use Adresser\Smsbot\Tests\Fake\FakeRequestDispatcher;
use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class OtpValidatorTest extends TestCase
{
    private string $goodServerResponseBody = '{"status": "Success"}';
    private string $badServerResponseBody  = '{"status": "Failed"}';

    public function testCanValidateOtp(): void
    {
        $requestDispatcher = new FakeRequestDispatcher();
        $goodServerResponse = new Response(200, [], $this->goodServerResponseBody);
        $requestDispatcher->appendResponse($goodServerResponse);

        $dummyOtp = new Otp('test', 60);
        $validator = new OtpValidator($requestDispatcher);
        $this->assertTrue($validator->validate($dummyOtp));
    }

    public function testValidationCanFail(): void
    {
        $requestDispatcher = new FakeRequestDispatcher();
        $badServerResponse = new Response(200, [], $this->badServerResponseBody);
        $requestDispatcher->appendResponse($badServerResponse);

        $dummyOtp = new Otp('test', 60);
        $validator = new OtpValidator($requestDispatcher);
        $this->assertFalse($validator->validate($dummyOtp));
    }

    public function testCanThrowExceptionOnServerError(): void
    {
        $this->expectException(Exception::class);
        $requestDispatcher = new FakeRequestDispatcher();
        $requestDispatcher->appendResponse(new Response(500));

        $dummyOtp = new Otp('test', 60);
        $validator = new OtpValidator($requestDispatcher);
        $validator->validate($dummyOtp);
    }
}
