<?php

namespace Adresser\Smsbot\Tests;

use Adresser\Smsbot\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testCanGetConfigurationValue(): void
    {
        try {
            $configurationValue = Environment::getConfiguration('environment_class_test');
        } catch (\Exception $e) {
            $configurationValue = null;
            $this->fail('unexpected exception');
        }

        $this->assertEquals('passed', $configurationValue);
    }
}
