<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>我要充值</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/bank.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/recharge.css"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/recharge.js"></script>
    <script type="text/javascript" src="/public/js/maskshade.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            var tremp = $("input[name='GateBusiId']");
            $('.zf-pub').each(function(i){
                $(this).click(function(){
                    if(i==0){
                        tremp.val("B2C");
                    } else if(i==1){
                        tremp.val("QP");
                    }
                    $(this).addClass('zf-pub1');
                    $(this).siblings().removeClass('zf-pub1');
                    $('.zf-con0'+i).show();
                    $('.zf-con0'+i).siblings(".zf-con-pub").hide();
                });
            })
            function fmoney(s, n)  
            {  
               n = n > 0 && n <= 20 ? n : 2;  
               s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";  
               var l = s.split(".")[0].split("").reverse(),
               r = s.split(".")[1];  
               t = "";  
               for(i = 0; i < l.length; i ++ )  
               {  
                  t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");  
               }  
               return t.split("").reverse().join("") + "." + r;  
            } 

            $("#rechargeMoney").click(function(){
                $("#recharge_notice").html("");
            });
            
            $("#rechargeMoney").keyup(function(){
                $("#recharge_notice").html("");
                var charge = $("#rechargeMoney").val();
                var rate = charge*0.0025;
                $("#rate").html('');
                $("#rate").html(fmoney(rate,2));
                var realcharge;
                if(charge == ''){
                    realcharge = "0.00";
                }else{
                    realcharge = fmoney(charge,2);
                }
                $("#realcharge").html('');
                $("#realcharge").html(realcharge);
            });

            $(".submit1").click(function(){
                var input_money = $("#rechargeMoney").val();
                if(input_money == "请输入金额"){
                    $("#recharge_notice").html("请输入正确的充值金额！");
                    return false;
                }
                $("form").submit();
                /* 弹出层 */
                parent.modalNew('bob',"充值","请您打开汇付天下页面完成充值操作","","","","","已完成","重新充值",'/Usercenter','');
                parent.document.getElementById("closeWin").style.display="none";
                /* 弹出层 */
            });

        })
    </script>
</head>
<body>
<div class="recharge user-all" id="recharge">
    <div class="user-section user-right-height" id="recharge-section">
        <p class="recharge-title usercenter-title">我要充值</p>
        <div class="charge-content">
