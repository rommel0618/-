<?php
    include_once "../dbcfg.php";
    // 获取参数
    $openid = $_POST['openid'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $verify = $_POST['verify'];
    $owner = $_POST['owner'];

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

    // 根据不同的用户，录入到不同的数据表中
    if ($owner == 0) {
        $sql = "insert into yhq_nuser(openid,name,mobile) values('".$openid."','".$name."','".$mobile."')";
    }
    $res_user=$pdo->exec($sql);

    // 注册成功，更新当前优惠券为不可用
    if ($res_user) {
        $result['code'] = "0";
        $result['info'] = '注册成功!!!';
    } else {
        $result['code'] = "-1";
        $result['info'] = '注册失败，请重试!!!';
    }
    echo json_encode($result,true);