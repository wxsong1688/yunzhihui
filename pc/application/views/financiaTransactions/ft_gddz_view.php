<link rel="stylesheet" type="text/css" href="/public/css/financiaTransactions.css"></link>
<script type="text/javascript" src="/public/js/financiaTransactions.js"></script>
<!--index-middle start -->
<div class="index-middle" id="highEndCustom">
    <!--middle section start-->
    <div class="index-middle-section show-center">
        <div class="highEndSection1">
            <ul>
                <li class="float-left" id="fin_ph" onclick="window.location='/FinanciaTransactions?p=fin_ph'">普惠金融</li>
                <li class="float-left" id="fin_jy" onclick="window.location='/FinanciaTransactions?p=fin_jy'">精英理财</li>
                <li class="float-left high-end-current" id="fin_gd" onclick="window.location='/FinanciaTransactions?p=fin_gd'">高端定制<span></span></li>
                <li class="float-left" id="fin_zq" onclick="window.location='/FinanciaTransactions?p=fin_zq'">债权转让</li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="highEndSection2">
            <ul>
                <li class="block">
                    <div class="highSection2-part4">
                        <div class="high-part4-sec1">
                            <p class="float-left hps-title">高端定制&nbsp;|&nbsp;<span>High-end customization</span>&nbsp;●</p>
                            <p class="float-right high-line high-top1"></p>
                            <div class="clear"></div>
                        </div>
                        <div class="high-part4-sec2">
                            <p>高端定制是专为具有一定风险承受能力的高端用户提供的一项理财服务，其起投金额为十万元，为平台高端用户所专享，可以获得超高年化收益。
                            其实际标的均经过云智慧平台严谨、仔细、全面的考察。</p>
                            <div class="high-btn">
                                <p class="how-hignEnd">如何高端&nbsp;></p>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="high-part4-sec3">
                            <p class="float-left hps-title1">●</p>
                            <p class="float-right high-line1 high-top2"></p>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="index-middle-section-center show-center">
                        <div class="im-sec2">
                            <div class="im-sec2-part">
                                <ul>
                                    <?php foreach($project_g as $key => $val): ?>
                                        <li class="im-sec2-part00">
                                            <ul class="float-left">
                                                <li class="float-left im-sec2-part-00 ">
                                                    <?php if($val['type']==2): ?>
                                                        <img src="/public/images/pu.png">
                                                    <?php elseif($val['type']==3): ?>
                                                        <img src="/public/images/jing.png"> 
                                                    <?php elseif($val['type']==4): ?>
                                                        <img src="/public/images/hign.png">
                                                    <?php endif;?>
                                                </li>
                                                <li class="float-left im-sec2-part-01 /public-left0">
                                                    <p class="text-center height2">
                                                        <a class="im-sec2-detail diff-text1" href="/Project/bid?pro_id=<?php echo $val['id'];?>"><?php echo $val['pro_num'];?></a>
                                                    </p>
                                                    <p class="text-center diff-text1"><?php echo $val['pro_name'];?></p>
                                                </li>
                                                <li class="float-left im-sec2-part-02 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo number_format($val['year_rate_out'],2);?>%</p>
                                                    <p class="text-center diff-text1">年化收益</p>
                                                </li>
                                                <li class="float-left im-sec2-part-03 /public-left0">
                                                    <p class="text-center diff-text3 height1"><?php echo $val['cycle']*30;?>天</p>
                                                    <p class="text-center diff-text1">项目周期</p>
                                                </li>
                                                <li class="float-left im-sec2-part-04 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo number_format($val['amount'],2);?>元</p>
                                                    <p class="text-center diff-text1">融资金额</p>
                                                </li>
                                                <li class="float-left im-sec2-part-05 /public-left0">
                                                    <div>
                                                        <img src="/public/images/mon.png" class="float-left">
                                                        <p class="diff-text4 float-left">剩余金额:<span>￥<?php echo number_format($val['remain_amount'],2);?></span></p>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="long-bar">
                                                        <div class="out-bar float-left" style="border: 1px solid rgb(51, 153, 255);">
                                                            <div class="bar-00"></div>
                                                            <?php if($key==0): ?>
                                                                <div class="bar-06-slider bar-slider" style="width: 0px;"></div>
                                                            <?php elseif($key==1): ?>
                                                                <div class="bar-07-slider bar-slider" style="width: 0px;"></div>
                                                            <?php elseif($key==2): ?>
                                                                <div class="bar-08-slider bar-slider" style="width: 0px;"></div>
                                                            <?php endif;?>
                                                        </div>
                                                        <label class="bar-num" style="color: rgb(51, 153, 255);"><?php echo $val['percentage'];?></label>
                                                        <div class="clear"></div>
                                                    </div>
                                                </li>
                                                <li class="float-right im-sec2-part-06 /public-left0">
                                                    <?php if($val['status']==5 && $val['amount']>$val['gained_amount'] && $val['remain_amount']!=0): ?>
                                                        <a class="block button-tb button-tb-color1" href="javascript:void(0)" onclick="investment(<?php echo $val['id'];?>)">
                                                    <?php else: ?>
                                                        <a class="block button-tb button-tb-color2" href="javascript:void(0)">
                                                    <?php endif;?>
                                                        <?php echo $val['status_zh'];?>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="clear"></div>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <div class="list-pagination">
                                <div class="y-pagination">
                                    <?php echo $this->pageclass->show(1); ?>
                                    <span class="p-elem p-item-go">第<input class="p-ipt-go" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"  >页<a href="javascript:void(0);" class="p-btn-go">GO</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <!--middle section1 end-->
</div>
<!--footer start-->
<?php $this->load->helper('footer');?>
<!--footer end-->
</body>
<script type="text/javascript">
    $(".p-btn-go").click(function(){
        var page = $(".p-ipt-go").val();
        if(!isNaN(page)){
            alert("请输入正确页数");return;
        }
        window.location="/FinanciaTransactions?p=fin_zq&pg="+page;
    });
    function investment(id)
    {
         window.location.href="/Project/bid?pro_id="+id; 
    }
</script>
</html>