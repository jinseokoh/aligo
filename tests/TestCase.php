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
    }

    public function tearDown(): void
    {
        parent::tearDown();

        if ($container = \Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
        $this->artisan('cache:clear');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('services.aligo.app_id', 'amusetravel');
        $app['config']->set('services.aligo.app_key', '00000000000000000000000000000000');
        $app['config']->set('services.aligo.sms_from', '02-000-0000');
        $app['config']->set('services.aligo.kakao_key', '0000000000000000000000000000000000000000');
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
