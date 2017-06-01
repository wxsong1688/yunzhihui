<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>云智慧后台管理系统</title>
<link rel="stylesheet" href="/static_develop/css/style.default.css" type="text/css" />
<script type="text/javascript" src="/static_develop/js/plugins/jquery-1.7.min.js"></script>
</head>

<body class="loginpage">
    <div class="loginbox">
        <div class="loginboxinner">
            <div class="logo">
                <h1 class="logo"><span>云智慧</span></h1>
                <span class="slogan">后台管理系统</span>
            </div><!--logo-->
            <br clear="all" /><br />
            <div class="nousername">
                <div class="loginmsg">用户名或密码错误！</div>
            </div><!--nousername-->
           
            <div class="username">
                <div class="usernameinner">
                    <input type="text" name="username" id="username" autocomplete="false" />
                </div>
            </div>
            <div class="password">
                <div class="passwordinner">
                    <input type="password" name="password" id="password" autocomplete="false" />
                </div>
            </div>
            <button id="loginSubmit">登录</button>
            <div class="keep"><a href="/">忘记密码</a></div>
        </div><!--loginboxinner-->
    </div><!--loginbox-->
</body>
</html>
<script>
function loadLlq()
{
    if(!+[1,]){
        alert("请使用其他浏览器,该后台不支持ie");return;
    }
}
loadLlq();
document.onkeydown = function(evt){
   　 var evt = window.event?window.event:evt;
    　if (evt.keyCode==13) {
          $("#loginSubmit").click();
    　}
   }

jQuery(document).ready(function(){
    ///// ADD PLACEHOLDER /////
    jQuery('#username').attr('placeholder','Username');
    jQuery('#password').attr('placeholder','Password');
    
    jQuery("#loginSubmit").click(function(){
        var username = jQuery("#username").val();
        var password = jQuery("#password").val();
        if(username == '')
        {
            jQuery(".nousername .loginmsg").text('用户名不能为空！');
            jQuery(".nousername").css('display','block');
            return false;
        }
        if(password == '')
        {
            jQuery(".nousername .loginmsg").text('密码不能为空！');
            jQuery(".nousername").css('display','block');
            return false;
        }
        
        jQuery.ajax({
            url: '/Login/authzUser',
            data: {'username':username,'password':password},
            type: "post",
            dataType: "json",
            success: function (d) {
                // console.info(d);return false;
                if(d.code==0){
                    jQuery(".nousername .loginmsg").text(d.msg);
                    jQuery(".nousername").css('display','block');
                    return false;
                }else{
                    window.location = "/Welcome?id_succ="+d.id_succ;
                }
            }
        });

    })
})
</script>