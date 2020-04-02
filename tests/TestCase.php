<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // $this->artisan('cache:clear');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('aligo.app_id', 'set-your-aligo-app-id-here');
        $app['config']->set('aligo.app_key', 'set-your-aligo-app-key-here');
        $app['config']->set('aligo.phone_number', 'set-your-approved-phone-number-here');
        $app['config']->set('aligo.kakao_sender_key', 'set-your-kakao-sender-key-here');
    }

    /**
     * Load package service provider
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            AligoServiceProvider::class,
        ];
    }
}
