<link rel="stylesheet" type="text/css" href="/public/css/financiaTransactions.css"></link>
<script type="text/javascript" src="/public/js/financiaTransactions.js"></script>
<!--index-middle start -->
<div class="index-middle" id="highEndCustom">
    <!--middle section start-->
    <div class="index-middle-section show-center">
        <div class="highEndSection1">
            <ul>
                <li class="float-left high-end-current" id="fin_ph">普惠金融</li>
                <li class="float-left" id="fin_jy">精英理财</li>
                <li class="float-left" id="fin_gd">高端定制<span></span></li>
                <li class="float-left" id="fin_zq">债权转让</li>
            </ul>
            <div class="clear"></div>
        </div>
        <input type="hidden" value="<?php echo $projectNavId;?>" id="projectNavId" name="projectNavId" />
        <div class="highEndSection2">
            <ul>
                <li class="block">
                    <div class="highSection2-part4">
                        <div class="high-part4-sec1">
                            <p class="float-left hps-title">债权转让&nbsp;|&nbsp;<span>Credit assignment</span>&nbsp;●</p>
                            <p class="float-right high-line high-top1"></p>
                            <div class="clear"></div>
                        </div>
                        <div class="high-part4-sec2">
                            <p>债权转让是指项目原始投资人将所投项目的债权转让给其他投资人， 因债权转让本金一般会按一定折价比例优惠转让，债权接收人会获得比原始项目更高的年化收益。 没事就快来债权转让专区淘个宝吧~</p>
                        </div>
                        <div class="high-part4-sec3">
                            <p class="float-left hps-title1">●</p>
                            <p class="float-right high-line1 high-top2"></p>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="index-middle-section-center show-center">
                        <div class="projectKind">
                            <p class="float-left pk-title">原始项目类型&nbsp;&nbsp;|</p>
                            <ul class="float-left">
                                <li class="float-left">
                                    <img src="/public/images/radio-00.png"><span>普惠金融</span>
                                </li>
                                <li class="float-left">
                                    <img src="/public/images/radio-00.png"><span>精英理财</span>
                                </li>
                                <li class="float-left">
                                    <img src="/public/images/radio-00.png"><span>高端定制</span>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <div class="proKind-content">
                            <ul class="float-left">
                                <li class="block">
                                    <ul class="float-left proKind-con-part1">
                                       <?php foreach($projectCredit_all as $key => $value): ?>
                                            <li>
                                                <div class="float-left pk-part1-00"><img src="/public/images/creditor.png"> </div>
                                                <div class="float-left pk-part1-01">
                                                    <div class="float-left proKind-middle">
                                                        <div>
                                                            <p class="text-sty0 float-left see-pro">
                                                            查看原始项目
                                                            <a href="/Project/bid?pro_id=<?php echo $value['pro_id']; ?>" style="color: #ff6600 !important;">
                                                            （<?php echo $value['pro_num']; ?>）
                                                            </a></p>
                                                        
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="top00 proKind-li3">
                                                            <ul class="float-left">
                                                                <li class="float-left" style="width:15%">
                                                                    <p class="text-sty1 height00"><?php echo $value['realname'];?></p>
                                                                    <p class="text-sty2">出让人</p>
                                                                </li>
                                                                <li class="float-left" style="width:22%">
                                                                    <p class="text-sty3 height00"><?php echo number_format($value['credit_amount']);?><span class="text-sty5"></span></p>
                                                                    <p class="text-sty2">债权价值</p>
                                                                </li>
                                                                <li class="float-left" style="width:14%">
                                                                    <p class="text-sty4 height00" title="10.66%"><?php echo round($value['year_rate_out'],2);?>%</p>
                                                                    <p class="text-sty2">年化收益</p>
                                                                </li>
                                                                <li class="float-left" style="width:14%">
                                                                    <p class="text-sty3 height00"><?php echo $value['remain_date']?><span class="text-sty5">天</span></p>
                                                                    <p class="text-sty2">剩余时间</p>
                                                                </li>
                                                                <li class="float-left" style="width:21%">
                                                                    <p class="text-sty4 height00"><?php echo number_format($value['real_amount']); ?></p>
                                                                    <p class="text-sty2">债权价格</p>
                                                                </li>
                                                                <li class="float-left" style="width:14%">
                                                                    <p class="text-sty3 height00"><?php echo round($value['discount'],2); ?>%</p>
                                                                    <p class="text-sty2">折让比例</p>
                                                                </li>
                                                            </ul>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </div>
                                                    <div class="float-right angle-zs">
                                                        <p class="text-sty5">推荐指数
                                                        <?php 
                                                        for($i=1;$i<=5;$i++){
                                                            if($i <= $value['star']){
                                                                echo '<span class="angle00"></span>';
                                                            }else{
                                                                echo '<span class="angle01"></span>';
                                                            }
                                                        }
                                                        ?>
                                                        </p>
                                                        <p class="text-sty7 posi-re">实际收益高于原始项目</p>
                                                        <p class="text-sty7 posi-re">推荐转让<img src="/public/images/tui.png"></p>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="float-right pk-part1-02 text-center">
                                                    <a class="buy-lj" href="/financiaTransactions/assignCreditor?id=<?php echo $value['id'];?>">立即购买</a>
                                                </div>
                                                <div class="clear"></div>
                                            </li>
                                        <?php endforeach;?>

                                    </ul>
                                    <div class="clear"></div>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
						<div class="list-pagination">
                            <div class="y-pagination">
                                <a href="javascript:void(0);" class="p-prev p-elem">上一页</a>
                                <span class="current p-elem">1</span>
                                <a href="javascript:void(0);" class="p-item p-elem">2</a>
                                <a href="javascript:void(0);" class="p-item p-elem">3</a>
                                <a href="javascript:void(0);" class="p-item p-elem">4</a>
                                <a href="javascript:void(0);" class="p-item p-elem">5</a>
                                <a href="javascript:void(0);" class="p-item p-elem">……</a>
                                <a href="javascript:void(0);" class="p-item p-elem">21</a>
                                <a href="javascript:void(0);" class="p-item p-elem">22</a>
                                <a href="javascript:void(0);" class="p-next p-elem">下一页</a>
                                <span class="p-total p-elem">共6页</span>
                                <span class="p-elem p-item-go">第<input class="p-ipt-go">页<a href="javascript:void(0);" class="p-btn-go">GO</a></span>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="display-none">
                    <div class="highSection2-part4">
                        <div class="high-part4-sec1">
                            <p class="float-left hps-title">普惠金融&nbsp;|&nbsp;<span>Inclusive Finance</span>&nbsp;●</p>
                            <p class="float-right high-line high-top1"></p>
                            <div class="clear"></div>
                        </div>
                        <div class="high-part4-sec2">
                            <p>普惠金融作为一项面向普通工薪阶层的理财产品，其投资门槛低，一百元至一万元均可投资。让每个人都能享受投资的乐趣，
							获得稳健的收益，是您小额理财的不二选择。其实际标的均经过云智慧平台严谨、仔细、全面的考察。</p>
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
                                    <?php foreach($project_p as $key => $val): ?>
                                        <li class="im-sec2-part00">
                                            <ul class="float-left">
                                                <li class="float-left im-sec2-part-00 ">
                                                    <?php if($key==0): ?>
                                                        <img src="/public/images/hign.png">
                                                    <?php elseif($key==1): ?>
                                                        <img src="/public/images/jing.png"> 
                                                    <?php elseif($key==2): ?>
                                                        <img src="/public/images/pu.png">
                                                    <?php endif;?>
                                                </li>
                                                <li class="float-left im-sec2-part-01 /public-left0">
                                                    <p class="text-center height2">
                                                        <a class="im-sec2-detail diff-text1" href="javascript:void(0);"><?php echo $val['pro_num'];?></a>
                                                    </p>
                                                    <p class="text-center diff-text1"><?php echo $val['pro_name'];?></p>
                                                </li>
                                                <li class="float-left im-sec2-part-02 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo $val['year_rate_out'];?></p>
                                                    <p class="text-center diff-text1">年化收益</p>
                                                </li>
                                                <li class="float-left im-sec2-part-03 /public-left0">
                                                    <p class="text-center diff-text3 height1"><?php echo $val['cycle']*30;?>天</p>
                                                    <p class="text-center diff-text1">项目周期</p>
                                                </li>
                                                <li class="float-left im-sec2-part-04 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo $val['amount'];?>元</p>
                                                    <p class="text-center diff-text1">融资金额</p>
                                                </li>
                                                <li class="float-left im-sec2-part-05 /public-left0">
                                                    <div>
                                                        <img src="/public/images/mon.png" class="float-left">
                                                        <p class="diff-text4 float-left">剩余金额:<span>￥<?php echo $val['remain_amount'];?></span></p>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="long-bar">
                                                        <div class="out-bar float-left" style="border: 1px solid rgb(51, 153, 255);">
                                                            <div class="bar-00"></div>
                                                            <?php if($key==0): ?>
                                                                <div class="bar-00-slider bar-slider" style="width: 0px;"></div>
                                                            <?php elseif($key==1): ?>
                                                                <div class="bar-01-slider bar-slider" style="width: 0px;"></div>
                                                            <?php elseif($key==2): ?>
                                                                <div class="bar-02-slider bar-slider" style="width: 0px;"></div>
                                                            <?php endif;?>
                                                        </div>
                                                        <label class="bar-num" style="color: rgb(51, 153, 255);"><?php echo $val['percentage'];?></label>
                                                        <div class="clear"></div>
                                                    </div>
                                                </li>
                                                <li class="float-right im-sec2-part-06 /public-left0">
                                                    <?php if($val['status']==10): ?>
                                                        <a class="block button-tb button-tb-color2" href="javascript:void(0)">
                                                    <?php else: ?>
                                                        <a class="block button-tb button-tb-color1" href="javascript:void(0)" onclick="investment(<?php echo $val['id'];?>)">
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
                        </div>
                    </div>
                </li>
                <li class="display-none">
                    <div class="highSection2-part4">
                        <div class="high-part4-sec1">
                            <p class="float-left hps-title">精英理财&nbsp;|&nbsp;<span>Elite financial management</span>&nbsp;●</p>
                            <p class="float-right high-line high-top1"></p>
                            <div class="clear"></div>
                        </div>
                        <div class="high-part4-sec2">
                            <p>精英理财是为都市中产，精英人士量身打造的一项理财服务，投在金额在一万元至十万元之间，
							具有低风险、高收益的特点，其实际标的均经过云智慧平台严谨、仔细、全面的考察。</p>
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
                                    <?php foreach($project_j as $key => $val): ?>
                                        <li class="im-sec2-part00">
                                            <ul class="float-left">
                                                <li class="float-left im-sec2-part-00 ">
                                                    <?php if($key==0): ?>
                                                        <img src="/public/images/hign.png">
                                                    <?php elseif($key==1): ?>
                                                        <img src="/public/images/jing.png"> 
                                                    <?php elseif($key==2): ?>
                                                        <img src="/public/images/pu.png">
                                                    <?php endif;?>
                                                </li>
                                                <li class="float-left im-sec2-part-01 /public-left0">
                                                    <p class="text-center height2">
                                                        <a class="im-sec2-detail diff-text1" href="javascript:void(0);"><?php echo $val['pro_num'];?></a>
                                                    </p>
                                                    <p class="text-center diff-text1"><?php echo $val['pro_name'];?></p>
                                                </li>
                                                <li class="float-left im-sec2-part-02 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo $val['year_rate_out'];?></p>
                                                    <p class="text-center diff-text1">年化收益</p>
                                                </li>
                                                <li class="float-left im-sec2-part-03 /public-left0">
                                                    <p class="text-center diff-text3 height1"><?php echo $val['cycle']*30;?>天</p>
                                                    <p class="text-center diff-text1">项目周期</p>
                                                </li>
                                                <li class="float-left im-sec2-part-04 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo $val['amount'];?>元</p>
                                                    <p class="text-center diff-text1">融资金额</p>
                                                </li>
                                                <li class="float-left im-sec2-part-05 /public-left0">
                                                    <div>
                                                        <img src="/public/images/mon.png" class="float-left">
                                                        <p class="diff-text4 float-left">剩余金额:<span>￥<?php echo $val['remain_amount'];?></span></p>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="long-bar">
                                                        <div class="out-bar float-left" style="border: 1px solid rgb(51, 153, 255);">
                                                            <div class="bar-00"></div>
                                                             <?php if($key==0): ?>
                                                                <div class="bar-03-slider bar-slider" style="width: 0px;"></div>
                                                            <?php elseif($key==1): ?>
                                                                <div class="bar-04-slider bar-slider" style="width: 0px;"></div>
                                                            <?php elseif($key==2): ?>
                                                                <div class="bar-05-slider bar-slider" style="width: 0px;"></div>
                                                            <?php endif;?>
                                                        </div>
                                                        <label class="bar-num" style="color: rgb(51, 153, 255);"><?php echo $val['percentage'];?></label>
                                                        <div class="clear"></div>
                                                    </div>
                                                </li>
                                                <li class="float-right im-sec2-part-06 /public-left0">
                                                    <?php if($val['status']==10): ?>
                                                        <a class="block button-tb button-tb-color2" href="javascript:void(0)">
                                                    <?php else: ?>
                                                        <a class="block button-tb button-tb-color1" href="javascript:void(0)" onclick="investment(<?php echo $val['id'];?>)">
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
                        </div>
                    </div>
                </li>
                <li class="display-none">
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
                                                    <?php if($key==0): ?>
                                                        <img src="/public/images/hign.png">
                                                    <?php elseif($key==1): ?>
                                                        <img src="/public/images/jing.png"> 
                                                    <?php elseif($key==2): ?>
                                                        <img src="/public/images/pu.png">
                                                    <?php endif;?>
                                                </li>
                                                <li class="float-left im-sec2-part-01 /public-left0">
                                                    <p class="text-center height2">
                                                        <a class="im-sec2-detail diff-text1" href="/Project/bid?pro_id=<?php echo $val['pro_id'];?>"><?php echo $val['pro_num'];?></a>
                                                    </p>
                                                    <p class="text-center diff-text1"><?php echo $val['pro_name'];?></p>
                                                </li>
                                                <li class="float-left im-sec2-part-02 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo $val['year_rate_out'];?></p>
                                                    <p class="text-center diff-text1">年化收益</p>
                                                </li>
                                                <li class="float-left im-sec2-part-03 /public-left0">
                                                    <p class="text-center diff-text3 height1"><?php echo $val['cycle']*30;?>天</p>
                                                    <p class="text-center diff-text1">项目周期</p>
                                                </li>
                                                <li class="float-left im-sec2-part-04 /public-left0">
                                                    <p class="text-center diff-text2 height1"><?php echo $val['amount'];?>元</p>
                                                    <p class="text-center diff-text1">融资金额</p>
                                                </li>
                                                <li class="float-left im-sec2-part-05 /public-left0">
                                                    <div>
                                                        <img src="/public/images/mon.png" class="float-left">
                                                        <p class="diff-text4 float-left">剩余金额:<span>￥<?php echo $val['remain_amount'];?></span></p>
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
                                                    <?php if($val['status']==10): ?>
                                                        <a class="block button-tb button-tb-color2" href="javascript:void(0)">
                                                    <?php else: ?>
                                                        <a class="block button-tb button-tb-color1" href="javascript:void(0)" onclick="investment(<?php echo $val['id'];?>)">
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
    function investment(id)
    {
         window.location.href="/Project/bid?pro_id="+id; 
    }
</script>
</html>