<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>我的投资</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/page.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/datepicker.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/investment.css"></link>

    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/datepicker.js"></script>
    <script type="text/javascript" src="/public/js/canlendar.js"></script>
    <script type="text/javascript" src="/public/js/maskshade.js"></script>
	<script type="text/javascript" src="/public/js/investment.js"></script>
    <script type="text/javascript" src="/public/js/detail-hk.js"></script>
	<script type="text/javascript" src="/public/js/userCenter.js"></script>
    
    <!--[if lte IE 6]>
    <script type="text/javascript">
        alert('您的浏览器版本太低了');
        window.opener=null;
        window.open('','_self','');
        window.close();
    </script>
    <![endif]-->
    
</head>
<body id="bob">
<div class="investment user-all" id="investment">
    <div class="user-section user-right-height" id="investment-section">
        <p class="investment-title usercenter-title">我的投资</p>
        <div class="investment-content">
            <div class="investment-content-section1">
                <div class="upload float-left">
                    <div class="uploadImg">
                        <img src="/public/images/uploadImg.png">
                    </div>
                </div>
                <div class="float-left investment-sec1-part1">
                    <p class="investment-text1">上次登录：<span>2015-09-11 16:30:52</span></p>
                    <h2>尊敬的<?php echo $userInfo['username']?>，您好！</h2>
                    <div class="investmentSafety">
                        <p class="investment-zw-text float-left">投资中项目<span class="inline-block"><?php echo $myProjectCount;?>笔</span></p>
                        <p class="investment-zw-text float-left">投资中债权<span class="inline-block"><?php echo $myCreditCount;?>笔</span></p>
                       
                    </div>
                </div>
                <div class="float-left investment-sec1-part2">
                    <p>投资中本金：<span class="investSec1-part20"><?php echo isset($myaccount['used_money']) ? number_format($myaccount['used_money'],2) : 0.00 ;?></span>元</p>
                    <p>待收本息&nbsp;&nbsp;&nbsp;：<span class="investSec1-part20"><?php echo number_format($expected,2);?></span>元</p>
                </div>
                <div class="float-left investment-sec1-part3">
                    <a href="/usercenter/recharge" class="invest-charge invest-btn-pub">充值</a>
                    <a href="/usercenter/withdrawals" class="invest-withdrawals invest-btn-pub">提现</a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="investment-content-section2">
                <div class="investment-con-section1">
                    <ul class="float-left">
                        <li class="float-left text-center" onclick="window.location='/Usercenter/investment?type=project'">
                            所投项目
                        </li>
                        <li class="float-left text-center ucaccrent">
                            所投债权
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="investment-con-sec10">
                    <ul class="float-left">
                        <li>
                            <div class="invest-section2-part4">
                                <ul class="float-left">
                                <?php if(!empty($myCredit)){
                                    foreach($myCredit as $k => $v){?>
                                    <li>
                                        <div class="float-left pk-part1-00"><img src="/public/images/creditor.png" width="63"> </div>
                                        <ul class="float-left invest-sec2-part40">
                                            <li class="float-left">
                                                <p class="investment-text2">投标日期</p>
                                                <p class="investment-text3"><?php echo date("Y-m-d",strtotime($v['deal_time']))?></p>
                                            </li>
                                            <li class="float-left">
                                                <p class="investment-text2">原始项目</p>
                                                <p class="investment-text3"><?php echo $v['pro_info']['pro_name']?></p>
                                            </li>
                                            <li class="float-left">
                                                <p class="investment-text2">债权价值</p>
                                                <p class="font-style5"><?php echo number_format($v['credit_amount'],2)?></p>
                                            </li>
                                            <li class="float-left">
                                                <p class="investment-text2">折让比例</p>
                                                <p class="font-style4"><?php echo round($v['discount']*100,2)?>%</p>
                                            </li>
                                            <li class="float-left">
                                                <p class="investment-text2">购买价格</p>
                                                <p class="font-style5"><?php echo number_format($v['real_amount'],2)?></p>
                                            </li>
                                            <li class="float-left">
                                                <p class="investment-text2">应收本息</p>
                                                <p class="font-style4"><?php echo number_format($v['readyGain'],2)?></p>
                                            </li>
                                            <li class="float-left">
                                                <p class="investment-text2">本息归还日期</p>
                                                <p class="investment-text3"><?php echo $v['finish_time']?></p>
                                            </li>
                                            <li class="float-left border-right-none">

                                                <?php if($v['status'] == 1){ ?>
                                                <a style="text-decoration:none;color:#ce0044;font-size:14px;">转让中</a>
                                                <?php }elseif($v['status'] == 10){ ?>
                                                    <a href="/Projectcredit/assignCreditorCon?mpid=<?php echo $v['item_id'];?>" target="_blank" class="font-style4">转让该项目</a>
                                                <?php }else{ ?>
                                                    <br><h3><font color="#ce0044">已结束</font></h3>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                        <div class="clear"></div>
                                    </li>
                                <?php }}?>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <div class="list-pagination">
                                <div class="y-pagination">
                                    <?php echo $this->pageclassc->show(1); ?>
                                    <span class="p-elem p-item-go">第<input class="p-ipt-go" id="p-ipt-go_c" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"  >页<a href="javascript:void(0);" class="p-btn-go" id="p-btn-go_c">GO</a></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>                
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">  
	change_height();
    $("#p-btn-go_c").click(function(){
        var page = $("#p-ipt-go_c").val();
        if(!isNaN(page)){
            alert("请输入正确页数");return;
        }
        window.location="/Usercenter/investment?type=credit&pg="+page;
    });
</script>
</html>