<link rel="stylesheet" type="text/css" href="/public/css/register.css?<?php echo rand(1000,9999)?>"></link>
<script type="text/javascript" src="/public/js/register.js?<?php echo rand(1000,9999)?>"></script>
<!--middle start -->
<div class="register-middle">
    <div class="register-middle-sec show-center">
        <div class="regmid-sec">
            <div class="regmid-sec-title">
                注册云智慧金融账户
            </div>
            <div class="regmid-sec-con">
                <div class="regmid-sec-con1">
                    <label class="text-right float-left"><span class="red">*</span>您的账号：</label>
                  
					<input type="text" class="float-left" placeholder="请输入您的手机号码" id="phone" name="phone"/>
					<p class="register-ts1 float-left" id="msgphone" style="display:none">
                    </p>
                    <div class="clear"></div>
                </div>
				<div class="regmid-sec-con1">
                    <label class="text-right float-left"><span class="red">&nbsp;</span>您的昵称：</label>
                    <div class="float-left">
                        <input type="text" class="float-left" id="username" name="username"/>
                        <p class="register-ts4 float-left">选填</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="regmid-sec-con1">
                    <label class="text-right float-left"><span class="red">*</span>登录密码：</label>
                    <div class="float-left">
						<div>
							<input type="password" id="pwd" name="pwd" class="float-left" placeholder="请输入密码"/>
							<p class="register-ts3 float-left" id="msgpwd" style="display:none"></p>
							<div class="clear"></div>
						</div>
							
						<p class="register-ts2">
							<span>低</span>
							<span>中</span>
							<span>高</span>
						</p>
                        
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="regmid-sec-con1">
                    <label class="text-right float-left"><span class="red">*</span>确认登录密码：</label>
					<input type="password" id="repwd" name="repwd" class="float-left" placeholder="请确认密码"/>
					<p class="register-ts3 float-left" id="msgrepwd" style="display:none"></p>
             
                    <div class="clear"></div>
                </div>
                <div class="regmid-sec-con1">
                    <label class="text-right float-left"><span class="red">*</span>图片验证码：</label>
                    <div class="float-left">
                        <input type="text" class="float-left phone-yzm" placeholder="图片验证码" id="piccode" name="piccode"/>
                        <img id="imgcheck" style="cursor: pointer; vertical-align: middle; margin: 5px 4px 4px 10px; height:35px;" class="float-left" src="/login/checkcode" onclick="javascript:this.src='/Login/checkcode?tm='+Math.random()" /> 
                        <span class="yzm-b float-left"> 
                        <a href="javascript:void(0);" onClick="changeCode()">换一张</a></span>
                        <p class="register-ts5 float-left" id="msgpiccode" style="display:none"></p>
                        <div class="clear"></div>
                    </div>
                    
                    <div class="clear"></div>
                </div>
                <div class="regmid-sec-con1">
                    <label class="text-right float-left"><span class="red">*</span>短信验证码：</label>
                    <div class="float-left">
						<input type="text" class="float-left phone-yzm" placeholder="手机验证码" id="phonecode" name="phonecode"/>
						<input class="float-left reget" type="button" id="zphone" value="获取短信验证码">
						<p class="register-ts5 float-left" id="msgphonecode" style="display:none"></p>
						<div class="clear"></div>
                    </div>
                    
                    <div class="clear"></div>
                </div>
                <div class="regmid-sec-con1">
                    <label class="text-right float-left"></label>
                    <div class="float-left relative" style="height:40px;">
                        <input type="checkbox" class="agreeXy">
                        <span class="agree-text">已阅读并接受《云智慧金融注册协议》</span>
                    </div>
                        <p class="register-read float-left" id="msgread" style="display:none">请确认是否同意该协议</p>
                    <div class="clear"></div>

                </div>
                <div class="regmid-sec-con1">
                    <label class="text-right float-left">邀请人手机号码：</label>
                    <div class="float-left">
                        <input type="text" class="float-left" name="" placeholder="请输入邀请人手机号码"/>
                        <p class="register-ts4 float-left">选填</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <button type="submit" class="agreeBtn" id="agreeBtn">立即注册</button>
            </div>
        </div>
    </div>
