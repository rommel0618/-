<?php
	session_start();
	// 验证发送验证码的有效期
	$session_verifytime = $_SESSION['verifytime'];
	$verify_count = 120;

	if(!empty($session_verifytime) && (time()-$session_verifytime)<$verify_count){
		$result['code'] = "-1";
        $result['info'] = '验证码已发送，请不要重复获取!!!';
        echo json_encode($result,true);
        die;
	}

	// 获取参数
    $mobile = $_POST['mobile'];
    
    // 随机生成六位随机数
    $verify_length = 6;
    $min = pow(10,($verify_length - 1));
    $max = pow(10,$verify_length) - 1;
    $verify = rand($min,$max);

	// luosimao发送短信内容
	$message = '验证码:'.$verify.'【耘禾】';

	// 调用螺丝帽接口发送短信
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD  , 'api:key-5c55a608472db15b8af4d0aa56395b4b');
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $mobile,'message' => $message));
	$send_res = curl_exec($ch);
	curl_close($ch);
	$res = json_decode($send_res, true );

	// 发送成功
	if ($res ['error'] == 0) {
		// 发送成功的话，把验证码、手机号、发送时间缓存起来
		//session_start();
		// store session data
		$_SESSION['verify'] = $verify;
		$_SESSION['mobile'] = $mobile;
		$_SESSION['verifytime'] = time();

		$result['code'] = "0";
        $result['info'] = '验证码发送成功!!!';
	} else {
		$result['code'] = "-1";
        $result['info'] = '验证码发送失败，请重试!!!';
	}
	echo json_encode($result,true);