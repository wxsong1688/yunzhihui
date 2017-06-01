<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>用户 自动投标</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userAutobid.css"></link>
    <script type="text/javascript" src="/public/js/jquery.min.js"></script>

    <script type="text/javascript">
        $(function(){
            var $autobidmg = $("#autobid-img");
            $autobidmg.click(function(){
                var src = $(this).find("img").attr("src").split("-");
                var last = src[src.length-1];
                if(last=="on.png"){
                    $(this).find("img").attr("src",src[0]+"-off.png");
                    $(this).siblings(".autobid-state").html("已关闭&nbsp;&nbsp;:&nbsp;&nbsp;");
                    $("#on-off").val("0");
                } else {
                    $(this).find("img").attr("src",src[0]+"-on.png");
                    $(this).siblings(".autobid-state").html("已开启&nbsp;&nbsp;:&nbsp;&nbsp;");
                    $("#on-off").val("1");
                }
            });
            $(".autobid-money ul>li").click(function(){
                $(this).addClass("am-current");
                $(this).siblings("li").removeClass("am-current");
                if($(".all_amount").hasClass("am-current")){
                    $(".autobid-money ul>li").addClass("am-current");
                }else{
                    $(".autobid-money ul>li").removeClass("am-current");
                }
            });
            $(".autobid-date ul>li").click(function(){
                $(this).toggleClass("am-current");
                if($(".all_cycle").hasClass("am-current")){
                    $(".autobid-date ul>li").addClass("am-current");
                }else{
                    $(".autobid-date ul>li").removeClass("am-current");
                }
            });
        })
        function autotender_submit(){
            var url = '';
            if(1==1){
                url = "/Hfcenter/autoInvestmentPlan";
            }else{
                url = "/Hfcenter/autoInvestmentPlanClose";
            }
            $.ajax({
                type: "POST",
                url: url,
                data: {tender:'1',amount:'100000',circle:'6',type:'1'},
                dataType: "json",
                success: function(data){
                        if(data.code=='1'){
                            alert(data.msg);return;
                        }else{
                            alert(data.msg);return;
                        }
                    }
            });
            
        }
    </script>
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
<div class="userAutobid user-all" id="userAutobid">
    <div class="user-section user-right-height">
        <p class="usercenter-title">自动投标</p>
        <div class="userAutobid-content">
            <div class="userAutoBid-con-section1">
<?php if(empty($autobid)){ ?>
                <div class="userAutobid-sec1-title">
                亲爱的用户，请先进行身份认证并充值，才能设置自动投标
                </div>
<?php }else{ ?>
                <div class="userAutobid-sec1-title">没空理财？那就开启自动投标吧，躺着就能赚钱！</div>
                <div class="userAutobid-sect-part1">
                    <p class="float-left autobid-text1">自动投标&nbsp;:&nbsp;</p>
                    <p class="float-left autobid-state autobid-text1">
                        <?php echo $autobid['auto_tender']==1 ? "已开启" : "已关闭" ;?>
                        &nbsp;&nbsp;:&nbsp;&nbsp;
                    </p>
                    <div class="autobid-img float-left" id="autobid-img">
                        <img src="<?php echo $autobid['auto_tender']==1 ? '/public/images/state-on.png' : '/public/images/state-off.png'?>" value="on"/>
                    </div>
                    <input type="hidden" value="1" id="on-off" name="on-off">
                    <div class="clear"></div>
                </div>

                <div class="userAutobid-sect-part2">设置投标条件</div>
                <div class="userAutobid-sect-part3">
                    <div class="bidsect-part3-00">
                        <div class="autobid-money">
                            <p class="autobid-money-t autobid-text3 text-right float-left">自动投标金额：</p>
                            <ul class="float-left">
                                <li value="0"      class="float-left autobid-text3 am-default all_amount <?php if($autobid['auto_amount'] == 0){echo 'am-current';}?>">全部余额</li>
                                <li value="1000"   class="float-left autobid-text3 am-default <?php if($autobid['auto_amount'] == 1000 || $autobid['auto_amount'] == 0){echo 'am-current';}?>">一千元以内</li>
                                <li value="10000"  class="float-left autobid-text3 am-default <?php if($autobid['auto_amount'] == 10000 || $autobid['auto_amount'] == 0){echo 'am-current';}?>">一万元以内</li>
                                <li value="100000" class="float-left autobid-text3 am-default <?php if($autobid['auto_amount'] == 100000 || $autobid['auto_amount'] == 0){echo 'am-current';}?>">十万元以内</li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <div class="autobid-date">
                            <p class="autobid-money-t autobid-text3 text-right float-left">投资项目周期：</p>
                            <ul class="float-left">
                                <li value="0" class="float-left autobid-text3 am-default all_cycle<?php if(strpos($autobid['auto_circle'],'0') !== false){echo 'am-current';}?>">全部</li>
                                <li value="1" class="float-left autobid-text3 am-default <?php if(strpos($autobid['auto_circle'],'1') !== false || strpos($autobid['auto_circle'],'0') !== false){echo 'am-current';}?>">1个月</li>
                                <li value="3" class="float-left autobid-text3 am-default <?php if(strpos($autobid['auto_circle'],'3') !== false || strpos($autobid['auto_circle'],'0') !== false){echo 'am-current';}?>">3个月</li>
                                <li value="6" class="float-left autobid-text3 am-default <?php if(strpos($autobid['auto_circle'],'6') !== false || strpos($autobid['auto_circle'],'0') !== false){echo 'am-current';}?>">6个月</li>
                                <li value="9" class="float-left autobid-text3 am-default <?php if(strpos($autobid['auto_circle'],'9') !== false || strpos($autobid['auto_circle'],'0') !== false){echo 'am-current';}?>">9个月</li>
                                <li value="12" class="float-left autobid-text3 am-default <?php if(strpos($autobid['auto_circle'],'12') !== false || strpos($autobid['auto_circle'],'0') !== false){echo 'am-current';}?>">12个月</li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="bidsect-part3-01">
                        <div class="autobid-kind">
                            <p class="autobid-text3 text-right float-left">投资项目类型：</p>
                            <ul class="float-left">
                                <li class="float-left autobid-text3 am-radio">
                                    <p class="float-left"><input type="checkbox" name="bid-radio" <?php if($autobid['auto_type'] == 2){echo "checked";}?>></p>
                                    <span>普惠金融</span>
                                </li>
                                <li class="float-left autobid-text3 am-radio">
                                    <p class="float-left"><input type="checkbox" name="bid-radio" <?php if($autobid['auto_type'] == 3){echo "checked";}?>></p>
                                    <span>精英理财</span>
                                </li>
                                <li class="float-left autobid-text3 am-radio">
                                    <p class="float-left"><input type="checkbox" name="bid-radio" <?php if($autobid['auto_type'] == 4){echo "checked";}?>></p>
                                    <span>高端定制</span>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <div class="agree-xy">
                            <div class="float-left">
                            <input type="checkbox" checked name="agree-check" class="agree-check"></div>
                            <p class="float-left autobid-text4">我已阅读并同意《云智慧自动投标协议》</p>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <button type="button" class="bid-button" onclick="autotender_submit()">确认提交</button>
                </div>
<?php } ?>
            </div>
            <div class="userAutoBid-con-section2">
            </div>
        </div>
    </div>
</div>
</body>
</html>