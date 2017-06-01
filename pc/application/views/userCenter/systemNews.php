<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>系统消息</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/page.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/systemNews.css"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/systemNews.js"></script>
	
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
<div class="systemNews user-all" id="systemNews">
    <div class="user-section user-right-height" id="systemNews-section">
        <p class="systemNews-title usercenter-title">系统消息</p>
        <div class="systemNews-content">
            <div class="user-submit-order-title">
                <span class="submit-order-title">|</span><span>消息中心</span>
            </div>
            <div class="user-center-right left">
                <div class="center-right-section1">
                    <ul class="cr-all float-left">
                        <li class="float-left cr-title">
                            <div class="cr-part01 float-left"><img src="/public/images/new01.png"></div>
                            <p class="float-left cr-part02">收信箱</p>
                            <!-- <div class="cr-part03"><span><?php echo $msgcount;?></span></div> -->
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="center-right-section2">

                    <div class="d-content">
                        <ul class="float-left">
                        <?php foreach($msginfo as $k => $v): ?>
                            <li class="d-content-li">
                                <div class="d-list d-list1">
                                    <ul class="float-left">
                                        <li class="float-left news-center-left1">
                                        <?php if($v['status']==0): ?>
                                            <span id="stat_<?php echo $v['id'];?>" class="tm3 systemNews-tm">未读</span>
                                        <?php else: ?>
                                            <span id="stat_<?php echo $v['id'];?>" class="systemNews-tm">已读</span>
                                        <?php endif; ?></li>
                                        <li class="float-left news-center-left2">|</li>
                                        <li class="float-left news-center-left2" style="color:#F33;"></li>
                                        <li class="float-left">【<?php echo $v['title'];?>】</li>
                                        <li class="float-right news-center-left2"><span class="tm4" ifread="<?php echo $v['status'];?>"><img id="<?php echo $v['id'];?>" class="systemNews-img" src="/public/images/c-jt.gif"></span></li>
                                        <li class="float-right"><?php echo $v['send_time'];?> 来自悦生活</li>
                                    </ul>
                                    <div style="clear:both"></div>
                                </div>
                                <div class="d-detail display-none">
                                    <?php echo $v['content'];?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        </ul>
						<div class="clear"></div>
					</div>
                    <div class="clear"></div>
                    <div class="list-pagination">
                        <div class="y-pagination">
                            <?php echo $this->pageclass->show(1); ?>
                            <span class="p-elem p-item-go">第<input class="p-ipt-go" id="p-ipt-go" onkeyup="this.value=this.value.replace(/\D/g,'')" value="<?php echo isset($searchData['pg'])?$searchData['pg']:'';?>" onafterpaste="this.value=this.value.replace(/\D/g,'')"  >页<a href="javascript:void(0);" class="p-btn-go" id="p-btn-go">GO</a></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
    </div>
</div>
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">
	change_height();
    $("#p-btn-go").click(function(){
        var page = parseInt($("#p-ipt-go").val());
        if(isNaN(page) || page<=0){
            alert("请输入正确页数");return;
        }
        window.location="/Usercenter/systemNews?pg="+page;
    });
</script>
</body>

</html>