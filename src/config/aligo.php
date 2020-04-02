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
            'code' => 'TB_0824',
            'subject' => '안내문',
            'message' => '인증번호는 #{OTP} 입니다.',
            'button' => '{"button":[{"name":"소크라테스","linkType":"WL","linkP":"https://socrates.com", "linkM": "https://socrates.com"}]}'
        ],
        [
            'code' => 'TB_0823',
            'subject' => '안내문',
            'message' => '안녕하세요 #{고객명}님, 전화번호가 정상적으로 등록되었습니다.',
            'button' => '{"button":[{"name":"소크라테스","linkType":"WL","linkP":"https://socrates.com", "linkM": "https://socrates.com"}]}'
        ],
    ]
];
