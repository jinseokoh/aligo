# Laravel notification channel for Aligo

[![Total Downloads](https://poser.pugx.org/jinseokoh/aligo/downloads)](https://packagist.org/packages/jinseokoh/aligo)
[![License](https://poser.pugx.org/jinseokoh/aligo/license)](https://packagist.org/packages/jinseokoh/aligo)

이 패키지는 [Aligo](https://smartsms.aligo.in/) 문자발송 API 를 이용하여 `SMS/LMS` 및 `카카오알림톡` 문자 메시지를 발송하기 위한 PHP Laravel 패키지이다. v2 에서는 Laravel 커스텀 Notification 딜리버리 채널로 변경했기 때문에, Notification 클래스의 via 메서드를 이용해 문자발송이 가능해졌다.  

Among a handful of Text messaging API providers in Korea as of April, 2020 [Aligo](https://smartsms.aligo.in/) is the hands down winner in terms of price. (KRW 8.4 / message) Since the price was the only factor that matters when I choose the company it's subject to change anytime. (meaning that I have no business relationship with it whatsoever.) 

문자를 발송하거나 카카오알림톡을 발송하는 API 제공 업체는 2020년 기준 어림잡아 10여군데가 넘는 것 같지만, 가격을 비교해본 결과 알리고 (Aligo) 라는 업체의 비용이 가장 낮은 것으로 파악되어, 이 업체의 문자발송 서비스를 이용하기 위한 패키지를 만들었다. 좀 더 저렴한 서비스 업체가 있거나 이 패키지 사용상 문제가 발견된다면, 이슈 트래커를 이용해 글을 남겨주면 감사하겠다.

## Contents

- [Laravel notification channel for Aligo](#laravel-notification-channel-for-aligo)
  - [Contents](#contents)
  - [Use-case](#use-case)
  - [Install](#install)
    - [Setting Up](#setting-up)
  - [SMS/LMS 문자 보내기 사용법](#smslms-문자-보내기-사용법)
    - [SMS/LMS 문자 설정시 사용가능한 메서드들 (모두 fluent call chain 이 가능)](#smslms-문자-설정시-사용가능한-메서드들-모두-fluent-call-chain-이-가능)
  - [카카오알림톡 보내기 사용법](#카카오알림톡-보내기-사용법)
    - [카카오알림톡 문자 설정시 사용가능한 메서드들 (모두 fluent call chain 이 가능)](#카카오알림톡-문자-설정시-사용가능한-메서드들-모두-fluent-call-chain-이-가능)
      - [카카오알림톡 템플릿](#카카오알림톡-템플릿)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)

## Use-case

이 패키지는 1명의 수신자에게 `SMS/LMS` 및 `카카오알림톡` 문자를 발송하기 위한 use-case 에서 사용하기 위해 만들었기 때문에, `MMS` 발송기능, 예약발송, 다수의 수신자에게 문자발송 등의 기능들은 현재 구현되어 있지 않다.  

## Install

```
composer require jinseokoh/aligo
```

### Setting Up

`config/services.php` 에 아래 코드를 추가한 다음

```
    'aligo' => [
        'app_id' => env('ALIGO_APP_ID'),
        'app_key' => env('ALIGO_APP_KEY'),
        'sms_from' => env('ALIGO_REGISTERED_PHONE'),
        'kakao_key' => env('ALIGO_KAKAO_CHANNEL_KEY'),
    ],
```
    
`.env` 에 자신의 계정 정보를 추가한다. 

```
ALIGO_APP_ID=socrates
ALIGO_APP_KEY=00000000000000000000000000000000
ALIGO_REGISTERED_PHONE=02-123-0000
ALIGO_KAKAO_CHANNEL_KEY=0000000000000000000000000000000000000000
```

> 보다 자세한 정보는 https://smartsms.aligo.in/admin/api/info.html 에서 찾아 볼 수 있다.

## SMS/LMS 문자 보내기 사용법

`AligoTextChannel` 을 사용하려면, Notification 클래스에 아래의 코드를 추가한다:

```
    public function via($notifiable)
    {
        return [AligoTextChannel::class];
    }
 
    public function toAligoText($notifiable)
    {
        return (new AligoTextMessage())
            ->content('이 패가 단풍이 아니라는 거에 내 돈 모두하고 내 손모가질 건다. 쫄리면 뒈지시던지.')
            ->short()
            ->debug();
    }
```

`AligoTextMessage` 는 chaining 이 가능한 아래의 메서드들을 갖는다.

### SMS/LMS 문자 설정시 사용가능한 메서드들 (모두 fluent call chain 이 가능)

``content($string)``. 보낼 메시지를 지정한다.

``short()``. 메시지 길이가 90 바이트 이상되는 경우, SMS 으로 보내기 위해서 90 바이트 이상되는 부분을 짤라버린다.

``debug()``. 테스트 모드로 설정한다.

## 카카오알림톡 보내기 사용법
 
> 카카오알림톡은 SMS/LMS 보다 저렴하게 카카오톡으로 문자를 보내는 서비스이며, 보다 자세한 정보는 https://smartsms.aligo.in/admin/api/info.html 에서 찾아볼 수 있다.

`AligoKakaoChannel` 을 사용하려면, Notification 클래스에 아래의 코드를 추가한다:
```
    public function via($notifiable)
    {
        return [AligoKakaoChannel::class];
    }
 
    public function toAligoKakao($notifiable)
    {
        return (new AligoKakaoMessage())
            ->code('TB_0824')
            ->replacements(['오진석', '대한통운', '오전11시', '123-456-7890'])
            ->debug();
    }
```

`AligoKakaoMessage` 는 chaining 이 가능한 아래의 메서드들을 갖는다.

### 카카오알림톡 문자 설정시 사용가능한 메서드들 (모두 fluent call chain 이 가능)

``code($string)``. 알리고에 등록되어 있는 카카오알림톡 템플릿 코드를 지정한다.

``replacements()``. 카카오알림톡 메시지의 `#{이름}` 부분과 대체시킬 값으로 구성된 배열을 지정한다.  

``fallback()``. 카카오알림톡 발송이 실패한 경우, 문자로 전환하여 전송한다.

``debug()``. 테스트 모드로 설정한다.

#### 카카오알림톡 템플릿

카카오알림톡 문자메시지는 템플릿 형태로 미리 등록하는 과정이 필요하며 스팸방지를 위한 사전 승인도 필요하다. 템플릿에 등록한 카카오알림톡 메시지가 아래와 같다면,

```
#{고객명} 고객님! #{택배회사명}입니다. #{택배배송시간} 택배를 배달할 예정입니다. 운송장번호 : #{송장번호}
```

이 예제 메시지의 경우, `#{이름}` 형태의 슬롯이 모두 4개가 있기 때문에, 체이닝 메서드 `replacements()` 의 인자값으로
`['오진석', '대한통운', '오전11시', '123-456-7890']` 와 같이 4개의 String 으로 이뤄진 배열이 필요하다. 모든 슬롯은 순차적으로 대체가 이뤄지며, 
최종적으로는 아래와 같은 카카오알림톡 문자가 발송된다.

```
오진석 고객님! 대한통운입니다. 오전11시 택배를 배달할 예정입니다. 운송장번호 : 123-456-7890
```
 
만일, 슬롯의 갯수와 `replacements()` 인자로 전달된 배열의 갯수가 다른 경우, Exception 이 발생하며, 문자가 발송되지 않는다.  

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Jinseok Oh](https://github.com/jinseokoh)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
