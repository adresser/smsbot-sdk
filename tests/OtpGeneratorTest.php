<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\OtpGenerator;
use Adresser\Smsbot\Tests\Fake\FakeRequestDispatcher;
use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class OtpGeneratorTest extends TestCase
{
    private string $goodServerResponseBody = '{"otp": "test"}';

    public function testCanGenerateOtp(): void
    {
        $requestDispatcher = new FakeRequestDispatcher();
        $generator = new OtpGenerator($requestDispatcher);

        $goodServerResponse = new Response(200, [], $this->goodServerResponseBody);
        $requestDispatcher->appendResponse($goodServerResponse);

        $otp = $generator->generate(60);
        $this->assertEquals('test', (string) $otp);
    }

    public function testCanThrowExceptionOnServerError(): void
    {
        $this->expectException(Exception::class);
        $requestDispatcher = new FakeRequestDispatcher();
        $requestDispatcher->appendResponse(new Response(500));

        $generator = new OtpGenerator($requestDispatcher);
        $generator->generate(60);
    }
}
