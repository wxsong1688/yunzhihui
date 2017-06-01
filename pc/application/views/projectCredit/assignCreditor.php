<link rel="stylesheet" type="text/css" href="/public/css/assignCreditor.css"></link>
<link rel="stylesheet" type="text/css" href="/public/css/bid.css"></link>
<script type="text/javascript" src="/public/js/creditor.js"></script>
<!--creditor-middle start-->
<div class="creditor-middle" id="assignCreditor">
    <!--middle section start-->
    <div class="creditor-middle-section show-center">
        <div class="creditor-section1">
            <div class="creditor-title">
            <p class="float-left creditor-icon"><img src="/public/images/cret.png">&nbsp;</p>
                <p class="float-left text-blue pro-detail">债权转让</p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="creditor-content">
                <div class="float-left creditor-content-section1">
                    <div class="cre-sec1-part1">
                        <ul class="float-left">
                            <li class="float-left">
                                <p class="font-sty1">债权价值：<span class="font-sty2"><?php echo number_format($creditInfo['credit_amount'],2);?></span></p>
                            </li>
                            <li class="float-right">
                                <p class="font-sty1">原始项目收益：<span class="font-sty2"><?php echo $projectInfo['year_rate_out'];?>%</span></p>
                            </li>
                            <li class="float-left">
                                <p class="font-sty1">剩余时间：<span class="font-sty2"><?php echo $projectInfo['residue_time'];?>天</span></p>
                            </li>
                            <li class="float-right">
                                <p class="font-sty1">折让比例：<span class="font-sty2"><?php echo number_format($creditInfo['discount'],2);?>%</span></p>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div class="cre-sec1-part2">
                        <p class="font-sty1 float-left">转让项目状态：<span class="font-sty3 cre-state">正常还款中</span></p>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="float-right creditor-content-section2">
                    <div class="cre-sec2-part1">
                    <form id="buyCredit" action="/Hfcenter/hfCredit" target="_blank" method="post">
                        <input type="hidden" name="credit_id" value="<?php echo $creditInfo['id']?>">
                        <input type="hidden" name="hf_usrCustId" value="<?php echo $creditInfo['hf_usrCustId']?>">
                        <input type="hidden" name="credit_amount" value="<?php echo $creditInfo['credit_amount']?>">
                        <input type="hidden" name="real_amount" value="<?php echo $creditInfo['real_amount']?>">
                        <input type="hidden" name="creditor_uid" value="<?php echo $creditInfo['creditor_id']?>">
                        <input type="hidden" name="bidOrdId" value="<?php echo $itemInfo['hforder_id']?>">
                        <input type="hidden" name="jk_hf_usrCustId" value="<?php echo $itemInfo['jk_hf_usrCustId']?>">
                        <input type="hidden" name="pro_id" value="<?php echo $itemInfo['pro_id']?>">
                        <input type="hidden" name="pro_num" value="<?php echo $projectInfo['pro_num']?>">
                        <span class="cre-top-left cre-tb"></span>
                        <span class="cre-top-right cre-tb"></span>
                        <span class="cre-bot-left cre-tb"></span>
                        <span class="cre-bot-right cre-tb"></span>
                        <p class="font-sty4">实际支付价格: <span><?php echo number_format($creditInfo['real_amount'],2);?></span>
                        <!-- <br/><span class="font-sty5">（优惠<?php //echo $creditInfo['privilege'];?>元）</span></p> -->
                        <p class="font-sty4">待收本息: <span><?php echo number_format($creditInfo['ready_gain'],2)?></span></p>
                        <p class="font-sty4">实际年化收益: <span><?php echo $projectInfo['real_earnings'];?>%</span></p>
                        <p class="text-center">
                            <input type="submit" class="buy-creditor inline-block" value="购买债权"/>
                        </p>
                    </form>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
		<div class="bid-section2">
            <div class="creditor-title">
                <p class="float-left text-blue pro-detail">原始项目借款信息--
                <a href="/Project/bid?pro_id=<?php echo $projectInfo['id'];?>" target="_blank"><?php echo $projectInfo['pro_num'];?></a></p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="bid-sec2-infor">
                <ul class="float-left">
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">平台用户名&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $projectInfo['financierinfo']['financier_realname'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">姓名&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $projectInfo['financierinfo']['financier_username'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">性别&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $projectInfo['financierinfo']['financier_sex_zh'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">年龄&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $projectInfo['financierinfo']['financier_year'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">婚姻状况&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $projectInfo['financierinfo']['financier_mar_zh'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">企业行业&nbsp;:&nbsp;</span><span class="text-style2"><?php echo $projectInfo['companyinfo']['comp_industry'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">企业规模&nbsp;:&nbsp;</span><span class="text-style2">大于<?php echo $projectInfo['companyinfo']['comp_scale'];?></span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">抵押担保&nbsp;:&nbsp;</span><span class="text-style2">XXXXXXXXXX</span>
                    </li>
                    <li class="float-left">
                        <span class="text-blue">|</span>
                        <span class="text-style2">项目详情描述&nbsp;:&nbsp;</span>
                        <p class="text-style2" style="width:900px;" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $projectInfo['projectinfo']['proj_desc'];?></span>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="bid-section3">
            <div class="creditor-title">
                <p class="float-left text-blue pro-detail">云智慧平台审核</p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="bid-sec3-info">
                <ul class="float-left">
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-00.png">
                        </div>
                        <p class="float-left text-style2 ">借款人身份验证</p>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-00.png">
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
                        <p class="float-left text-style2 ">融资满标后，不定期进行项目跟踪和监督</p>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <div class="check-xt float-left">
                            <img src="/public/images/shIcon-01.png">
                        </div>
                        <p class="float-left text-style2 ">备注</p>
                        <div class="clear"></div>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
	</div>
    <!--middle section start-->
</div>
<!--creditor-middle end-->
<!--footer start-->
<?php $this->load->helper('footer');?>
<!--footer end-->
</body>
</html>