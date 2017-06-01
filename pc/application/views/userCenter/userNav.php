<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>用户中心</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userNav.css"></link>
    <script type="text/javascript" src="/public/js/jquery.min.js"></script>
    <script>
        $(function(){
		
            var $secli = $(".usernav-nav-section>ul>li");
            var ppt = $(".myaccount-nav");
            var left = $(window.parent.document).find("#leftFrame");
            var right = $(window.parent.document).find("#rightFrame");     
           
          
            $secli.click(function(){
                var $img = $(this).find("img").attr("src").split("-");
                var $par = $(".acurrent");
                var src = $par.find("img").attr("src").split("-");
                $(this).find("a").addClass("acurrent");
                $(this).find("img").attr("src",$img[0]+"-"+"1.png");
                $(this).find("p").addClass("nav-color");
                $(this).siblings("li").find("a").removeClass("acurrent");
                $par.find("img").attr("src",src[0]+"-"+"0.png");
                $(this).siblings("li").find("p").removeClass("nav-color");
                $(this).parents(".usernav-nav-section").siblings(".usernav-nav-section").find("a").removeClass("acurrent");
                $(this).parents(".usernav-nav-section").siblings(".usernav-nav-section").find("p").removeClass("nav-color");
				$.ajax({
				 type: "GET",
				 url: "/Usercenter/setcookie_nav",
				 data: {navId:$(this).find("a").attr("id")},
				 dataType: "json",
				 success: function(data){
							 alert(data);return;
						  }
				});
			
            });

            $(".navTitle").click(function(){
                var $img = $(this).find("img").attr("src").split("-");
                $(this).siblings("ul").slideToggle();
                if($img[$img.length-1]=="00.png"){
                    $(this).find("img").attr("src",$img[0]+"-01.png");
                } else {
                    $(this).find("img").attr("src",$img[0]+"-00.png")
                }

            });
			//记录刷新前用户所在页面
			var navId = $("input[name='navId']").val();
            var currentId = $("#"+navId);
			var href = currentId.attr("href"); 
			currentId.trigger("click");
            right.attr("src",href);
        })
    </script>
</head>
<body id="user-nav">
<input type="hidden" value="<?php echo $navId;?>" name="navId" />
<div class="userNav" id="userNav">
    <div class="userNav-all">
        <p class="userNav-title">我的账户</p>
        <div class="userNav-nav">
            <div class="usernav-nav-section">
				<p class="navTitle">个人资料<img src="/public/images/botjt-00.png"></p>
                <ul class="float-left">
                    <li>
                        
                        <a href="/usercenter/userInformation" target="rightFrame" class="block acurrent myaccount-nav" id="basicInfor">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav0-1.png">
                            </div>
                            <p class="float-left nav-font1 nav-color">基本信息</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                    <li>
                        
                        <a href="/usercenter/changePwd" target="rightFrame" class="block myaccount-nav" id="changePwd">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav1-0.png">
                            </div>
                            <p class="float-left nav-font1">修改密码</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                    <li>

                        <a href="/usercenter/systemNews" target="rightFrame" class="block myaccount-nav" id="sysmsg">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav3-0.png">
                            </div>
                            <p class="float-left nav-font1">系统消息<span class="nav-color1">(<?php echo $msgcount;?>)</span></p>
                            <div class="clear"></div>
                        </a>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="usernav-nav-section">
                <p class="navTitle">资金管理<img src="/public/images/botjt-00.png"></p>
                <ul class="float-left">
                    <li>
                        
                        <a href="/usercenter/accountBrowse" target="rightFrame" class="block myaccount-nav" id="accBrowse">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav4-0.png">
                            </div>
                            <p class="float-left nav-font1">账户概览</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                    <li>
                       
                        <a href="/usercenter/recharge" target="rightFrame" class="block myaccount-nav" id="irecharge">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav5-0.png">
                            </div>
                            <p class="float-left nav-font1">我要充值</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                    <li>
                        
                        <a href="/usercenter/withdrawals" target="rightFrame" class="block myaccount-nav" id="withdrawals">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav6-0.png">
                            </div>
                            <p class="float-left nav-font1">我要提现</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                    <li>
                        
                        <a href="/usercenter/fundRunning" target="rightFrame" class="block myaccount-nav" id="fundRunning">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav7-0.png">
                            </div>
                            <p class="float-left nav-font1">资金流水</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="usernav-nav-section border-last">
                <p class="navTitle">投资管理<img src="/public/images/botjt-00.png"></p>
                <ul class="float-left">
                    <li>
                        
                        <a href="/usercenter/investment" target="rightFrame" class="block myaccount-nav" id="investment">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav8-0.png">
                            </div>
                            <p class="float-left nav-font1">我的投资</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                    <!-- <li>
                        
                        <a href="/usercenter/userAutobid" target="rightFrame" class="block myaccount-nav" id="userAutobid">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav9-0.png">
                            </div>
                            <p class="float-left nav-font1">自动投标</p>
                            <div class="clear"></div>
                        </a>
                    </li> -->
                    <li>
                       
                        <a href="/usercenter/userAssignCreditor" target="rightFrame" class="block myaccount-nav" id="assignCred">
                            <div class="userNav-li-img float-left">
                                <img src="/public/images/nav10-0.png">
                            </div>
                            <p class="float-left nav-font1">债权转让</p>
                            <div class="clear"></div>
                        </a>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>