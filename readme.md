# Unofficial Aligo text messaging package for Laravel

[![Total Downloads](https://poser.pugx.org/jinseokoh/aligo/downloads)](https://packagist.org/packages/jinseokoh/aligo)
[![License](https://poser.pugx.org/jinseokoh/aligo/license)](https://packagist.org/packages/jinseokoh/aligo)

Laravel 프로젝트에서 사용할 수 있는 [Aligo](https://smartsms.aligo.in/) 용 PHP Laravel 패키지이다.

Among a handful of Text messaging API providers in Korea as of April, 2020 [Aligo](https://smartsms.aligo.in/) is the hands down winner in terms of price. (KRW 8.4 / message) Since the price was the only factor that matters when I choose the company it's subject to change anytime. (meaning that I have no business relationship with it whatsoever.) 

문자를 발송하거나 카카오 알림톡을 발송하는 API 제공 업체는 2020년 4월 기준 어림잡아 10여군데가 넘는 것 같지만, 가격을 비교해본 결과 알리고 (Aligo) 라는 업체의 비용이 가장 낮은 것으로 파악되어, 이 업체의 문자발송 서비스를 이용하기 위한 패키지를 만들었다. 좀 더 저렴한 서비스 업체가 있거나, 이 패키지 사용상 문제가 발견된다면 이슈로 남겨주면 감사하겠다.

## Use-case

이 패키지는 알리고 API 를 호출하여, `단문문자발송` 및 `카카오알림톡` 에 해당하는 짧은 메시지를 1개의 전화번호에 발송하기 위한 패키지이며, 사용자의 라라벨 프로젝트에서 PHP 디펜던시 관리자인 [Composer](https://getcomposer.org/) 를 통하여 추가한 다음 해당 기능을 사용할 수 있다. 참고로, LMS/MMS 발송기능, 예약발송, 다수에게 batch 문자발송 등의 기능들은 현재 필요하지 않아 구현되어 있지 않다. 또한 카카오 알림톡 전송실패시 문자 메시지로 발송하는 fallback 기능 역시, 알리고 관리자 페이지에서 설정이 가능하기에 이 부분 구현도 생략했다.  

## 인스톨

이 패키지를 인스톨하기 위해서는 터미널에서 아래의 명령을 실행한다.

```
composer require jinseokoh/aligo
```

## config 등록

카카오 알림톡의 경우, 발송메시지를 미리 등록시키고 허가를 받는 승인과정이 있어서, 해당 templates 을 등록 후 config 파일에 반영해야만 한다.

```
php artisan vendor:publish --provider="JinseokOh\Aligo\AligoServiceProvider"
```

위 명령으로 `/config/aligo.php` 파일을 생성시킨 다음 필요한 값으로 등록해야한다. ALIGO_APP_ID, ALIGO_APP_KEY, ALIGO_PHONE_NUMBER, ALIGO_KAKAO_SENDER_KEY 값들 모두 관리자 페이지에서 찾아 복사할 수 있다. 

```php
<?php

return [
    'app_id' => env('ALIGO_APP_ID', 'your-valid-app-id'),
    'app_key' => env('ALIGO_APP_KEY', 'your-valid-app-key'),
    'phone_number' => env('ALIGO_PHONE_NUMBER', 'your-valid-phone-number'),
    'kakao_sender_key' => env('ALIGO_KAKAO_SENDER_KEY', 'your-valid-kakao-sender-key'),
    'sms_url' => 'https://apis.aligo.in',
    'kakao_url' => 'https://kakaoapi.aligo.in',
    'kakao_templates' => [
        [
            'code' => 'TB_0011',
            'subject' => '안내문',
            'message' => '인증번호는 #{OTP} 입니다.',
            'button' => '{"button":[{"name":"소크라테스","linkType":"WL","linkP":"https://socrates.com", "linkM": "https://socrates.com"}]}'
        ],
        [
            'code' => 'TB_0012',
            'subject' => '안내문',
            'message' => '안녕하세요 #{USER}님, 전화번호가 정상적으로 등록되었습니다.',
            'button' => '{"button":[{"name":"소크라테스","linkType":"WL","linkP":"https://socrates.com", "linkM": "https://socrates.com"}]}'
        ],
    ]
];

```

## `.env` 설정

Laravel 프로젝트 `.env` 파일에 아래의 키값에 대한 값을 설정한다.

```
ALIGO_APP_ID=socrates
ALIGO_APP_KEY=00000000000000000000000000000000
ALIGO_PHONE_NUMBER=02-123-0000
ALIGO_KAKAO_SENDER_KEY=0000000000000000000000000000000000000000
```

## 사용법

카카오 알림톡을 보낼 수 있는 `KakaoClient` 와 단문문자를 보낼 수 있는 `SmsClient` 가 있는데, `\JinseokOh\Aligo\ClientFactory::create('kakao')` 또는 `\JinseokOh\Aligo\ClientFactory::create('sms')` 식으로 생성하여 사용하면 된다. 

### 카카오 알림톡 발송 예제

```php
    /** @var AligoClientContract $client */
    $client = ClientFactory::create('kakao');
    $response = $client->sendMessage(
        "인증번호는 123456 입니다.",
        "010-9000-7415"
    );
```

### SMS 단문문자 발송 예제

```php
    /** @var AligoClientContract $client */
    $client = ClientFactory::create('sms');
    $response = $client->sendMessage(
        "인증번호는 123456 입니다.",
        "010-9000-7415"
    );
```

## Glitches

카카오 알림톡 템플릿에 버튼을 포함한 경우, 알림톡 전송시 에도 동일한 내용이 전달되어야만 된다. Payload 에서 `button_1` 가 빠진 경우, response 에는 "성공적으로 전송요청 하였습니다." 라는 성공 리턴값이 오지만 정작 사용자에게 전송은 되지 않으므로, 이 부분 유념하기 바란다. 또한 카카오 알림톡 전송시, `subject_1` 으로 설정한 제목 문자열은 알림톡 내에서 출력되지 않는 점도 이상했다. 이 부분 역시 알리고 API 상의 문제인 듯...  

## License

The MIT Licence (MIT).
