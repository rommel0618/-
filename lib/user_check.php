<?php
    include_once "../dbcfg.php";
    // 获取参数
    $openid = $_POST['openid'];

    // 判断用户是否注册过
    $nuser_check_sql = "select * from yhq_nuser where openid='".$openid."'";
    $nuser_check = $pdo->query($nuser_check_sql)->fetch(PDO::FETCH_ASSOC);

    if ($nuser_check) {
        $result['code'] = "1";
        echo json_encode($result,true);
        die;
    }

    $ouser_check_sql = "select * from yhq_ouser where openid='".$openid."'";
    $ouser_check = $pdo->query($ouser_check_sql)->fetch(PDO::FETCH_ASSOC);
    if ($ouser_check) {
        $result['code'] = "2";
        echo json_encode($result,true);
        die;
    }
