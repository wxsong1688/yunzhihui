<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>悦生活</title>

    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/header.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/banner.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/index-middle.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/footer.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/jquery.marquee.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/public-zz.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/page.css"></link>    
    <link rel="stylesheet" type="text/css" href="/public/css/login.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/detail-hk.css"></link>
    
    <script type="text/javascript" src="/public/js/maskshade.js"></script>
    <script type="text/javascript" src="/public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/jQueryRotate.2.2.js"></script>
    <script type="text/javascript" src="/public/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/public/js/i.js"></script>
    <script type="text/javascript" src="/public/js/jquery.marquee.js"></script>
    <script type="text/javascript" src="/public/js/header.js"></script>
    <script type="text/javascript" src="/public/js/index.js"></script>
    <script type="text/javascript" src="/public/js/banner.js"></script>
	<script type="text/javascript" src="/public/js/widthSet.js"></script>
	<script type="text/javascript" src="/public/js/userCenter.js"></script>
	

</head>
<body id="bob">
    <!--header start-->
    <div class="header">
        <!--<div class="top-section1">
            <div class="close-icon">
                <img src="/public/images/close.png"></img>
            </div>
        </div>-->
        <div class="top-section2">
            <div class="top-section2-part1">
                <div class="float-left">
                    <div class="float-left">
                        <img class="float-left tsp-top" src="/public/images/home.png"></img>
                    </div>
                    <span class="inline-block hotline-text float-left hotline-blue">云智慧官网</span>
                    <div class="hotline float-left">
                        <img class="float-left tsp-top" src="/public/images/phone.png"></img>
                    </div>
                    <span class="inline-block hotline-text float-left hotline-blue"><?php echo $this->config->config['send_msg_kftel']?></span>  
                </div>
               
                <div class="float-left part1-left">
                    <div class="hotline float-left tsp-top">
                        <a href="#"><img class="float-left part1-top" src="/public/images/tblog.png"></img></a>
                        <a href="#"><img class="float-left part1-top part1-left3" src="/public/images/weixin.png"></img></a>
                    </div>
                </div>
                <div class="float-left part1-left2">
                    <!--<a href="javascript:void(0);">
                        <ul id="marquee" class="marquee">
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="hotline float-left">
                                        <img class="float-left part1-top2" src="/public/images/horn.png"></img>
                                    </div>
                                    <span class="text-ke inline-block hotline-text"></span>
                                </a>
                            </li>
                        </ul>
                    </a>-->
                </div>

                <?php if(!empty($user)): ?>
                    <span class="float-right loginstatus">尊敬的 <a href="/usercenter" class="userCode"><?php echo $user;?></a>，您好！&nbsp;&nbsp;<a href="/login/exitLogin" class="userquit hotline-blue">退出</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="hotline-text text-ke" href="javascript:void(0);" ></a><span class="hotline-blue"></span></span>
                <?php else:?>
                    <a href="/Register" class="float-right index-register">注册</a>
                    <a href="/Login" class="float-right index-login">登录</a>
                    <a class="float-right index-register">您好，欢迎光临云智慧理财俱乐部！</a>
                <?php endif;?>
                <div class="clear"></div>                
            </div>
        </div>
        <div class="top-section3">
            <div class="top-section3-part">
                <div class="float-left">
                    <a href="/" class="float-left">
                        <img src="/public/images/logo.png" alt="LOGO">
                    </a>
                    <span class="inline-block float-left logo-line"></span>
                    <div class="float-left">
                        <img src="/public/images/logo-right.png">
                    </div>
                </div>
                <div class="float-right position-re section3-left1">
                    <ul class="float-left ">
                        <li class="float-left" id="home"><a href="/" class="<?php echo $headerNavClass['HNav_home'];?>">首页</a></li>
                        <li class="float-left" id="finan"><a href="/FinanciaTransactions" class="<?php echo $headerNavClass['HNav_finan'];?>">我要理财</a></li>
                        <li class="float-left" id="myAccount"><a href="/Usercenter" class="<?php echo $headerNavClass['HNav_myAccount'];?>">我的账户</a></li>
                        <li class="float-left" id="safeBz2"><a href="/SafetysAssurance" class="<?php echo $headerNavClass['HNav_safeBz'];?>">安全保障</a></li>
                        <li class="float-left" id="ask"><a href="/Helpcenter" class="<?php echo $headerNavClass['HNav_ask'];?>">智慧问答</a></li>
                    </ul>
                    <span class="nav-bot-line nav-line-ab"></span>
                    <?php if(empty($user)): ?>
                        <a class="float-left register-bg" href="/Register">免费注册</a>
                    <?php endif;?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!--header end -->
