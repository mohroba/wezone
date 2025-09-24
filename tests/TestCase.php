<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private static bool $passportKeysGenerated = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (! self::$passportKeysGenerated) {
            \Illuminate\Support\Facades\Artisan::call('passport:keys', [
                '--no-interaction' => true,
                '--force' => true,
            ]);

            self::$passportKeysGenerated = true;
        }
    }
}
