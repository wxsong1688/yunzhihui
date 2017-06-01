/**
 * Created by Administrator on 2015/10/21.
 */
$(document).ready(function(){
    $("input").focus(function(){
        $(this).css({"border":"1px solid #7AB7F3","color":"#5c5c5c"});
    });
    $("input").blur(function(){
        $(this).css("border","1px solid #ddd");
    });

	$("#yzm-get").click(function(){
		
	});
    $("#phone").blur(function () {
        if ($(this).val().trim().length != 11) {
            $('.msgphone').text("手机号不能为空").css({"background-color":"red"});return;
        }else{
            $('.msgphone').text("ok").css({"background-color":"green"});return;
        }
    })  
    $('#phonecode').blur(function () {  
        if ($(this).val().length==0) {
            $('.msgphonecode').text("短信验证码不能为空！").css({"background-color":"red"});return;
        }else{
            $('.msgphonecode').text("ok").css({"background-color":"green"});return;
        }
    })  

    $('.findPwdNext').click(function(){
        if ($("#phone").val().trim().length != 11) {
            $('.msgphone').text("手机号不能为空").css({"background-color":"red"});return;
        }
        if ($('#phonecode').val().length==0) {
            $('.msgcheckcode').text("短信验证码不能为空！").css({"background-color":"red"});return;
        }
        $.ajax({
            url:'/usercenter/findPwdSubmit',
            data:{phone:$('#phone').val(),phonecode:$('#phonecode').val(),sendcode:$('#sendcode').val()},  
            error:function(){  
                alert("error occured!!!");  
            },
            success:function(data){ 
                if(data=='success'){
                    alert("验证通过请重置密码！");
                    location.href = "/usercenter/pwdRestart?p="+$('#phone').val();
                }else{
                    alert(data);
                    location.href = "/usercenter/findPwd?p="+$('#phone').val();
                }
            }  
       
        }); 
    });

});



function get_mobile_code(btn){
    $.post('/forgotpwd/sendmsg', {phone:jQuery.trim($('#phone').val())}, function(msg) {
        var obj = eval('('+msg+')');//转换后的JSON对象
        if(obj.code==0){  
			var iTime = 60;
			var Account;
            RemainTime(iTime);
            $("#sendcode").val(obj.sendCode);
        }else{
            alert(jQuery.trim(unescape(obj.msg)));
        }
    });
};

function RemainTime(iTime){
	iTime-=1;
	//alert("123");
    //document.getElementById('yzm-get').disabled = true;
    $("#yzm-get").attr("disabled",true);
    $("#yzm-get").addClass("disabled-btn");
    var iSecond,sSecond="",sTime="";
    if (iTime >= 0){
        iSecond = parseInt(iTime%60);
        iMinute = parseInt(iTime/60);
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
            //document.getElementById('yzm-get').disabled = false;
            $("#yzm-get").attr("disabled",false);
            $("#yzm-get").removeClass("disabled-btn");
			$("#yzm-get").html(sTime);
			iTime=60;
        }else{
            
            
			$("#yzm-get").html(iTime+"秒后重新获取");
			Account = setTimeout("RemainTime('" + iTime + "')",1000);
			//alert(iTime);
        }
	
    }else{
        sTime='没有倒计时';
		$("#yzm-get").html(sTime);
    }
    //document.getElementById('yzm-get').value = sTime;
    //$("#yzm-get").html(sTime+"秒后重新获取");
}