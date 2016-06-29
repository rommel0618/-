<?php
    include_once "../dbcfg.php";
    // 获取参数
    $openid = $_POST['openid'];
    $remark = $_POST['remark'];
    $sql="update yhq_ouser set remark='".$remark."' where openid='".$openid."'";
    $res=$pdo->exec($sql);

    if ($res) {
        $result['code'] = "0";
        $result['info'] = '信息发布成功!!!';
    } else {
        $result['code'] = "-1";
        $result['info'] = '信息发布失败，请重试!!!';
    }
    echo json_encode($result,true);