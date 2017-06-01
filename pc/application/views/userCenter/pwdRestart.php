<link rel="stylesheet" type="text/css" href="/public/css/findPwd.css"></link>
<link rel="stylesheet" type="text/css" href="/public/css/pwdRestart.css"></link>

<!--middle start -->
<div class="findPwd-middle">
        <div class="findPwd-middle-sec show-center">
            <div class="findPwd-middle-section1 text-center findPwd-title">找回密码</div>
            <div class="findPwd-middle-section2">
                <div class="findPwd-sec2-part show-center">
                    <p class="float-left text-center infor-confirm"><i>1&nbsp;信息确认</i></p>
                    <p class="float-left text-center infor-confirm faccurent"><i>2&nbsp;密码重置</i></p>
                    <p class="float-left text-center infor-confirm"><i>3&nbsp;重置成功</i></p>
                    <div class="clear"></div>
                </div>
                <div class="findPwd-sec2-part1 show-center">
                    <div class="findPwd-account findPwd-top">
                        <label class="inline-block text-right">输入新密码：</label>
                        <input type="password" id="newpwd"/>
                        <div class="pwdRestart-safe">
                            <span class="pwd-safe text-center safe-current">低</span>
                            <span class="pwd-safe text-center">中</span>
                            <span class="pwd-safe text-center">高</span>
                        </div>
                    </div>
                    <div class="findPwd-account findPwd-top">
                        <label class="inline-block text-right">确认新密码：</label>
                        <input type="password" id="renewpwd"/>
                        <p class="findPwd-sm pwdRestart-sm"></p>
                    </div>
                    <div class="text-center"><input type="button" id="findPwdNext2" value="下一步"/></div>
                    <input type="hidden" id="phone" name="phone" value="<?php echo $p;?>" />
                </div>
            </div>
        </div>
</div>
<!--middle end -->
<script type="text/javascript">
$("#findPwdNext2").click(function(){
    if ( $("#newpwd").val().trim().length < 6 ) {
        $('.findPwd-sm').text("密码不得少于6位").css({"color":"red"});return;
    }
    if ( $('#renewpwd').val() != $("#newpwd").val() ) {
        $('.findPwd-sm').text("两次密码不一致").css({"color":"red"});return;
    }
    $.ajax({
        type:"post",
        dataType:"json",
        url:'/usercenter/pwdRestartSubmit',
        data:{phone:$('#phone').val(),newpwd:$('#newpwd').val(),renewpwd:$('#renewpwd').val()},  
        // error:function(){  
        //     alert("error occured!!!");  
        // },
        success:function(data){
            if(data.errCode==0){
                alert("密码修改成功！");
                location.href = "/Login";
            }else{
                alert("密码修改失败，请重新验证");
                location.href = "/usercenter/findPwd?p="+$('#phone').val();
            }
        }  
   
    }); 
});
</script>
