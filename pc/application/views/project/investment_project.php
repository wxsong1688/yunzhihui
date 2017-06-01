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
    <script type="text/javascript" src="/public/js/detail-hk.js?<?php echo rand(1000,9999)?>"></script>
	
    
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
                        <li class="float-left text-center ucaccrent">
                            所投项目
                        </li>
                        <li class="float-left text-center" onclick="window.location='/Usercenter/investment?type=credit'">
                            所投债权
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="investment-con-sec10">
                    <ul class="float-left">
                        <li>
                            <!-- <div class="invest-state">
                                <p class="invest-state-title float-left">项目状态：</p>
                                <ul class="float-left">
                                    <li class="float-left">
                                        <input type="checkbox" name="invest-stateAll" checked>
                                        <span>全部</span>
                                    </li>
                                    <li class="float-left">
                                        <input type="checkbox" name="invest-state" checked>
                                        <span>投标中</span>
                                    </li>
                                    <li class="float-left">
                                        <input type="checkbox" name="invest-state" checked>
                                        <span>回款中</span>
                                    </li>
                                    <li class="float-left">
                                        <input type="checkbox" name="invest-state" checked>
                                        <span>逾期中</span>
                                    </li>
                                    <li class="float-left">
                                        <input type="checkbox" name="invest-state" checked>
                                        <span>已完结</span>
                                    </li>
                                    <li class="float-left">
                                        <input type="checkbox" name="invest-state" checked>
                                        <span>已转出</span>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </div> -->
                            <div class="invest-section2-part3">
                                <table border="0" class="invest-table">
                                    <thead>
                                    <tr class="even">
                                        <th width="100">投标日期</th>
                                        <th width="32"></th>
                                        <th width="80">项目编号</th>
                                        <th width="32"></th>
                                        <th width="80">投标金额</th>
                                        <th width="32"></th>
                                        <th width="80">年化收益</th>
                                        <th width="32"></th>
                                        <th width="80">应收本息</th>
                                        <th width="32"></th>
                                        <th width="70">已回款</th>
                                        <th width="32"></th>
                                        <th width="70">项目状态</th>
                                        <th width="32"></th>
                                        <th width="110">本金归还日期</th>
                                        <th width="32"></th>
                                        <th width="100">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($mypro as $key => $v):?>
                                        <tr class="<?php $key%2==0?'even':'';?>">
                                            <td class="font-style3"><?php echo date("Y-m-d",strtotime($v['create_time']));?></td>
                                            <td class="font-style3">|</td>
                                            <td class="font-style3">
                                            <a href="/Project/bid?pro_id=<?php echo $v['proInfo']['id'];?>" target="_blank"><font style=" text-decoration: underline;" color="#ce0044"><?php echo $v['proInfo']['pro_num'];?></font></a>
                                            </td>
                                            <td class="font-style3">|</td>
                                            <td class="font-style5">￥<?php echo number_format($v['invest_sum'],2);?></td>
                                            <td class="font-style3">|</td>
                                            <td class="font-style4"><?php echo number_format($v['proInfo']['year_rate_out'],2);?>%</td>
                                            <td class="font-style3">|</td>
                                            <td class="font-style5"><?php echo number_format($v['readyGainPro'],2);?></td>
                                            <td class="font-style3">|</td>
                                            <td class="font-style5">
                                            <a href="javascript:void(0);" class="detail-hk" data-id="<?php echo $v['id']?>">详情</a>
                                            </td>
                                            <td class="font-style3">|</td>
                                            <td class="font-style3"><?php echo $v['proInfo']['stat'];?></td>
                                            <td class="font-style3">|</td>
                                            <td class="font-style3">
<?php 
if($v['proInfo']['status'] == 10){
    $returnTime = date("Y-m-d",strtotime($v['proInfo']['full_time']) + ($v['proInfo']['cycle']*30 - 1)*24*3600);
}else{
    $returnTime = "--";
}
echo $returnTime;
?>
                                            </td>
                                            <td class="font-style3">|</td>
                                            <td>
<?php 
if($v['proInfo']['status'] == 10){
    if($v['credit_to_uid']!=0){
        $return_status_explan = "已转让";
    }else{
        if($v['credit_status'] == 0){
            $return_status_explan = "<a href='/Projectcredit/assignCreditorCon?mpid=".$v['id']."' target='_blank' class='font-style4'>转让该项目</a>";
        }else if($v['credit_status'] == 1){
            $return_status_explan = "转让中";
        }else if($v['credit_status'] == 10){
            $return_status_explan = "已转让";
        }
    }
}else if($v['proInfo']['status'] == 5){
    $return_status_explan = "未满标";
}else{
    $return_status_explan = "--";
}
echo $return_status_explan;
?>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="list-pagination">
                                <div class="y-pagination">
                                    <?php echo $this->pageclass->show(1); ?>
                                    <span class="p-elem p-item-go">第<input class="p-ipt-go" id="p-ipt-go" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"  >页<a href="javascript:void(0);" class="p-btn-go" id="p-btn-go">GO</a></span>
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
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">
	change_height();
    $("#p-btn-go").click(function(){
        var page = $("#p-ipt-go").val();
        if(!isNaN(page)){
            alert("请输入正确页数");return;
        }
        window.location="/Usercenter/investment?type=project&pg="+page;
    });
</script>
</html>