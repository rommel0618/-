var InterValObj; //timer变量，控制时间
var count = 120; //间隔函数，1秒执行
var curCount;//当前剩余秒数
function sendMessage(mobile) {
  　curCount = count;
　　//设置button效果，开始计时
    $("#btn_getVerify").attr("disabled", "true");
    $("#btn_getVerify").val(curCount + "秒");
    InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
　　//向后台发送处理数据
     $.ajax({
        type : "POST",
        url : "lib/getVerify.php",
        data : {
            mobile:mobile
        },
        dataType:"json",
        success : function(msg){
            // alert(msg.info);
            if (msg.code == -1) {
                alert(msg.info);
            }
        },
        error : function(){
            alert("网络异常,请重试！");
            return;
        }
    }); 
}

//timer处理函数
function SetRemainTime() {
    if (curCount == 0) {                
        window.clearInterval(InterValObj);//停止计时器
        $("#btn_getVerify").removeAttr("disabled");//启用按钮
        $("#btn_getVerify").val("重新发送验证码");
    }else {
        curCount--;
        $("#btn_getVerify").val(curCount + "秒");
    }
}

// 验证电话号码
function mobileCheck(mobile){
  // 手机号非11位
  if(mobile.length!=11){
      return false;
  }
  // 非法手机号
  var myreg = /(130|131|132|133|134|135|136|137|138|139|150|151|152|153|155|156|157|158|159|168|170|177|180|181|182|183|185|186|187|188|189)[\d]{8}/;
  if(!myreg.test(mobile)){
      return false;
  }
  return true;
}

// 验证邮箱
function emailCheck(email){
    var myreg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;  
    if (!myreg.test(email)) {  
        return false;  
    }  
    return true;
}

// 验证身份证号
function idcardnoCheck(idcardno){
    // 身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X  
    var idcardnoMyreg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;  
    if(idcardnoMyreg.test(idcardno)){
        return true;
    }else{
        return false;
    }
}

// 判断参数是否为空
function paramsEmptyCheck(params){
    var params_arry = params.split(",");
    var params_length = params_arry.length;
    for (var i = 0; i < params_length; i++) {
        if (params_arry[i] == "" || params_arry[i] == null) {
            return true;
        }else{
            continue;
        }
    }
    return false;
}       

