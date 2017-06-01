<div class="login-middle">
    <div class="login-middle-sec show-center">
        <div class="login-middle-sec1">
            <p><img src="/public/images/login1.png"> </p>
        </div>
        <div class="login-middle-sec2">
            <div class="float-left login-middle-sec21">
                <!--<p class="login-text1 mar-lef">赚钱快/稳/准，新一代的理财神器</p>-->
                <img src="/public/images/login2.png">
            </div>
            <form method="post" action="/Login/doLogin">
            <div class="float-left login-middle-sec22">
                <input type="hidden" name="u" value="<?php echo $u;?>" />
                <input type="text" class="block" name="phone" placeholder="请输入手机号或昵称" />
                <input class="block pwd-ab" type="password"  name="pwd1" placeholder="密码" />
                <input class="block" type="text" placeholder="请输入下图中的字符，不区分大小写" name="checkcode">
                <div class="login-yzm">
                    <!--<img src="/public/images/login-yzm.png" class="float-left">-->
                    <img id="imgcheck" style="cursor:pointer; margin: 0 4px 4px 0; vertical-align: middle;" class="float-left" src="/login/checkcode" onclick="javascript:this.src='/Login/checkcode?tm='+Math.random()" /> 
                    <span class="yzm-b float-left"> 
                    <a href="javascript:void(0);" onClick="changeCode()">看不清，换一张</a></span>
                    <div class="clear"></div>
                </div>
                <div class="login-btn">
                    <button type="submit" class="float-left login-bb">登录</button>
                    <div class="float-left auto-login">
                        <input type="checkbox" name="auto" checked>
                        <span class="auto-login-text2">下次自动登录</span>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="fo-pwd">
                    <a href="/usercenter/findPwd" class="fop">忘记密码？</a>|<a href="/Register" class="regi">立即注册</a>
                </div>
                <div class="clear"></div>
            </div>
            </form>
            <div class="clear"></div>
        </div>
    </div>
</div>
<!--footer start-->
<?php $this->load->helper("footer");?>
<!--footer end-->
<script type="text/javascript">
	$(function(){
        var $temp = $("input.pwd-ab");
        $temp.focus(function(){
            $(".login-middle-sec22>label").hide();
        });
        $temp.blur(function(){
            if($temp.val()){
                $(".login-middle-sec22>label").hide();
            } else {
                $(".login-middle-sec22>label").show();
            }
        });
	})
</script>
<script language="javascript">  
    function changeCode(){
        $("#imgcheck").attr("src",'/login/checkcode?tm='+Math.random());
    }
</script>
</body>
</html>