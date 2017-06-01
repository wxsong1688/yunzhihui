/**
 * Created by Administrator on 2015/10/20.
 */
$(function(){

    // 判断是否邮箱验证
    var checkRes = $('#checkRes').val();
    if(checkRes=='checkSuccess'){
        //alert("邮箱验证通过！");
        parent.modalNew('bob',"邮箱验证",'邮箱验证通过！','','','','','确定','','/Usercenter','');
    }else if(checkRes=='checkFalse'){
        //alert("邮箱验证有误或超时，请稍后重试！");
        parent.modalNew('bob',"邮箱验证",'邮箱验证有误或超时！','','','','','确定','','/Usercenter','');
    }else{
        var $usersT = $(".nicEdit");
        var $usersNi = $(".usersNi");
        var $niName = $(".niName");
        var $emailEdit = $(".emailEdit");
        var $inputEmail = $(".inputEmail");
        var $bindEmail = $(".bindEmail");
        var $finishiBind = $(".finishiBind");
        function startNic(){
            if($niName.html()){
                $niName.show();
                $usersNi.hide();
                $usersT.hide();
            } else {
                $niName.hide();
                $usersNi.hide();
                $usersT.show();

            }
        }
        function startEmail(){
            if($bindEmail.html()){
                $bindEmail.show();
                $inputEmail.hide();
                $.ajax({
                    type: "POST",
                    url:'/Usercenter/isBindMail',
                    data:{email:$bindEmail.html()},  
                    error:function(){  
                        alert("error occured!!!");   
                    },
                    success:function(data){ 
                        if(data=='isbinded'){
                            $emailEdit.html("重新绑定");
                        }else{
                            $emailEdit.html("现在认证");
                        }
                    }  
               
                });
            } else {
                $bindEmail.hide();
                $inputEmail.hide();
                $emailEdit.html("现在绑定");

            }
        }
        startNic();
        startEmail();
        $finishiBind.click(function(){
            parent.modalNew('bob',"实名认证",'请您打开汇付天下页面完成充值操作','','','','','认证成功','重新认证','/Usercenter','');
        });
        $usersT.click(function(){
            parentFrame.find(".imsec2-ul-out").show();
            parentFrame.find("#rightFrame").css("z-index","1200");
            $(".imsec1-ul-out").show();
            getHeight(parentBodyH,"imsec2-ul-out");
            getHeight(childBodyH,"imsec1-ul-out");
            $(this).siblings(".editNc-div").show();

        });
    	$(".cancle-btn").click(function(){
    		parentFrame.find(".imsec2-ul-out").hide();
    		parentFrame.find("#rightFrame").css("z-index","900");
            $(".imsec1-ul-out").hide();
            $(this).parents(".upfile-div").hide();
    	});
    	$(".upload-btn").click(function(){
            var mail = $('#email').val();
            CheckMail(mail);
    		//parentFrame.find(".imsec2-ul-out").hide();
            //parentFrame.find("#rightFrame").css("z-index","900");
    		//$(".imsec1-ul-out").hide();
    		//$(this).parents(".upfile-div").hide();
    	});
        $emailEdit.click(function(){
            if($emailEdit.html()=="现在认证"){
                $.ajax({
                    type: "POST", 
                    url:'/Usercenter/sendsmail',
                    data:{uid:$('#uid').val(),isBindmail:1},  
                    error:function(){  
                        alert("error occured!!!");  
                    },
                    success:function(data){ 
                        if(data=='success'){
                            var mailEnd = $bindEmail.html().split("@");
                            var mailUrl = 'http://mail.'+mailEnd[1];
                            parent.modalNew('bob',"邮箱认证",'验证邮件已发送，请登录邮箱验证！<a href='+mailUrl+' target="_blank">现在验证</a>','','','','','稍后验证','','/Usercenter','');
                        }else{
                            parent.modalNew('bob',"邮箱认证",data,'','','','','确定','','/Usercenter','');
                        }
                    }  
               
                });  
            }else{
                parentFrame.find(".imsec2-ul-out").show();
                parentFrame.find("#rightFrame").css("z-index","1200");
                $(".imsec1-ul-out").show();
                getHeight(parentBodyH,"imsec2-ul-out");
                getHeight(childBodyH,"imsec1-ul-out");
                $(this).siblings(".editNc-div").show();
            }
        });
        var parentFrame = $(window.parent.document);
        var childBodyH = $(document).find("body").height();
        var parentBodyH = parentFrame.find("body").height();
        $(".upload-text").click(function(){
            parentFrame.find(".imsec2-ul-out").show();
    		parentFrame.find("#rightFrame").css("z-index","1200");
            $(".imsec1-ul-out").show();
            getHeight(parentBodyH,"imsec2-ul-out");
            getHeight(childBodyH,"imsec1-ul-out");
            $(this).siblings(".upfile-div").show();
        });
        $(".close-up").click(function(){
            parentFrame.find(".imsec2-ul-out").hide();
    		parentFrame.find("#rightFrame").css("z-index","900");
            $(".imsec1-ul-out").hide();
            $(this).parents(".upfile-div").hide();
        });

        $(".updateNiname").click(function(){
            $.ajax({
                type: "POST",
                url:'/Usercenter/editNiname',
                data:{uid:$('#uid').val(),username:$('#username').val()},  
                error:function(){  
                    alert("error occured!!!");   
                },
                success:function(data){ 
                    if(data=='success'){
                        parent.modalNew('bob',"编辑昵称",'修改昵称成功！','','','','','确定','','/Usercenter','');
                    }else{
                        parent.modalNew('bob',"编辑昵称",data,'','','','','确定','','/Usercenter','');
                    }
                }  
           
            });     
        });

        function CheckMail(mail) {
            var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (filter.test(mail)){
                $.ajax({
                    async: true,
                    type: "POST", 
                    url:'/Usercenter/editEmail',
                    data:{uid:$('#uid').val(),email:$('#email').val()},  
                    error:function(){  
                        alert("error occured!!!");  
                    },
                    success:function(data){ 
                        if(data=='success'){
                            parentFrame.find(".imsec2-ul-out").hide();
                            parentFrame.find("#rightFrame").css("z-index","900");
                            $(".imsec1-ul-out").hide();
                            $(this).parents(".upfile-div").hide();

                            var mailEnd = $('#email').val().split("@");
                            var mailUrl = 'http://mail.'+mailEnd[1];
                            parent.modalNew('bob',"邮箱绑定",'绑定邮箱成功，请登录邮箱验证！<a href='+mailUrl+' target="_blank">现在验证</a>','','','','','稍后验证','','/Usercenter','');
                        }else{
                            $(".msgemail").html("<font color='red'>"+data+"</font>");
                        }
                    }  
               
                });  
            } else {
                $(".msgemail").html("<font color='red'>您的电子邮件格式不正确！</font>");
                return false;
            }
        }
       

        function getHeight(h,out){
            parentFrame.find("."+out).height(h);
        }
    	function changeimg(){
    		$(".upload-success").show();
    	}
    }
});
