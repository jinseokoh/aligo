# Unofficial Aligo SMS package for Laravel

[![Total Downloads](https://poser.pugx.org/jinseokoh/aligo/downloads)](https://packagist.org/packages/jinseokoh/aligo)
[![License](https://poser.pugx.org/jinseokoh/aligo/license)](https://packagist.org/packages/jinseokoh/aligo)

Laravel 프로젝트에서 사용할 수 있는 [Aligo](https://smartsms.aligo.in/) 용 PHP Laravel 패키지이다.

Among a handful of Text messaging API providers in Korea as of April, 2020 [Aligo](https://smartsms.aligo.in/) is the hands down winner in terms of price. (KRW 8.4 / message) Since the price was the only factor that matters when I choose the company it's subject to change anytime. (meaning that I have no business relationship with it whatsoever.) 

문자를 발송하거나 카카오 알림톡을 발송하는 API 제공 업체는 2020년 4월 기준 어림잡아 10여군데가 넘는 것 같지만, 가격을 비교해본 결과 알리고 (Aligo) 라는 업체의 비용이 가장 낮은 것으로 파악되어, 이 업체의 문자발송 서비스를 이용하기 위한 패키지를 만들었다. 좀 더 저렴한 서비스 업체가 있거나, 이 패키지 사용상 문제가 발견된다면 이슈로 남겨주면 감사하겠다.

이 패키지는 문자발송 및 카카오 알림톡 API 를 호출하는 클라이언트 패키지이며, 사용자들의 라라벨 프로젝트에서 PHP 디펜던시 관리자인 [Composer](https://getcomposer.org/) 를 통하여 문자발송 로직을 간단히 수행하기 위하여 만들었다. 상세한 API 스펙은 해당사 홈페이지를 참고하기 바란다.

## 인스톨

이 패키지를 인스톨하기 위해서는 터미널에서 아래의 명령을 실행한다.

```
composer require jinseokoh/aligo
```

## 사용법

Laravel 5.5 부터 제공하는 Package Auto-Discovery 기능으로, 이 패키지를 인스톨함과 동시에 ServiceProvider 와 Facade 등록이 자동으로 된다.

### 설정

Laravel 프로젝트 `.env` 파일에 BootPay 에 연관된 아래의 3개 Key/Value 값을 추가해야만 한다. 해당사 문자API 신청/인증 페이지에 가면, API 용 Identifier (ALIGO_APP_ID) 와 발급키 (ALIGO_APP_KEY) 를 받을 수 있다. 발송서버 IP 대역 및 발신번호 등록도 실제 문자발송에 앞서 미리 수행해야하는 부분이다.

```
ALIGO_URL=https://apis.aligo.in
ALIGO_APP_ID=whatsupkorea
ALIGO_APP_KEY=t4ae0dx8tgb889ek5444wcsgt3urb0rb
ALIGO_PHONE_NUMBER=0200820082
```

### 예제

현재는 SMS 단문문자보내기 `sendSMS`, LMS 장문문자보내기 `sendLMS`, 남은 문자갯수를 확인할 수 있는 `remain` API 만 지원한다.

일반적으로 Controller 에서, `JinseokOh\Aligo\AligoHanlder` 클래스를 DI 를 이용해 주입하거나, Aligo facade 를 이용하여 생성 후, 필요한 API 의 호출을 아래의 샘플과 같이 작성한다. 

#### sendSMS() 호출 샘플코드

```php
    public function sendSMS(
        TextMessageRequest $request,
        AligoHandler $aligoHandler
    ): JsonResponse {
        $message = $request->getMessage();
        $phoneNumbers = $request->getPhoneNumbers();

        try {
            $response = $aligoHandler->sendSMS($message, $phoneNumbers);
        } catch (\Throwable $e) {
            \Log::critical("[LOG] {$e->getMessage()}");
            throw $e;
        }
        :
        // 추가로직
        :
    }
```

#### remain() 호출 샘플코드

```php
    public function remainingCredit(
        AligoHandler $aligoHandler
    ): JsonResponse {
        try {
            $response = $aligoHandler->remain();
        } catch (\Throwable $e) {
            \Log::critical("[LOG] {$e->getMessage()}");
            throw $e;
        }
        :
        // 추가로직
        :
    }
```

## To-Do

카카오 알림톡 및 문자 예약 발송 등의 API 추가

## License

The MIT Licence (MIT).
