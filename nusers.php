<?php 
    // 连接数据库
    require_once "dbcfg.php";
    $min_count = 5;

    $ouser_sql = "select * from yhq_ouser";
    $ousers_all = $pdo->query($ouser_sql)->fetchAll(PDO::FETCH_ASSOC);
    $ousers_count = count($ousers_all);
    session_start();

//     session_unset();
// session_destroy();die;

    // 获取今天起始时间戳
    $today_start = strtotime(date('Y-m-d'));
    // 获取今天结束时间戳(明天起始时间戳)
    $today_end = strtotime(date('Y-m-d',strtotime('+1 day')));
    // 获取当前戳
    $curtime = time();
    $ousers_sessons;

    // 人数不够，全部显示
    if ($ousers_count > $min_count) {
        // 如果是在当天，用户不变，读取session，否则更换随机5条用户信息,并置换原来的session的值
        if ($curtime>=$today_start && $curtime<=$today_end) {
            if (empty($_SESSION['ousers'])) {
                $ousers;
                $sql = "select * from yhq_ouser order by rand() limit ".$min_count;
                $ousers = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                foreach ($ousers as $key1 => $ouser) {
                    $ousers_sessons_init[$key1] = $ouser;
                    $ousers[$key1] = $ouser;
                }
                $_SESSION['ousers'] = $ousers_sessons_init;
            }else{
                $ousers_sessons_array = $_SESSION['ousers'];
                $ousers;
                foreach ($ousers_sessons_array as $key2 => $ouser) {
                    $ousers[$key2] = $ouser;
                }
            }
            
        }else{
            session_unset();
            session_destroy();
            $ousers;
            $sql = "select * from yhq_ouser order by rand() limit ".$min_count;
            $ousers = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            foreach ($ousers as $key3 => $ouser) {
                $ousers_sessons[$key3] = $ouser;
                $ousers[$key3] = $ouser;
            }
            $_SESSION['ousers'] = $ousers_sessons;
        }
    }else{
        foreach ($ousers_all as $key4 => $ouser) {
            $ousers[$key4] = $ouser;
        }
    }

    $openid = $_GET['openid']; 
    $nuser_sql = "select * from yhq_nuser where openid='".$openid."'";
    $nuser = $pdo->query($nuser_sql)->fetch(PDO::FETCH_ASSOC);
    
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <title>砍价助力</title>
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/index.css" rel="stylesheet">
        
    </head>
    <body>
        <div class="container" style="margin:20px;5px;5px;10px;">
            <!-- 助力人列表开始 --> 
            <div class="row">
                <div class="col-xs-12 col-md-8 col-sm-6 center" style="height:300px; overflow:scroll;">
                    <table class="table table-bordered">
                        <thead>
                            <th class="center" width="30%">姓名</th>
                            <th class="center" width="30%">电话</th>
                            <th class="center" width="40%">留言</th>
                        </thead>
                        <tbody> 
                            <?php foreach ($ousers as $key => $ouser): ?>
                                <tr>
                                    <td><?php echo $ouser['name'];?></td>
                                    <td><a href="tel:<?php echo $ouser['mobile'];?>"></a><?php echo $ouser['mobile'];?></td>
                                    <td><?php echo $ouser['remark'];?></td>
                                   
                                </tr>
                            <?php endforeach ?>
                        </tbody> 
                    </table>
                </div>
            </div>
        </div>
        <!-- 助力人列表结束 --> 
        <div class="row">
            <div class="col-xs-12 col-md-8 col-sm-6 center">
                <hr style="height:5px;border:none;border-top:5px ridge green;" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="col-xs-12">
                    <input type="button" class="form-control btn-danger" id="btn_public" value="发布" data-toggle="modal" data-target="#myModal">
                </div>
            </div>
        </div>

        <!-- 表单信息开始 -->
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top:height/2;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">请填写发布信息</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="bzr_name" class="control-label">发布信息:</label>
                                <textarea class="form-control" name="remark" id="remark" name="" cols="30" rows="10"><?php echo $nuser['remark'];?></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" id="btn_remard_tj">提交</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 表单信息结束 -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery-2.1.4.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jweixin-1.0.0.js"></script>
        <script src="js/tool.js"></script>
        <script>
            var openid = "<?php echo $openid; ?>";
            // 提交发布信息
            $("#btn_remard_tj").bind("click",function(){
                var remark = $("#remark").val();
                // 验证参数是否为空
                if (paramsEmptyCheck(remark)) {
                    alert("信息不完整，请完善信息!!!");
                    return;
                }

                $.ajax({
                    type : "POST",
                    url : "lib/nuser_public.php",
                    data : {
                        openid:openid,
                        remark:remark
                    },
                    dataType:"json",
                    success : function(msg){
                        alert(msg.info);
                        if (msg.code == 0) {
                          location.reload(true);
                          // 加这一句，防止跳转不了
                          return false;
                        }
                    },
                    error : function(){
                        alert("网络异常,请重试！");
                        return;
                    }
                })
                
            })
        </script>
        
    </body>
</html>