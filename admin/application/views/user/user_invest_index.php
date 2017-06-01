<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">用户管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>投资人列表</h3>
        </div><!--contenttitle-->
        <div class="tableoptions tableoptions_new"> 
            昵称：<input type="text" name="username" id="username" value="<?php echo isset($search_data['username']) ? $search_data['username'] : '';?>">&nbsp;
            手机号：<input type="text" name="phone" id="phone" value="<?php echo isset($search_data['phone']) ? $search_data['phone'] : '';?>">&nbsp;
            姓名：<input type="text" name="realname" id="realname" value="<?php echo isset($search_data['realname']) ? $search_data['realname'] : '';?>">&nbsp;
            身份证号：<input type="text" name="identify" id="identify" value="<?php echo isset($search_data['identify']) ? $search_data['identify'] : '';?>">&nbsp;
            <button class="radius3" id="submitForm">搜索</button>&nbsp;
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
                <tr>　　<!--   姓名  手机号码 身份证号   用户等级  积分  注册日期  项目（包括债权）  资金  -->
                    <th class="head1">姓名</th>
                    <th class="head0">手机号码</th>
                    <th class="head1">身份证号</th>
                    <th class="head0">用户等级</th>
                    <th class="head1">积分</th>
                    <th class="head0">注册日期</th>
                    <th class="head1">操作</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($list as $v):?>
                <tr>
                    <td class="center"><?php echo !empty($v['realname'])?$v['realname']:'';?></td>
                    <td class="center"><?php echo $v['phone'];?></td>
                    <td class="center"><?php echo $v['identify'];?></td>
                    <td class="center"><?php echo $v['level'];?></td>
                    <td class="center"><?php echo $v['score'];?></td>
                    <td class="center"><?php echo $v['create_time'];?></td>
                    <td class="center">
                        <a href="/Project/pusers?uid=<?php echo $v['uid']?>" class="table_edit">项目</a> &nbsp; 
                        <a href="/Asset/invest?uid=<?php echo $v['uid']?>" class="table_edit">资金</a> &nbsp; 
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>     
<script>
    $(function(){
        $("#submitForm").click(function(){
            var username = $.trim(encodeURIComponent($("#username").val()));
            var realname = $.trim(encodeURIComponent($("#realname").val()));
            var identify = $.trim($("#identify").val());
            var phone    = $.trim($("#phone").val());
            window.location.href = "/User/invest_index?username="+username+"&identify="+identify+"&phone="+phone+"&realname="+realname;
        })
    })
</script>