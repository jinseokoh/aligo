<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // $this->artisan('cache:clear');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        // ALIGO_APP_ID=amusetravel
        // ALIGO_APP_KEY=t4ae0lx8tgp889ey5448wcsgt3urb5rb
        // ALIGO_PHONE_NUMBER=027196810
        // ALIGO_KAKAO_SENDER_KEY=72177a356440d1d6c50945ed2655ed0ee8fe6a60

        $app['config']->set('services.aligo.app_id', 'amusetravel');
        $app['config']->set('services.aligo.app_key', 't4ae0lx8tgp889ey5448wcsgt3urb5rb');
        $app['config']->set('services.aligo.sms_from', '02-719-6810');
        $app['config']->set('services.aligo.kakao_key', '72177a356440d1d6c50945ed2655ed0ee8fe6a60');
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
