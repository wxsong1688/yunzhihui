<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>我要提现</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/bank.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/withdrawals.css"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/withdrawa.js"></script>
	<script type="text/javascript" src="/public/js/userCenter.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            $("#withdrawalsMoney").click(function(){
                $("#withdrawals_notice").html("");
            });

            $("#withdrawalsMoney").keyup(function(){
                $("#withdrawals_notice").html("");
                var rate= 0.00;
                var outMoney = $("#withdrawalsMoney").val();
                var outMoneytype = $(".withdraw-current").attr("id");
                if( outMoneytype=="yiban" ){
                    rate = 2;
                }else{
                    rate = outMoney*0.0005+2;
                }
                $("#rate").html('');
                $("#rate").html(rate.toFixed(2));
                $("#withdrawalsAmount").text(outMoney);

            });

            $(".confirm-tx").click(function(){
                var input_money = $("#withdrawalsMoney").val();
                if(input_money == "请输入金额"){
                    $("#withdrawals_notice").html("请输入正确的充值金额！");
                    return false;
                }
                $("form").submit();
                /* 弹出层 */
                parent.modalNew('bob',"提现",'请在新打开的汇付页面进行提现','','','','','已完成','重新提现','/Usercenter','');
                // $(".register-success span").parents().hide();
                parent.document.getElementById("sure_span").style.display="none";
                parent.document.getElementById("closeWin").style.display="none";
                /* 弹出层 */
            });

            $(".withdrawalsBank").click(function(){
                var url = "/Hfcenter/userBindCard";
                window.open(url);
            });


        })
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
<div class="withdrawals user-all" id="withdrawals">
    <div class="user-section user-right-height" id="withdrawals-section">
        <p class="withdrawals-title usercenter-title">我要提现</p>
        <div class="withdrawals-content">
<?php if($id_succ == 0 || $card_status == 0){ ?>
            <div class="withdrawals-con-title-rz">
                <div style="float:left;margin:80px 162px;width:500px">亲爱的用户，请点击“基本信息”进行实名认证并绑定银行卡</div>
            </div>
<?php }else{ ?>
            <form action="/Hfcenter/getCash" method="POST" name="getCashform" target="_blank">
                <div class="withdrawals-con-title">申请提现</div>
                <div class="withdrawals-middle">
                    <p class="sum-ye">现金账户余额：<span class="km1"><?php echo $userAccount['money'];?></span>元</p>
                    <div class="fm-item">
                        <div class="fm-title float-left">提现金额：</div>
                        <input type="text" name="withdrawalsMoney" id="withdrawalsMoney" class="fm-text differ-color0" size="14" value="请输入金额" onfocus="if(this.value=='请输入金额'){this.value=''}" onblur="if(this.value==''){this.value='请输入金额'}">
                        元
                        <label id="withdrawals_notice" style="color:red;"></label>
                        <div class="clear"></div>
                    </div>
                    <div class="fm-item">
                        <div class="fm-title float-left">提现方式：</div>
                        <ul class="withdraw-ways float-left">
                            <li id="GENERAL" class="float-left withdraw-current">普通<span></span></li>
                            <!--<li id="FAST" class="float-left">快速<span class="display-none"></span></li>
                            <li id="IMMEDIATE" class="float-left">及时<span class="display-none"></span></li>-->
                        </ul>
                        <input type="hidden" name="cashtype" id="cashtype" value="FAST" />
                        <div class="clear"></div>
                    </div>
                    <div class="fm-item">
                        <div class="fm-title float-left">提现手续费：</div>
                        <span id="rate" class="float-left charge-text1 withdraw-top0">0.00</span>元
                        <label class="inline-block position-re torange">
                            <span class="charge-new">充值手续费由云智慧代为支付给第三方支付平台。</span>
                        </label>
                    </div>
                    <div class="fm-item">
                        <div class="fm-title float-left tx-time">到账时间：</div>
                        <p class="float-left withdrawalsTime" id="withdrawalsTime">1-2个工作日到账</p>
                        <div class="clear"></div>
                    </div>
                    <div class="fm-item">
                        <div class="fm-title select-bank">选择银行卡：</div>
                        <div class="bank-ka">
                            <ul class="float-left">
<?php foreach ($cardInfo as $k => $v){ ?>
                                <li>
                                    <span class="select-curr <?php if($k>0) echo 'display-none';?>"></span>
                                    <span class="public-pic <?php echo isset($bankInfo[$v['deposit_bank']]) ? $bankInfo[$v['deposit_bank']]['css'] : '' ;?>" id=""></span>
                                    <span class="bank-id"><?php echo $v['card_num'];?></span>
                                </li>
<?php } ?>
<?php if($card_type==1){ ?>
                                <li><p class="withdrawalsBank">添加银行卡</p></li>
<?php } ?>
                            </ul>
                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <!-- <div class="fm-item lef-dd">
                        <span class="real-accout">实际到账：</span>
                        <span class="differ-color1" id="withdrawalsAmount">0.00</span>
                        元,预计
                        <span class="differ-color1"><?php //echo date("Y-m-d",strtotime("+3 days"))." 24:00"?></span>
                        前到账
                    </div> -->
                    <div class="confirm-btn-tx"><a href="javascript:void(0);" class="inline-block confirm-tx">确认提现</a></div>
                    <div class="warning">
                        <span>温馨提示：</span><br>
                        <span>1、用户实名认证开通第三方托管账户、绑定银行卡后，才可申请提现。</span><br>
                        <span>2、提现到账时间为1-2个工作日，双休日和法定节假日除外。</span><br>
                        <span>3、用户绑定银行卡作为快捷卡后，只可以提现到该快捷卡，快捷卡一经绑定，其余银行卡自动解绑。</span><br>
                    </div>
                </div>
            </form>
<?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	change_height();
</script>
</body>
</html>