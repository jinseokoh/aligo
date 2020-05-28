<?php

namespace JinseokOh\Aligo;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Aligo\Exceptions\InvalidConfiguration;


class AligoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(AligoTextChannel::class)
            ->needs(AligoTextClient::class)
            ->give(function () {
                $config = config('services.aligo');

                if (empty($config)) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                $appId = config('services.aligo.app_id');
                $appKey = config('services.aligo.app_key');
                $smsFrom = config('services.aligo.sms_from');

                return new AligoTextClient($appId, $appKey, $smsFrom);
            });

        $this->app->when(AligoKakaoChannel::class)
            ->needs(AligoKakaoClient::class)
            ->give(function () {
                $config = config('services.aligo');

                if (empty($config)) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                $appId = config('services.aligo.app_id');
                $appKey = config('services.aligo.app_key');
                $smsFrom = config('services.aligo.sms_from');
                $kakaoKey = config('services.aligo.kakao_key');

                return new AligoKakaoClient($appId, $appKey, $smsFrom, $kakaoKey);
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
