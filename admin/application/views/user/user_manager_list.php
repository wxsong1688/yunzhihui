<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">用户管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>管理员列表</h3>
        </div><!--contenttitle-->
        <div class="tableoptions tableoptions_new"> 
            用户名：
            <input type="text" name="username" id="username" value="<?php echo isset($search_data['username']) ? $search_data['username'] : '';?>">&nbsp;
            添加日期：
            <input  class="Wdate" type="text" onclick="WdatePicker()" name="time_start" id="time_start" value="<?php echo isset($search_data['time_start']) ? $search_data['time_start'] : '';?>"/>&nbsp;-&nbsp;
            <input  class="Wdate" type="text" onclick="WdatePicker()" name="time_end" id="time_end" value="<?php echo isset($search_data['time_end']) ? $search_data['time_end'] : '';?>"/>&nbsp;-&nbsp;
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
                <tr>
                    <th class="head1">用户名</th>
                    <th class="head1">类型</th>
                    <th class="head0">添加日期</th>
                    <th class="head1">操作&nbsp;</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($list as $v):?>
                <tr>
                    <td class="center"><?php echo !empty($v['username'])?$v['username']:'(暂无用户名)';?></td>
                    <td class="center">
                    <?php 
                        switch ($v['type']) {
                            case 1:
                                echo "超级管理员";
                                break;
                            case 2:
                                echo "普通管理员";
                                break;
                            case 3:
                                echo "客服";
                                break;
                            case 4:
                                echo "风险控制管理员";
                                break;
                        }
                    ?>
                    </td>
                    <td class="center"><?php echo $v['create_time'];?></td> 
                    <td class="center">
                        <a href="/Project/index?check_uname=<?php echo $v['username'];?>" class="table_edit">审核的项目</a> &nbsp; 
                        <!--超级管理员可以编辑客服和普通管理员，普通管理员可以编辑客服-->
                        <?php if(($userinfo['role']==1 && $v['type']!=1) || ($userinfo['role']==2 && $v['type']==3)):?>
                            <a href="/User/editUser?uid=<?php echo $v['uid'];?>&from=1" class="table_edit">编辑</a> &nbsp; 
                        <?php endif;?>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>     
<script>
    $(function(){
        $("#addUser").click(function(){
            window.location.href = "/User/addUser?from=1";
        })

        $("#submitForm").click(function(){
            var username    = $.trim(encodeURIComponent($("#username").val()));
            var time_start  = $("#time_start").val();
            var time_end    = $("#time_end").val();
            window.location.href = "/User/manager_index?username="+username+"&time_start="+time_start+"&time_end="+time_end;
        })
    })
</script>