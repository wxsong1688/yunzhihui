<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">融资人资产管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>融资人列表</h3>
            </div><!--contenttitle--><!-- 用户名   已融资总金额   欠款总金额  账户余额  下期应还本息  冻结  项目   -->
            <div class="tableoptions tableoptions_new"> 
                用户名：<input type="text" name="username" id="username" value="<?php echo isset($search_data['username']) ? $search_data['username'] : '';?>">&nbsp;
                <!-- 手机号：<input type="text" name="phone" id="phone" value="<?php echo isset($search_data['phone']) ? $search_data['phone'] : '';?>">&nbsp; -->
                <button class="radius3" id="submitForm">搜索</button>&nbsp;
                <!-- <?php if($userinfo['role'] == 7):?>
                    <span id="tixian" onclick="alert('提现功能待开放')">提现</span>
                <?php endif;?> -->
                </div><!--tableoptions-->
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
                            <th class="head1">用户名</th>
                            <th class="head0">已融资总金额</th>
                            <!-- <th class="head1">欠款总金额</th> -->
                            <th class="head0">账户余额</th>
                            <th class="head1">下期应还本息</th>
                            <th class="head0">操作&nbsp;</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach($list as $v):?>
                        <tr>
                            <td class="center"><?php echo $v['username'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['tenderee_money'];?>&nbsp;</td>
                            <!-- <td class="center"><?php echo $v['debt_money'];?></td> -->
                            <td class="center"><?php echo $v['money'];?>&nbsp;</td>
                            <td class="center paymoney" uid="<?php echo $v['uid'];?>">&nbsp;</td>
                            <td class="center">
                                <a href="/Project/index?tenderee_uid=<?php echo $v['uid'];?>" class="table_edit">项目</a> &nbsp; 
                                <?php if($userinfo['role'] == 1 || $userinfo['role'] == 2):?>
                                    <?php if($v['iflock'] == 1):?>
                                        <a href="/Asset/lockUser?uid=<?php echo $v['uid'];?>&from=0&type=0" class="table_edit">解冻</a> &nbsp; 
                                    <?php else:?>
                                        <a href="/Asset/lockUser?uid=<?php echo $v['uid'];?>&from=0&type=1" class="table_edit">冻结</a> &nbsp; 
                                    <?php endif;?>
                                <?php endif;?>&nbsp;
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
<script>
    $(function(){
        $("#submitForm").click(function(){
            var username = $.trim(encodeURIComponent($("#username").val()));
            /*var phone   = $.trim($("#phone").val());*/
            window.location.href = "/Asset/finance?username="+username;
        })

        $(".paymoney").each(function(){
            var uid = $(this).attr('uid');
            var obj = $(this);
            $.ajax({
                url:'/Asset/getPayforMoney',
                data:{uid:uid},
                type:"post",
                dataType: "json",
                success:function(msg){
                    obj.html(msg.money);
                }
            })
        })
    })
</script>