<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">用户管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>提现</h3>
    </div>
    <form class="stdform" action="http://admin.antxd.com/Hfcenter/getCash" method="post" id="thisForm" target="_blank" >
        <p>
            <label>现金账户余额</label>
            <span class="field" style="font-size:26px;color:#f0882c"><?php echo $userAccount['money'];?></span>
            <small class="error" >请填写用户名</small>
        </p>
            <p>
                <label>提现金额</label>
                <span class="field">
                    <input type="text" id="withdrawalsMoney" name="withdrawalsMoney" class="smallinput" placeholder="请输入金额" />&nbsp;&nbsp;元
                </span>
                <small class="desc" ></small>
            </p>
            <p>
                <label>提现方式</label>
                <span class="field">
                    <input type="radio" name="wtype" checked="checked" value="GENERAL" />一般&nbsp;&nbsp;
                    <input type="radio" name="wtype" value="FAST" />快速&nbsp;&nbsp;
                    <input type="radio" name="wtype" value="IMMEDIATE" />及时
                </span>
                <small class="desc" ></small>
            </p>
            <p>
                <label>提现手续费</label>
                <span id="rate" class="field" style="font-size:22px;color:#f0882c">0.00</span>
                <small class="desc" >充值手续费由云智慧代为支付给第三方支付平台。</small>
            </p>
            <p>
                <label>到账时间</label>
                <span class="field">1-3个工作日到账</span>
                <small class="desc" ></small>
            </p>
            <p>
                <label>选择银行卡</label>
                <span class="field">
                    <select id="type" style="background:#ccc;" name="type" class="uniformselect">
                        <?php foreach($userBank as $k=>$v):?>
                            <option value="<?php echo $v['card_num'];?>"><?php echo $v['card_num'].'&nbsp;'.$v['deposit_bank'];?></option>
                        <?php endforeach;?>
                    </select>
                </span>
                <small class="desc" ></small>
            </p>
            <p class="stdformbutton">
                <button class="submit radius2" id="submit">确认提现</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
            </p>
            </form>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".submit").click(function(){
            parent.modalNew('bob',"提示框",'请在第三方平台完成操作！','','','','','已完成','稍后再试','/User/withdrawals','');
        });      
       $("#withdrawalsMoney").keyup(function(){
            var rate= 0.00;
            var outMoney = $("#withdrawalsMoney").val();
            var outMoneytype = $(".withdraw-current").val();
            if( outMoneytype=="GENERAL" ){
                rate = 2;
            }else{
                rate = outMoney*0.0005+2;
            }
            $("#rate").html('');
            $("#rate").html(rate.toFixed(2));
            $("#withdrawalsAmount").text(outMoney);

        });
    })
</script>
<script type="text/javascript">
$("#pwd1").blur(function(){
    if($("#pwd1").val() != ''){
        $("#pwd1_repeat_p").show();
    }else{
        $("#pwd1_repeat_p").hide();
    }
})

function checkRequired (item,isnum)
{
    var data = isnum == 1 ? 0 : '';
    if(item.val() == data)
    {
        item.parent().next().show();
        return 0;
    }
    return 1;
}
</script>