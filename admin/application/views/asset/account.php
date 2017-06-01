<div class="centercontent">
    <div class="pageheader">
        <h3 class="pagetitle">账户管理</h3>
        <span class="pagedesc"></span>
    </div><!--pageheader-->

    <div >
        <h4>　　
            <div>
                您的账户余额： <?php echo $accountInfo['withdrawal_cash'];?>元&nbsp;&nbsp;
                <a href="javascript:void(0);" class="table_edit withdrawalsBank">绑卡</a>&nbsp;&nbsp;
                <a href="/User/recharge" class="table_edit">充值</a>&nbsp;&nbsp;
                <a href="/User/withdrawals" class="table_edit">提现</a>&nbsp;&nbsp;
                <a href="/User/editUserPwd" class="table_edit">密码修改</a>　  
            </div>
        </h4>
    </div>
    <div>
        <h4>　　
            <div>还款：您下次还款日期为：<?php echo !empty($payinfo['date'])?$payinfo['date']:""; ?>，所需还款金额为：<?php echo !empty($payinfo['money'])?$payinfo['money']:0; ?> 元  &nbsp;&nbsp;
             <a href="/Asset/paylist?s=1" class="table_edit">查看详情</a>
            </div>
        </h4>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        changeH();
        $(window).resize(function(){
            changeH();
        });
        function changeH(){
            $("#bob").height($(window).height());
        }
        $(".withdrawalsBank").click(function(){
            parent.modalNew('bob',"提示框",'请在第三方平台完成操作！','','','','','已完成','稍后再试','/Asset/account','');
            var url = "/Hfcenter/userBindCard";
            window.open(url);
        });
    })
</script>
