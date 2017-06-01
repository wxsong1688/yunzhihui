/**
 * Created by Administrator on 2015/10/20.
 */
$(function(){
/*    $('#old_pwd').keyup(function () {
        if ($('#old_pwd').val().trim().length < 6) {  
            $('#oldpwd_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }else{
            $('#oldpwd_message').html('<img src="/public/images/pwd-right.png" class="pwd-right">');
        }
    });

    $('#new_pwd').keyup(function () {
        if ($('#new_pwd').val().trim().length < 6) {
            $('#newpwd_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }else{
            $('#newpwd_message').html('<img src="/public/images/pwd-right.png" class="pwd-right">');
        }
    });

    $('#renew_pwd').keyup(function () {
        if ($('#renew_pwd').val().trim() != $('#new_pwd').val().trim() || $('#new_pwd').val().trim().length < 6) {
            $('#renew_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }else{
            $('#renew_message').html('<img src="/public/images/pwd-right.png" class="pwd-right">');
        }  

    });*/

    $('#old_pwd').blur(function () {
        if ($(this).val().trim().length < 6) {  
            $('#oldpwd_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }else{
            $('#oldpwd_message').html('<img src="/public/images/pwd-right.png" class="pwd-right">');
        }
    }) 
    $('#new_pwd').blur(function () {  
        if ($(this).val().trim().length < 6) {
            $('#newpwd_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }else{
            $('#newpwd_message').html('<img src="/public/images/pwd-right.png" class="pwd-right">');
        }  
    }) 
    $('#renew_pwd').blur(function () {  
        if ($(this).val().trim() != $('#new_pwd').val().trim() || $(this).val().trim().length==0) {
            $('#renew_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }else{
            $('#renew_message').html('<img src="/public/images/pwd-right.png" class="pwd-right">');
        }  
    })

    $('.changePwd-button').click(function(){
        if ( $('#old_pwd').val().trim().length < 6 ) {
            $('#oldpwd_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }
        if ( $('#new_pwd').val().trim().length < 6 ) {
            $('#newpwd_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }
        if ( $('#new_pwd').val().trim() != $('#renew_pwd').val().trim() || $('#new_pwd').val().trim().length==0 ){
            $('#renew_message').html('<img src="/public/images/pwd-error.png" class="pwd-right">');return;
        }
        var params = {uid:$('#uid').val(),old_pwd:$('#old_pwd').val(),new_pwd:$('#new_pwd').val(),renew_pwd:$('#renew_pwd').val()};
        
        $.ajax({
            url:'/usercenter/dochangePwd',
            type:"post",
            data:params,
            dataType:"json",
            error:function(){  
                alert("error occured!!!");  
            },
            success:function(data){
                if(data.code==1){
                    parent.modalNew('bob',"修改密码",data.msg,'','','','','确定','','/Usercenter','');
                }else{
                    parent.modalNew('bob',"修改密码",data.msg,'','','','','确定','','','');
                }
            }  
       
        }); 
    });  
});