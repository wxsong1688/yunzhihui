<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">用户管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>内部融资人列表</h3>
        </div><!--contenttitle-->
        <div class="tableoptions tableoptions_new"> 
            用户名：<input type="text" name="username" id="username" value="<?php echo isset($search_data['username']) ? $search_data['username'] : '';?>">&nbsp;
            手机号：<input type="text" name="phone" id="phone" value="<?php echo isset($search_data['phone']) ? $search_data['phone'] : '';?>">&nbsp;
            身份证号：<input type="text" name="identify" id="identify" value="<?php echo isset($search_data['identify']) ? $search_data['identify'] : '';?>">&nbsp;
            <button class="radius3" id="submitForm">搜索</button>&nbsp;
            <?php if($userinfo['role']!=3):?><span id="addUser">添加</span><?php endif;?>
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
                <tr>　　<!-- 添加日期   用户名 手机号码 身份证号  逾期（利息+本金）  项目   资金  -->
                    <th class="head1">用户名</th>
                    <th class="head0">手机号码</th>
                    <th class="head1">身份证号</th>
                    <th class="head0">添加日期</th>
                    <th class="head1">逾期(利息+本金)</th>
                    <th class="head0">操作</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($list as $v):?>
                <tr>
                    <td class="center"><?php echo !empty($v['username'])?$v['username']:'(暂无用户名)';?></td>
                    <td class="center"><?php echo $v['phone'];?></td>
                    <td class="center"><?php echo $v['identify'];?></td>
                    <td class="center"><?php echo $v['create_time'];?></td>
                    <td class="center"><?php echo $v['late_interest_count']."+".$v['late_amount_count'];?></td>
                    <td class="center">
                        <a href="/Project/index?tenderee_uid=<?php echo $v['uid'];?>" class="table_edit">项目</a> &nbsp; 
                        <a href="/Asset/finance?username=<?php echo $v['username'];?>" class="table_edit">资金</a> &nbsp; 
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>     
<script>
    $(function(){
        $("#addUser").click(function(){
            window.location.href = "/User/addUser?from=2";
        })

        $("#submitForm").click(function(){
            var username = $.trim(encodeURIComponent($("#username").val()));
            var identify = $.trim($("#identify").val());
            var phone    = $.trim($("#phone").val());
            window.location.href = "/User/finance_index?username="+username+"&identify="+identify+"&phone="+phone;
        })
    })
</script>