<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">用户管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>用户列表</h3>
        </div><!--contenttitle-->
        <div class="tableoptions tableoptions_new"> 
            用户名：<input type="text" name="username" id="username" value="<?php echo isset($search_data['username']) ? $search_data['username'] : '';?>">&nbsp;
            真实姓名：<input type="text" name="realname" id="realname" value="<?php echo isset($search_data['realname']) ? $search_data['realname'] : '';?>">&nbsp;
            电话号码：<input type="text" name="phone" id="phone" value="<?php echo isset($search_data['phone']) ? $search_data['phone'] : '';?>">&nbsp;
            用户类型：
            <select class="radius3" name="type" id="type">
                <option value="0">请选择</option>
                <?php if($role == 1):?>
                    <option value="2" <?php if(isset($search_data['type']) && $search_data['type'] == '2'):?> selected="selected" <?php endif;;?>>普通管理员</option>
                <?php endif;?>
                <option value="3" <?php if(isset($search_data['type']) && $search_data['type'] == '3'):?> selected="selected" <?php endif;?>>客服</option>
                <option value="5" <?php if(isset($search_data['type']) && $search_data['type'] == '5'):?> selected="selected" <?php endif;?>>普通投资人</option>
                <option value="7" <?php if(isset($search_data['type']) && $search_data['type'] == '7'):?> selected="selected" <?php endif;?>>内部融资人</option>
            </select>&nbsp;
            用户等级：
            <select class="radius3" type="level" id="level">
                <option value="0">请选择</option>
                <option value="1" <?php if(isset($search_data['level']) && $search_data['level'] == '1'):?> selected="selected" <?php endif;?>>一级</option>
                <option value="2" <?php if(isset($search_data['level']) && $search_data['level'] == '2'):?> selected="selected" <?php endif;?>>二级</option>
                <option value="3" <?php if(isset($search_data['level']) && $search_data['level'] == '3'):?> selected="selected" <?php endif;?>>三级</option>
                <option value="4" <?php if(isset($search_data['level']) && $search_data['level'] == '4'):?> selected="selected" <?php endif;?>>四级</option>
                <option value="5" <?php if(isset($search_data['level']) && $search_data['level'] == '5'):?> selected="selected" <?php endif;?>>五级</option>
            </select>&nbsp;    
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
                    <th class="head0">真实姓名</th>
                    <th class="head1">类型</th>
                    <th class="head0">等级</th>
                    <th class="head1">电话号码</th>
                    <th class="head0">积分</th>
                    <th class="head1">操作&nbsp;</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($list as $v):?>
                <tr>
                    <td class="center"><?php echo !empty($v['username'])?$v['username']:'(暂无用户名)';?></td>
                    <td class="center"><?php echo $v['realname'];?></td>
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
                            case 5:
                                echo "普通投资人";
                                break;
                            case 7:
                                echo "内部融资人";
                                break;
                        }
                    ?>
                    </td>
                    <td class="center"><?php echo $v['level'];?></td>
                    <td class="center"><?php echo $v['phone'];?></td>
                    <td class="center"><?php echo $v['score'];?></td>
                <td class="center">
                    <?php if($userinfo['role']!=3):?>
                        <a href="/User/editUser?uid=<?php echo $v['uid'];?>" class="edit">编辑</a> &nbsp; 
                    <?php endif;?>
                    <!-- <a href="/User/lockUser?uid=<?php echo $v['uid'];?>&type=<?php echo $v['type']?>" class="delete">冻结</a> -->
                </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>     
<script>
    $(function(){
        $("#addUser").click(function(){
            window.location.href = "/User/addUser";
        })

        $("#submitForm").click(function(){
            var username = $.trim(encodeURIComponent($("#username").val()));
            var realname = $.trim(encodeURIComponent($("#realname").val()));
            var phone    = $.trim($("#phone").val());
            var level    = $("#level").val();
            var type     = $("#type").val();
            window.location.href = "/User/index?username="+username+"&realname="+realname+"&phone="+phone+"&level="+level+"&type="+type;
        })
    })
</script>