<link rel="stylesheet" type="text/css" href="/public/css/bid.css"></link>
<script type="text/javascript" src="/public/js/bid.js?<?php echo rand(1000,9999)?>"></script>
<!--bid start -->
<div class="bid-middle" id="assignbid">
    <!--middle section start-->
	<div class="widthMiddle show-center">
    <div class="bid-middle-section show-center">
	
        <div class="bid-section1">
            <div class="assign-title">
                <p class="float-left text-blue pro-detail">项目详情</p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="bid-pro-detail">
                <div class="person-image float-left">
                <?php if($investment['proj_rzpic']): ?>
                    <img src="<?php echo $investment['proj_rzpic'];?>" style="height:210px;width:160px;" class="block">
                <?php else: ?>
                    <img src="/public/images/personPic.png" class="block">
                    <!--<p class="text-style1 text-center"><?php echo $user;?>，您好<a href="/login/exitLogin">退出</a></p>-->
                <?php endif; ?>
                </div>
                <div class="bid-information float-left">
                    <p class="text-blue"><?php echo $investment['pro_name'];?></p>
                    <div class="bid-infor-part1">
                        <ul class="float-left">
                            <li class="float-left">
                                <p class="float-left sicon"><img src="/public/images/sicon1.png" ></p>
                                <p class="float-left text-style2">借款金额：<span class="text-style3"><?php echo number_format($investment['amount']);?>元<span></p>
                            </li>
                            <li class="float-left">
                                <p class="float-left sicon"><img src="/public/images/sicon2.png" ></p>
                                <p class="float-left text-style2">年化收益：<span class="text-style3"><?php echo number_format($investment['year_rate_out'],2);?>%<span></p>
                            </li>
                            <li class="float-left">
                                <p class="float-left sicon"><img src="/public/images/sicon3.png" ></p>
                                <p class="float-left text-style2">项目周期：<span class="text-style3"><?php echo $investment['cycle']*30;?><span>天</p>
                            </li>
                            <li class="float-left">
                                <p class="float-left sicon"><img src="/public/images/sicon4.png" ></p>
                                <p class="float-left text-style2">项目编号：<span class="text-style3"><?php echo $investment['pro_num'];?><span></p>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <p class="text-style2 bid-infor-part2">还款方式：按月付息，到期还款</p>
                    <a class="inline bid-infor-part3">使用本金保障计划</a>
                </div>
                <?php if($investment['status']==80): ?>
                    <div class="bid-tz-80 float-right position-re">
                           <img src="/public/images/rz2.png" class="renbid">
                    </div>
                <?php elseif($investment['status']==5): ?>
                    <div class="bid-tz float-right position-re">
                        <form id="bid" action="/Hfcenter/doInvestment" method="POST" target="_blank">
                            <input type="hidden" name="buid" id="buid" value="<?php echo $investment['buid'];?>" />
                            <input type="hidden" name="pid" id="pid" value="<?php echo $pid;?>" />
                            <input type="hidden" name="ptype" id="ptype" value="<?php echo $investment['type'];?>" />
                            <input type="hidden" name="remain_amount" id="remain_amount" value="<?php echo $investment['remain_amount'];?>">
                            <div class="bid-tz-part1">
                                <label class="text-style4">剩余可投：</label>
                                <span class="text-input"><?php echo number_format($investment['remain_amount'],2);?></span>
                            </div>
                            <div class="bid-tz-part1">
                                <label class="text-style4">账户余额：</label>
                                <span class="text-input"><?php echo number_format($withdrawal_cash,2);?></span>
                                <a href="/Usercenter/redrectRecharge" class="bid-cz">点击充值</a>
                            </div>
                            <div class="bid-tz-part1">
                                <label class="text-style4">投资金额：</label>
                                <?php if($withdrawal_cash <= 0):?>
                                    <input type="text" value="请充值 ↑" class="text-input disabled-input" readonly="true" />
                                <?php else:?>
                                    <input type="text" name="invest_sum" id="invest_sum" value="" class="text-input disabled-input" />
                                <?php endif;?>
                            </div>
                            <p><span class="errinfo" style="margin-left:90px;color:red;"></span></p>
                                <?php if($withdrawal_cash >= 0):?>
                                    <button type="button" class="confirm-tz">确认投资</button>
                                <?php endif;?>

                        </form>
                    </div>
                <?php elseif($investment['status']==10): ?>
                    <div class="bid-tz-80 float-right position-re">
                           <img src="/public/images/rz1.png" class="renbid">
                    </div>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
        </div>
        <div class="bid-section2">
            <div class="assign-title">
                <p class="float-left text-blue pro-detail">借款方信息</p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="bid-sec2-infor">
                <ul class="float-left">
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">借款人&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $investment['financierinfo']['financier_username'];?></span>
                    </li>
                    <li class="float-left">
                    <span class="text-blue">|</span>
                    <span class="text-style2">姓名&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $investment['financierinfo']['financier_realname'];?></span>
                </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">性别&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $investment['financierinfo']['financier_sex_zh'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">年龄&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $investment['financierinfo']['financier_year'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">婚姻状况&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $investment['financierinfo']['financier_mar_zh'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">企业行业&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $investment['companyinfo']['comp_industry'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">企业规模&nbsp;:&nbsp;</span><span class="text-style2">大于<?php echo $investment['companyinfo']['comp_scale'];?>万人</span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">抵押担保&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $investment['companyinfo']['comp_guarantee'];?></span>
                    </li>
                    <li class="float-left" style="width:100%">
                        <span class="text-blue">|</span>
                        <span class="text-style2">项目详情描述&nbsp;:&nbsp;</span>
                        <p class="text-style2" style="width:900px;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $investment['projectinfo']['proj_desc'];?></span>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="bid-section3">
            <div class="assign-title">
                <p class="float-left text-blue pro-detail">云智慧平台审核</p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="bid-sec3-info">
                <ul class="float-left">
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-00.png"><!--shIcon-01-->
                        </div>
                        <p class="float-left text-style2 ">借款人身份验证</p>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-00.png"><!--shIcon-01-->
                        </div>
                        <p class="float-left text-style2 ">企业执照认证（营业执照，组织机构代码证，税务登记证等）</p>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-00.png">
                        </div>
                        <p class="float-left text-style2 ">借款人面对面详谈</p>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-00.png">
                        </div>
                        <p class="float-left text-style2 ">企业实地考察（企业行业发展趋势，经营情况）</p>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-00.png">
                        </div>
                        <p class="float-left text-style2 ">融资满标后，定期进行项目跟踪和监督</p>
                        <div class="clear"></div>
                    </li>
                    <!--<li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-01.png">
                        </div>
                        <p class="float-left text-style2 ">备注</p>
                        <div class="clear"></div>
                    </li>-->
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="bid-section4">
            <div class="assign-title">
                <p class="float-left text-blue pro-detail">投资记录</p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="bid-sec4-infor">
                <ul class="float-left">
                    <li>
                        <ul class="float-left">
                            <li class="float-left text-center text-style2 invest-li">
                                投资人
                            </li>
                            <li class="float-left text-center text-style2 invest-li">
                                投资时间
                            </li>
                            <li class="float-left text-center text-style2 invest-li">
                                投资金额
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </li>
<?php foreach($pro_user as $k => $v){ ?>
                    <li>
                        <ul class="float-left">
                            <li class="float-left text-center text-style6 invest-li">
                                <?php if(empty($v['username'])){
                                    echo substr_replace($v['phone'],'****',3,4);
                                }else{
                                    echo $v['username'];       
                                }?>
                            </li>
                            <li class="float-left text-center text-style6 invest-li">
                                <?php echo $v['create_time'];?>
                            </li>
                            <li class="float-left text-center text-style6 invest-li">
                                <?php echo number_format($v['invest_sum'],2);?>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </li>
<?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="text-center">
            <a href="#" class="return-tb">返回投标</a>
        </div>

    </div>
	</div>
</div>
<!--assignbid end -->
<script type="text/javascript" language=JavaScript charset="UTF-8">
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];      
        if(e && e.keyCode==13){ // enter 键
            return;
        }
    };
</script>
<!--footer start-->
<?php $this->load->helper("footer");?>
<!--footer end-->