<?php if($id_succ == 0){ ?>
            <div class="charge-title-rz">
            <div style="float:left;margin:80px 162px;width:500px">亲爱的用户，请点击“基本信息”进行实名认证</div>
            </div>
<?php }else{ ?>
            <form action="/Hfcenter/Recharge" method="POST" name="rechargeform" target="_blank">
                <div class="charge-title">我的账户</div>
                <div class="charge-middle">
                    <p class="sum-ye">账户余额：<span class="km1"><?php echo $userAccount['money'];?></span>元</p>
                </div>
                <div class="fm-item">
                    <div class="fm-title float-left">充值金额：</div>
                    <input type="text" class="fm-text" style="color:#999999;" size="10" value="请输入金额" onfocus="if(this.value=='请输入金额'){this.value=''}" onblur="if(this.value==''){this.value='请输入金额'}" name="rechargeMoney" id="rechargeMoney" onkeyup="this.value=this.value.replace(/\D/g,'')">
                    元
                    <label id="recharge_notice" style="color:red;"></label>
                </div>
                <div class="fm-item">
                    <div class="fm-title float-left">充值手续费：</div>
                    <span id="rate" class="float-left charge-text1">0.00</span>元
                    <label class="inline-block position-re torange"><span class="marLeft1 charge-new">充值手续费由云智慧代为支付给第三方支付平台。</span></label>
                </div>
                <div class="fm-item">
                    <div class="fm-title float-left">实际到账金额：</div>
                    <span id="realcharge" class="float-left charge-text1">0.00</span>元
                </div>
                <div class="register-section3">选择支付方式</div>
                <div class="register-section4">
                    <div class="charge-top">
                        <div>
                            <ul class="buy-zf-all float-left">
                                <li class="float-left zf-pub zf-pub1">网银</li>
                                <li class="float-left zf-pub zf-pub2">快捷</li>
                            </ul>
                            <input type="hidden" name="GateBusiId" value="B2C" />
                            <div class="clear"></div>
                        </div>
                        <div class="zf-content">
                            <div class="block zf-con-pub zf-con00">
                                <ul class="float-left">
                                    <li>
                                        <input type="radio" hidefocus="" value="ICBC" name="OpenBankId" id="bank-izgs" checked>
                                        <label class="bank-izgs label-current" for="bank-izgs"></label>
                                        <div class="dg"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <!-- <li>
                                        <input type="radio" hidefocus="" value="ABC" name="OpenBankId" id="bank-abchina">
                                        <label class="bank-abchina" for="bank-abchina"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li> -->
                                    <li>
                                        <input type="radio" hidefocus="" value="BOC" name="OpenBankId" id="bank-boc">
                                        <label class="bank-boc" for="bank-boc"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="CCB" name="OpenBankId" id="bank-ccb">
                                        <label class="bank-ccb" for="bank-ccb"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <!-- <li>
                                        <input type="radio" hidefocus="" value="BOCOM" name="OpenBankId" id="bank-bankcomm">
                                        <label class="bank-bankcomm" for="bank-bankcomm"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li> -->
                                    <li>
                                        <input type="radio" hidefocus="" value="CMBC" name="OpenBankId" id="bank-cmbc">
                                        <label class="bank-cmbc" for="bank-cmbc"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="CMB" name="OpenBankId" id="bank-cmbchina">
                                        <label class="bank-cmbchina" for="bank-cmbchina"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="CEB" name="OpenBankId" id="bank-cebbank">
                                        <label class="bank-cebbank" for="bank-cebbank"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="CIB" name="OpenBankId" id="bank-cib">
                                        <label class="bank-cib" for="bank-cib"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <!-- <li>//这个id是浦发银行的，但是图标是广发银行的，暂时没有图标，所以隐藏掉了
                                        <input type="radio" hidefocus="" value="SPDB" name="OpenBankId" id="bank-cgbchina">
                                        <label class="bank-cgbchina" for="bank-cgbchina"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li> -->
                                    <li>
                                        <input type="radio" hidefocus="" value="PSBC" name="OpenBankId" id="bank-psbc">
                                        <label class="bank-psbc" for="bank-psbc"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="BOS" name="OpenBankId" id="bank-bankofshanghai">
                                        <label class="bank-bankofshanghai" for="bank-bankofshanghai"></label>
                                        <div class="dg" style="display: none;"><img src="/public/images/dg.png"></div>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <div class="zf-con01 zf-con-pub display-none">
                                <ul class="float-left">
                                    <li>
                                        <input type="radio" hidefocus="" value="ICBC" name="kbank" id="kbank-izgs" checked>
                                        <label class="bank-izgs label-current" for="kbank-izgs"></label>
                                        <div class="dg"><img src="/public/images/dg.png"></div>
                                    </li> 
                                    <li>
                                        <input type="radio" hidefocus="" value="CEB" name="kbank" id="kbank-cebbank">
                                        <label class="bank-cebbank" for="kbank-cebbank"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="CCB" name="kbank" id="kbank-ccb">
                                        <label class="bank-ccb" for="kbank-ccb"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="ABC" name="kbank" id="kbank-abchina">
                                        <label class="bank-abchina" for="kbank-abchina"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="PINGAN" name="kbank" id="kbank-pingan">
                                        <label class="bank-pingan" for="kbank-pingan"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="SPDB" name="kbank" id="kbank-spdb">
                                        <label class="bank-spdb" for="kbank-spdb"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="BOS" name="kbank" id="kbank-bankofshanghai">
                                        <label class="bank-bankofshanghai" for="kbank-bankofshanghai"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="CIB" name="kbank" id="kbank-cib">
                                        <label class="bank-cib" for="kbank-cib"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="PSBC" name="kbank" id="kbank-psbc">
                                        <label class="bank-psbc" for="kbank-psbc"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="CCB" name="kbank" id="kbank-ecitic">
                                        <label class="bank-ecitic" for="kbank-ecitic"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="BOC" name="kbank" id="kbank-boc">
                                        <label class="bank-boc" for="kbank-boc"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                    <li>
                                        <input type="radio" hidefocus="" value="BOCOM" name="kbank" id="kbank-bankcomm">
                                        <label class="bank-bankcomm" for="kbank-bankcomm"></label>
                                        <div class="dg display-none"><img src="/public/images/dg.png"></div>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <p class="revise-password-p52 submit1">立即充值</p>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="warning">
                    <span>温馨提示：</span><br>
                    <span>1、您的充值资金直接进入您在第三方支付平台开设的个人账户。</span><br>
                    <span>2、为确保资金安全，充值时使用银行卡进行快捷支付后，该快捷卡默认成为唯一绑定提现卡，其余银行卡自动解绑。</span><br>
                    <span>3、请注意您银行卡充值限制，以免造成不便。</span><br>
                    <span>4、如有任何疑问，请致电云智慧客服电话<?php echo $this->config->config['send_msg_kftel']?>。</span>
                </div>
                <div class="clear"></div>
            </form>
<?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">
	change_height();
</script>
</body>
</html>