</div>
<!--middle end -->
<!--footer start-->
<?php $this->load->helper("footer");?>
<!--footer end-->
<script language="javascript">  
    $(function(){
        $('#piccode').keyup(function () {
            if ($(this).val().replace(/[ ]/g,"").length==4) {
                $("#zphone").removeClass("reget").addClass("reget_go");
                $("#zphone").attr("onClick","get_mobile_code(this)");
            }else{
                $("#zphone").removeClass("reget_go").addClass("reget");
                $("#zphone").removeAttr("onClick");
            }
        })

        $("#phone").blur(function () {
            if ($(this).val().trim().length != 11) {
                $('#msgphone').show();
                $('#msgphone').text("用户名不能为空");
                $('#msgphone').addClass("register-ts1").removeClass("register-ts1_1");
            }else{
                $('#msgphone').show();
                $('#msgphone').text("");
                $('#msgphone').addClass("register-ts1_1").removeClass("register-ts1");
            }
        })  
        $('#pwd').blur(function () {
            if ($(this).val().trim().length < 6) {
                $('#msgpwd').show();
                $('#msgpwd').text("密码不能少于6个字符");
                $('#msgpwd').addClass("register-ts3").removeClass("register-ts3_1");
            }else{
                $('#msgpwd').show();
                $('#msgpwd').text("");
                $('#msgpwd').addClass("register-ts3_1").removeClass("register-ts3");
            }
        })  
        $('#repwd').blur(function () {  
            if ($(this).val().trim() != $('#pwd').val().trim() || $(this).val().trim().length==0) {
                $('#msgrepwd').show();
                $('#msgrepwd').text("两次密码输入不一致");
                $('#msgrepwd').addClass("register-ts3").removeClass("register-ts3_1");
            }else{
                $('#msgrepwd').show();
                $('#msgrepwd').text("");
                $('#msgrepwd').addClass("register-ts3_1").removeClass("register-ts3");
            }  
        })
        $('#piccode').blur(function () {
            if ($(this).val().length!=4) {
                $('#msgpiccode').show();
                $('#msgpiccode').text("图片验证码不能为空！");
            }else{
                $('#msgpiccode').hide();
            } 
        })
        $('#phonecode').blur(function () {
            if ($(this).val().length!=4) {
                $('#msgphonecode').show();
                $('#msgphonecode').text("短信验证码不能为空！");
            }else{
                $('#msgphonecode').hide();
            } 
        })

        $('.agreeXy').click(function(){
            if($('.agreeXy').is(":checked") == true){
                $('#msgread').hide();
            }else{
                $('#msgread').show();
            }
        })

        $('.agreeBtn').click(function(){
            if ($("#phone").val().trim().length != 11) {
                $('#msgphone').show();
                $('#msgphone').text("用户名不能为空");
            }else if ($('#pwd').val().trim().length < 6) {
                $('#msgpwd').show();
                $('#msgpwd').text("密码不能少于6个字符");
            }else if ($('#repwd').val().trim() != $('#pwd').val().trim() || $('#pwd').val().trim().length==0) {
                $('#msgrepwd').show();
                $('#msgrepwd').text("两次密码输入不一致");
            }else if ($('#piccode').val().length!=4) {
                $('#msgpiccode').show();
                $('#msgpiccode').text("图片验证码不能为空！");
            }else if ($('#phonecode').val().length!=4) {
                $('#msgphonecode').show();
                $('#msgphonecode').text("短信验证码不能为空！");
            }else if($('.agreeXy').is(":checked") == false){
                $('#msgread').show();
            }else{
                $.ajax({
                    url:'/Register/register',
                    data:{phone:$('#phone').val(), username:$('#username').val(), pwd1:$('#pwd').val(), repwd:$('#repwd').val(), phonecode:$('#phonecode').val(), sendcode:$('#sendcode').val()},  
                    error:function(){
                        alert("error occured!!!");
                    },  
                    success:function(data){
                        if(data=='success'){
                            popBox('bob',"注册成功，正在登陆中......",'','','','','600','/','');
                            $('.modal-close1').hide();
                            $('.inline-block').hide();
                            setTimeout("window.location.href='/'",1500);
                        }else{
                            alert(data);
                        }
                    }
                });
            }
        });

        
        $(".modal-close").click(function(){
            $(".modal").hide();
            $(".imsec2-ul-out").hide();
        });

    });

</script>  

<script language="javascript">
    function get_mobile_code(btn){
        $.post('/register/checkPiccode', {piccode:jQuery.trim($('#piccode').val())}, function(msg) {
            var obj = eval('('+msg+')');//转换后的JSON对象
            if(obj.code==0){
                $.post('/register/sendmsg', {phone:jQuery.trim($('#phone').val()),piccode:jQuery.trim($('#piccode').val())}, function(msg) {
                    var obj = eval('('+msg+')');//转换后的JSON对象
                    if(obj.code==0){
                        RemainTime();
                        $("#sendcode").val(obj.sendCode);
                    }else{
                        alert(jQuery.trim(unescape(obj.msg)));
                    }
                });
            }else{
                alert(jQuery.trim(unescape(obj.msg)));
            }
        });
        
    };

    var iTime = 59;
    var Account;
    function RemainTime(){
        document.getElementById('zphone').disabled = true;
        var iSecond,sSecond="",sTime="";
        if (iTime >= 0){
            iSecond = parseInt(iTime%60);
            iMinute = parseInt(iTime/60)
            if (iSecond >= 0){
                if(iMinute>0){
                    sSecond = iMinute + "分" + iSecond + "秒";
                }else{
                    sSecond = iSecond + "秒";
                }
            }
            sTime=sSecond;
            if(iTime==0){
                clearTimeout(Account);
                sTime='获取手机验证码';
                iTime = 59;
                document.getElementById('zphone').disabled = false;
            }else{
                Account = setTimeout("RemainTime()",1000);
                iTime=iTime-1;
            }
        }else{
            sTime='没有倒计时';
        }
        document.getElementById('zphone').value = sTime;
    }

    function login(){
        window.location="/Login";
    }
</script>
<script language="javascript">  
    function changeCode(){
        $("#imgcheck").attr("src",'/login/checkcode?tm='+Math.random());
    }
</script>
</body>
</html>
