<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">用户管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>充值</h3>
    </div>
    <input type="hidden" value="<?php echo $userInfo['type'];?>" id="isbackuser">
    <form class="stdform" action="http://admin.antxd.com/Hfcenter/Recharge" method="post" id="thisForm" target="_blank" >
        <input type="hidden" value="<?php echo $userInfo['uid'];?>" name="uid" id="uid">
        <p>
            <label>现金账户余额</label>
            <span class="field" style="font-size:26px;color:#f0882c"><?php echo $userAccount['money'];?></span>
            <small class="error" >请填写用户名</small>
        </p>

            <p>
                <label>充值金额</label>
                <span class="field">
                    <input type="text" id="rechargeMoney" name="rechargeMoney" class="smallinput" placeholder="请输入金额" />&nbsp;&nbsp;元
                </span>
                <small class="desc" ></small>
            </p>
            <p>
                <label>充值手续费</label>
                <span id="rate" class="field" style="font-size:22px;color:#f0882c">0.00</span>
                <small class="desc" >充值手续费由云智慧代为支付给第三方支付平台。</small>
            </p>
            <p>
                <label>实际到账金额</label>
                <span id="realcharge" class="field" style="font-size:24px;color:#f0882c">0.00</span>
                <small class="desc" ></small>
            </p>
            <p>
                <label>选择支付方式</label>
                <span class="field">
                    <select id="type" style="background:#ccc;" name="type" class="uniformselect">
                        <?php foreach($userBank as $k=>$v):?>
                            <option value="<?php echo $v['card_num'];?>"><?php echo $v['card_num'].'&nbsp;'.$v['deposit_bank'];?></option>
                        <?php endforeach;?>
                    </select>
                </span>
                <small class="desc" >若无选项,请您先绑定银行卡</small>
            </p>
            <p class="stdformbutton">
                <input type="hidden" value="B2C" name="GateBusiId">
                <input id="bank-cib" type="hidden" name="OpenBankId" value="CIB" hidefocus="" checked="checked">
                <button class="submit radius2" id="submit">确认充值</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
            </p>
            </form>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".submit").click(function(){
            parent.modalNew('bob',"提示框",'请在第三方平台完成操作！','','','','','已完成','稍后再试','/User/recharge','');
        });

       $("#rechargeMoney").keyup(function(){
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