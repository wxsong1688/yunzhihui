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
                <li class="float-left" id="fin_gd" onclick="window.location='/FinanciaTransactions?p=fin_gd'">高端定制<span></span></li>
                <li class="float-left high-end-current" id="fin_zq" onclick="window.location='/FinanciaTransactions?p=fin_zq'">债权转让</li>
            </ul>
            <div class="clear"></div>
        </div>
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
                        <!-- <div class="projectKind">
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
                        </div> -->
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
                                                            <a href="/Project/bid?pro_id=<?php echo $value['pro_id']; ?>" style="color: #ff6600 !important;">
                                                                <p class="text-sty0 float-left see-pro">
                                                                查看原始项目
                                                                （<?php echo $value['pro_num']; ?>）
                                                                </p>
                                                            </a>
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
                                                                    <p class="text-sty3 height00"><?php echo $value['remain_date']?>天</p>
                                                                    <p class="text-sty2">剩余时间</p>
                                                                </li>
                                                                <li class="float-left" style="width:21%">
                                                                    <p class="text-sty4 height00"><?php echo number_format($value['real_amount'],2); ?></p>
                                                                    <p class="text-sty2">债权价格</p>
                                                                </li>
                                                                <li class="float-left" style="width:14%">
                                                                    <p class="text-sty3 height00"><?php echo round($value['discount']*100,2); ?>%</p>
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
<?php if($value['p_status']==10){ ?>
    <?php if($value['c_status']==1){ ?>
                                                    <a class="buy-lj-w" href="javascript:void(0);" onclick="checkCredite(<?php echo $value['c_id'].','.$value['creditor_id'];?>)">立即购买</a>
    <?php }else{ ?>
                                                    <a class="buy-lj" href="javascript:void(0);">已成交</a>
    <?php } ?>
<?php }elseif(in_array($value['p_status'],array(25,80))) { ?>
                                                    <a class="buy-lj" href="javascript:void(0);">已结束</a>
<?php } ?>
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
                                <?php echo $this->pageclass->show(1); ?>
                                <span class="p-elem p-item-go">第<input class="p-ipt-go" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"  >页<a href="javascript:void(0);" class="p-btn-go">GO</a></span>
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

    function checkCredite(cid,creditor_id){
        $.ajax({
            type:'post',
            url:'/FinanciaTransactions/checkAssignCreditor',
            data:{cid:cid,creditor_id:creditor_id},  
            error:function(){  
                alert("error occured!!!");  
            },  
            success:function(data){
                if(data=='goon'){
                    var url = "/financiaTransactions/assignCreditor?id="+cid;
                    window.location=url;
                }else{
                    modalNew('bob',"债权转让",'债权购买者不能为债权发布人，您可以在“我的账户->债权转让”中撤销转让！','','','','','确定','','/FinanciaTransactions','');
                }
            }       
        }); 
    }
</script>
</html>