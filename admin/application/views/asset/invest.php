<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">投资人资产管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>投资人列表</h3>
            </div><!--contenttitle--><!-- 投资人用户名，手机号，账户总资产，已冻结金额，收入，支出，可提现金额 ，投资中金额，利息总收入，当天利息收入-->
            <div class="tableoptions tableoptions_new"> 
                <!-- 用户名：<input type="text" name="username" id="username" value="<?php echo isset($search_data['username']) ? $search_data['username'] : '';?>">&nbsp; -->
                手机号：<input type="text" name="phone" id="phone" value="<?php echo isset($search_data['phone']) ? $search_data['phone'] : '';?>">&nbsp;
                <button class="radius3" id="submitForm">搜索</button>&nbsp;
                </div><!--tableoptions-->
<!--手机号  个人总资产  投资中金额  账户余额 已获得总收益  充值总额  提现总额  冻结 项目  流水-->
                <table cellpadding="0" cellspacing="0" border="0" id="table1" class="stdtable stdtablecb">
                    <colgroup>
                    <col class="con1" />
                    <col class="con0" />
                    <col class="con1" />
                    <col class="con0" />
                    <col class="con1" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="head0">手机号</th>
                            <th class="head1">个人总资产</th>
                            <th class="head0">投资中金额</th>
                            <th class="head1">账户余额</th>
                            <th class="head0">已获得总收益</th>
                            <th class="head1">充值总额</th>
                            <th class="head0">提现总额</th>
                            <th class="head1">操作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach($list as $v):?>
                        <tr>
                            <td class="center"><?php echo $v['phone'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['money'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['used_money'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['withdrawal_cash'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['gain_total'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['recharge_total'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['withdrawal_cash_total'];?>&nbsp;</td>
                            <td class="center">
                                <a href="/Project/pusers?uid=<?php echo $v['uid'];?>" class="table_edit">项目</a>&nbsp;
                                <a href="/Asset/investFlow?uid=<?php echo $v['uid'];?>" class="table_edit">流水</a>&nbsp;
                                <?php if($userinfo['role'] == 1 || $userinfo['role'] == 2):?>
                                    <?php if($v['iflock'] == 1):?>
                                        <a href="/Asset/lockUser?uid=<?php echo $v['uid'];?>&from=1&type=0" class="table_edit">解冻</a> &nbsp; 
                                    <?php else:?>
                                        <a href="/Asset/lockUser?uid=<?php echo $v['uid'];?>&from=1&type=1" class="table_edit">冻结</a> &nbsp; 
                                    <?php endif;?>
                                <?php endif;?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
<script>
    $(function(){
        $("#submitForm").click(function(){
            /*var username = $.trim(encodeURIComponent($("#username").val()));*/
            var phone   = $.trim($("#phone").val());
            window.location.href = "/Asset/invest?phone="+phone;
        })
    })
</script>