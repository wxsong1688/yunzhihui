<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">我的资产管理</h1>
        <span class="pagedesc"></span>
    </div>
    <div>&nbsp;</div>
    <!--contenttitle　已融资总金额  账户余额  下次还款日期　 下期应还金额  还款计划      -->
    <table cellpadding="0" cellspacing="0" border="0" id="table1" class="stdtable stdtablecb">
        <thead>
            <!-- <tr>
                <th class="head1">已融资总金额</th>
                <th class="head0">账户余额</th>
                <th class="head1">下次还款日期</th>
                <th class="head0">下期应还金额</th>
                <th class="head1">还款计划</th>
            </tr> -->
        </thead>
        <tbody>
            <!-- <tr>
                <td class="center"><?php //echo isset($accountInfo['tenderee_money'])?$accountInfo['tenderee_money']:0;?>元</td>
                <td class="center"><?php //echo isset($accountInfo['withdrawal_cash'])?$accountInfo['withdrawal_cash']:0;?>元</td>
                <td class="center"><?php //echo isset($payinfo['date'])?$payinfo['date']:''?></td>
                <td class="center"><?php //echo isset($payinfo['money'])?$payinfo['money']:''?>元</td>
                <td class="center"><a href="/Asset/paylist" class="table_edit">查看</a></td>
            </tr> -->
        </tbody>
    </table>
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
    })
</script>
