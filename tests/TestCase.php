<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoFacade;
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
        $app['config']->set('aligo.base_url', 'https://apis.aligo.in');
        $app['config']->set('aligo.app_id', 'set-your-aligo-app-id-here');
        $app['config']->set('aligo.app_key', 'set-your-aligo-app-key-here');
        $app['config']->set('aligo.phone_number', 'set-your-approved-phone-number-here');
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
    /**
     * Load package alias
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Aligo' => AligoFacade::class,
        ];
    }
}
