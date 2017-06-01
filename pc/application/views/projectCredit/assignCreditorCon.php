<link rel="stylesheet" type="text/css" href="/public/css/assignCreditor.css"></link>
<script type="text/javascript" src="/public/js/creditor.js"></script>
<!--creditor-middle start-->
<div class="creditor-middle" id="assignCreditor">
    <!--middle section start-->
    <div class="creditor-middle-section show-center">
        <div class="creditor-section1">
            <div class="creditor-title">
                <p class="float-left creditor-icon"><img src="/public/images/zq.png">&nbsp;</p>
                <p class="float-left text-blue pro-detail">债权转让</p>
                <p class="float-right blue-line"></p>
                <div class="clear"></div>
            </div>
            <div class="creditor-content text-center">
                <p class="text-center creditor-con-p">
                    <?php if( $type=="1" ): ?>
                    <span class="cre-bg0">您购买的债权<span id="tz-program">
                    <a href="/Project/bid?pro_id=<?php echo $pro_id;?>" target="_blank"><font color="#5e940c">(<?php echo $pro_num;?>)</font></a>
                    <?php else: ?>
                    <span class="cre-bg0">您投资的项目<span id="tz-program">
                    <a href="/Project/bid?pro_id=<?php echo $pro_id;?>" target="_blank"><font color="#5e940c"><?php echo $pro_num;?></font></a>
                    <?php endif; ?>
                    </span>还有
                    <font color="#ff6600 !important"><?php echo $expire_date;?></font>
                    天可以获取本息收益了，现在进行转让需要进行折价处理！</span>
                </p>
                <div class="creditor-con-part show-center">
                    <ul class="float-left">
                        <li>
                            <label>债权价值</label>
                            <input type="text" value="<?php echo number_format($credit_amount,2);?> 元" class="color-green" readonly="readonly"  style="background:transparent;border:0px solid #ffffff">
                        </li>
                        <li>
                            <label>折价率</label>
                            <input type="text" value="<?php echo round($discount,2);?> %" class="color-red" readonly="readonly"  style="background:transparent;border:0px solid #ffffff">
                        </li>
                        <li>
                            <label>转让价格</label>
                            <input type="text" value="<?php echo number_format($real_amount,2);?> 元" class="color-green" readonly="readonly"  style="background:transparent;border:0px solid #ffffff">
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <a href="javascript:void(0);" onclick="return confirmAct(<?php echo $pro_id.','.$item_id;?>)" class="creditor-btn">确认转让</a>
                <a href="/Usercenter" class="creditor-btn">取消转让</a>
            </div>
        </div>
    </div>
</div>
<!--footer start-->
<?php $this->load->helper('footer');?>
<!--footer end-->
</body>
</html>