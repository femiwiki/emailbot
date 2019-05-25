<?php
/**
 * SUBJECT : FEMIWIKI MAIL BOT
 * Author 차별없는세상(고래)
 *
 * 페미위키의 메일을 디스코드로 보내주는 디스코드용 봇 코드입니다.
 */

// 메일 발송 시각의 타임스탬프를 서버시간과 동기화합니다.
$timestamp = date('Y년 m월 d일 H시 i분 s초', $_POST['timestamp']);

// 메일 발송 내용을 Content-Type:text/plain 과 동일한 값으로 뽑아옵니다.
$mailContent = $_POST['body-plain'];

// 메일 Template 작성
$recvContents = <<<TEMPLATE

메일 시간 : {$timestamp} ({$_POST['timestamp']})
메일 발송자 : {$_POST['from']}
메일 제목 : {$_POST['subject']}
```
{$mailContent}
```
TEMPLATE;

// 전달받은 데이터 중 HTML SPECIAL 문자(특수문자)를 걸러냅니다.
// 내용에 쿼리스트링을 삽입하는 경우, 공격당할 위험이 있음.
// $recvContents = htmlspecialchars($recvContents);

// WebHook을 발송시킵니다.
$curl = curl_init();
$curlOption = array(
	CURLOPT_URL => $_SERVER['MAILBOT_URL'],
	CURLOPT_HEADER => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => http_build_query(
		array(
			'username' => "[보낸 사람] {$_POST['from']}",
			'content' => $recvContents
		)
	)
);
curl_setopt_array($curl, $curlOption);
curl_exec($curl);
