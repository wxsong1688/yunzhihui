<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>用户 修改密码</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/changePwd.css"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/changePwd.js"></script>
	
    <!--[if lte IE 6]>
    <script type="text/javascript">
        alert('您的浏览器版本太低了');
        window.opener=null;
        window.open('','_self','');
        window.close();
    </script>
    <![endif]-->
</head>
<body>
<div class="changePwd user-all" id="changePwd">
    <div class="user-section user-right-height">
        <p class="usercenter-title">密码管理</p>
        <div class="changePwd-content">
            <p class="changePwd-con-title">修改密码</p>
                <input type="hidden" name="uid" id="uid" value="<?php echo $userInfo['uid'];?>" />
                <div class="changePwd-part">
                    <div class="old-pwd">
                        <p class="float-left pwd-text-top">原始密码：</p>
                        <div class="float-left">
                            <input type="password" id="old_pwd" name="old_pwd" />
                        </div>
                        <div class="float-left" id="oldpwd_message"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="old-pwd">
                        <p class="float-left pwd-text-top">新密码：</p>
                        <div class="float-left">
                            <input type="password" id="new_pwd" name="new_pwd" />
                        </div>
                        <div class="float-left" id="newpwd_message"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="old-pwd">
                        <p class="float-left pwd-text-top">确认新密码：</p>
                        <div class="float-left">
                            <input type="password" id="renew_pwd" name="renew_pwd" />
                        </div>
                        <div class="float-left" id="renew_message"></div>
                        <div class="clear"></div>
                    </div>
                    <button type="submit" class="changePwd-button">确定</button>
                </div>
        </div>
    </div>
</div>
<div class="pwd-zz-outer"></div>
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">
	change_height();
</script>
</body>
</html>