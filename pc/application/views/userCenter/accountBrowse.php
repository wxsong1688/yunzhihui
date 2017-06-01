<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>账户浏览</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/accountBrowse.css"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
	
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
<div class="accountBrowse user-all" id="accountBrowse">
    <div class="user-section user-right-height" id="accountBrowse-section">
        <p class="accountBrowse-title usercenter-title">账户浏览</p>
        <div class="accountBrowse-content">
            <div class="accountBrowse-content-section1">
                <div class="upload float-left">
                    <div class="uploadImg">                    
                        <?php if($user['headpic']): ?>
                            <img src="<?php echo $user['headpic'];?>" width="100px" height="120px">
                        <?php else: ?>
                            <img src="/public/images/uploadImg.png">
                        <?php endif; ?>
                        
                    </div>
                </div>
                <div class="float-left browse-sec1-part1">
                    <p class="brower-text1 brower-bg-color">上次登录：<span><?php echo $user['last_login'];?></span></p>
                    <h2>尊敬的<?php echo $user['username']?>，您好！</h2>
                    <div class="accoutSafety">
                        <p class="browse-zw-text float-left brower-text1">用户等级：</p>
                        <?php if($user['level']==0 || $user['level']==1): ?>
                            <p class="long-bar1 float-left">普通用户</p>
                        <?php elseif($user['level']==2): ?>
                            <p class="long-bar2 float-left">精英会员</p>
                        <?php else:?>
                            <p class="long-bar3 float-left">高端会员</p>
                        <?php endif;?>
                    </div>
                </div>
                <div class="account-sum float-right">
                    账户总资产：<span class="brower-text7">¥<?php echo number_format($userAccount['total'],2);?></span><span class="brower-text8">元</span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="accountBrowse-content-section2">
                <div class="browse-sec2-00 brower-text2">
                    号外：年度重磅【拍活宝】8月27日全面开放申购！只为做你的活期理财神器！
                    <a href="javascript:void(0);">立即开枪</a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="accountBrowse-content-section3">
                <div class="brower-sec3-part1">
                    <div class="float-left brower-sec3-part10 brower-pub">
                        <p class="bs-part10-title brower-text3">账户余额：</p>
                        <div class="bs-part10-body">
                            <p class="brower-text4">账户余额：<span class="brower-text7">¥<?php echo number_format($userAccount['withdrawal_cash'],2);?></span><span class="brower-text8">元</span></p>
                            <div class="brower-button text-right">
                                <a href="/usercenter/recharge" class="recharge">充值</a>
                                <a href="/usercenter/withdrawals" class="withdrawals">提现</a>
                            </div>
                        </div>
                    </div>
                    <div class="float-right brower-sec3-part11 brower-pub">
                        <p class="bs-part11-title brower-text3">今日收益：</p>
                        <div class="bs-part10-body">
                            <p class="brower-text4 float-left accout-zc">今日收益：
                                <span class="brower-text7"><?php echo number_format($userAccount['gain_curr_day'],2);?></span>
                                <span class="brower-text8">元</span>
                            </p>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="brower-sec3-part2">
                    <p class="bs-part2-title brower-text3">账户总览：</p>
                    <div class="bs-part2-body">
                        <ul class="float-left">
                            <li class="float-left brower-border">
                                <p class="brower-text4 accout-zc">已得总收益：</p>
                                <p class="brower-text7"><?php echo number_format($userAccount['gain_total'],2);?>元</p>
                            </li>
                            <li class="float-left brower-border">
                                <p class="brower-text4 accout-zc">投资中本金：</p>
                                <p class="brower-text7"><?php echo number_format($userAccount['used_money']);?>元</p>
                            </li>
                            <li class="float-left brower-border">
                                <p class="brower-text4 accout-zc">应收本息：</p>
                                <p class="brower-text7"><?php echo number_format($userAccount['expected'],2);?>元</p>
                            </li>
                            <li class="float-left brower-border">
                                <p class="brower-text4 accout-zc">已充值总金额：</p>
                                <p class="brower-text7"><?php echo number_format($userAccount['recharge_total'],2);?>元</p>
                            </li>
							<li class="float-left">
                                <p class="brower-text4 accout-zc">已提现总金额：</p>
                                <p class="brower-text7"><?php echo number_format($userAccount['withdrawal_cash_total'],2);?>元</p>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="brower-sec3-part3">
                    <h3>温馨提示：今日收益是根据您账户内所有投资项的当日收益计算所得，不是实时到账，实际到账时间为每月15日或者项目结束。</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">
	change_height();
</script>
</body>
</html>