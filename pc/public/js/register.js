/**
 * Created by Administrator on 2015/10/29.
 */
$(function(){
    var $temp1 = $(".register-ts2>span");
    var pwd = $("input[name='pwd']");
    pwd.keyup(function(){
        checkPwd();
    });
    function checkPwd(){
        if(pwd.val().length < 6 && pwd.val().length > 0){
            /*pwd.siblings(".register-ts3").show();
            pwd.siblings(".register-ts3").html("密码不能少于6个字符");*/
            for(var i=0;i<$temp1.length;i++){
                $temp1.eq(0).addClass("high");
                $temp1.eq(1).removeClass("high");
                $temp1.eq(2).removeClass("high");
            }
        }
        if(pwd.val().length >= 6 && pwd.val().length < 10){
            pwd.siblings(".register-ts3").hide();
            $temp1.eq(0).addClass("high");
            $temp1.eq(1).addClass("high");
            $temp1.eq(2).removeClass("high");
        }
        if(pwd.val().length >= 10 && pwd.val().length < 16){
            pwd.siblings(".register-ts3").hide();
            for(var m=0;m<$temp1.length;m++){
                $temp1.eq(m).addClass("high");
            }
        }
        // if(pwd.val().length >= 16){
        //     pwd.siblings(".register-ts3").show();
        //     pwd.siblings(".register-ts3").html("密码不能多于16个字符");
        //     for(var n=0;n<$temp1.length;n++){
        //         $temp1.eq(n).addClass("high");
        //     }
        // }
    }

});