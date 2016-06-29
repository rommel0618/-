<?php
	include_once "../dbcfg.php";
	// 获取参数
	$openid = $_POST['openid'];
	$name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $verify = $_POST['verify'];
    $owner = $_POST['owner'];
    $coupon_no = $_POST['coupon'];

    // 判断用户是否注册过
     // 判断用户是否注册过
    $nuser_check_sql = "select * from yhq_nuser where openid='".$openid."'";
    $nuser_check = $pdo->query($nuser_check_sql)->fetch(PDO::FETCH_ASSOC);

    $ouser_check_sql = "select * from yhq_ouser where openid='".$openid."'";
    $ouser_check = $pdo->query($ouser_check_sql)->fetch(PDO::FETCH_ASSOC);

    if ($nuser_check || $ouser_check) {
        $result['code'] = "0";
        $result['info'] = "您已提交过信息,请不要重复提交";
        echo json_encode($result,true);
        die;
    }

    session_start();
	// 验证短信验证码
	// 验证码有效时长
	$verify_count = 120;
	$session_mobile = $_SESSION['mobile'];
	$session_verify = $_SESSION['verify'];
	$session_verifytime = $_SESSION['verifytime'];

	if((time()-$session_verifytime)>$verify_count){
		$result['code'] = "-1";
        $result['info'] = '验证码已过期!!!';
        echo json_encode($result,true);
        die;
	}
	if(!($mobile==$session_mobile && $verify==$session_verify)){
		$result['code'] = "-1";
        $result['info'] = '验证码错误!!!';
        echo json_encode($result,true);
        die;
	}

	// 判断优惠券是否合法
	$coupon_check_sql = "select * from yhq_yhq where yhq_no='".$coupon_no."' and is_use=0";
	$coupon_check = $pdo->query($coupon_check_sql)->fetch(PDO::FETCH_ASSOC);
    if (!$coupon_check) {
        $result['code'] = "-1";
        $result['info'] = "非法的优惠券!!!";
        echo json_encode($result,true);
        die;
    }

	// 录入到数据表中
	$coupon = $_POST['coupon'];
	$sql = "insert into yhq_ouser(openid,name,mobile,coupon_no) values('".$openid."','".$name."','".$mobile."','".$coupon_no."')";
    $res_user=$pdo->exec($sql);

	// 注册成功，更新当前优惠券为不可用
	if ($res_user) {
		$yhq_sql="update yhq_yhq set is_use=1 where yhq_no='".$coupon_no."'";
		$res_yhq=$pdo->exec($yhq_sql);
		
		$result['code'] = "0";
        $result['info'] = '注册成功!!!';
	} else {
		$result['code'] = "-1";
        $result['info'] = '注册失败，请重试!!!';
	}
	echo json_encode($result,true);

			

			