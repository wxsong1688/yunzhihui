<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">项目管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3><?php echo $userinfo['role'] == 7 ? "我的项目" : "项目列表";?></h3>
            </div><!--contenttitle　 项目编号  项目金额  项目周期  发布日期 状态 审核人-->
            <div class="tableoptions tableoptions_new"> 
                项目编号：<input type="text" name="pro_id" id="pro_id" value="<?php echo isset($search_data['pro_id']) ? $search_data['pro_id'] : '';?>">&nbsp;
                项目金额：<input type="text" name="amount" id="amount" value="<?php echo isset($search_data['amount']) ? $search_data['amount'] : '';?>" >&nbsp;
                项目周期：
                <select class="radius3" type="cycle" id="cycle">
                    <option value="0">请选择</option>
                    <?php for($i=1 ;$i<=12 ;$i++):?>
                        <?php if($i == 1 || ($i%3 == 0)):?>
                            <option value="<?php echo $i;?>" <?php if(isset($search_data['cycle']) &&$search_data['cycle'] == $i):?> selected="selected" <?php endif;?>><?php echo $i*30;?>天</option>
                        <?php endif;?>
                    <?php endfor;?>
                </select>&nbsp;
                发布日期：
                <input  class="Wdate" type="text" onclick="WdatePicker()" name="time_start" id="time_start" value="<?php echo isset($search_data['time_start']) ? $search_data['time_start'] : '';?>"/>&nbsp;-&nbsp;
                <input  class="Wdate" type="text" onclick="WdatePicker()" name="time_end" id="time_end" value="<?php echo isset($search_data['time_end']) ? $search_data['time_end'] : '';?>"/>&nbsp;-&nbsp;
                状态：
                <select class="radius3" name="status" id="status">
                    <option value="0">请选择</option>
                    <option value="1" <?php if(isset($search_data['status']) && $search_data['status'] == '1'):?> selected="selected" <?php endif;?>>待审核</option>
                    <option value="2" <?php if(isset($search_data['status']) && $search_data['status'] == '2'):?> selected="selected" <?php endif;?>>初审通过</option>
                    <option value="5" <?php if(isset($search_data['status']) && $search_data['status'] == '5'):?> selected="selected" <?php endif;?>>融资中</option>
                    <option value="6" <?php if(isset($search_data['status']) && $search_data['status'] == '6'):?> selected="selected" <?php endif;?>>审核驳回</option>
                    <option value="10" <?php if(isset($search_data['status']) && $search_data['status'] == '10'):?> selected="selected" <?php endif;?>>已满标</option>
                    <option value="20" <?php if(isset($search_data['status']) && $search_data['status'] == '20'):?> selected="selected" <?php endif;?>>未满标</option>
                    <option value="25" <?php if(isset($search_data['status']) && $search_data['status'] == '25'):?> selected="selected" <?php endif;?>>清算完成</option>
                    <option value="30" <?php if(isset($search_data['status']) && $search_data['status'] == '30'):?> selected="selected" <?php endif;?>>还款延时</option>
                    <option value="80" <?php if(isset($search_data['status']) && $search_data['status'] == '80'):?> selected="selected" <?php endif;?>>还款完成</option>
                </select>&nbsp;
                <?php if($userinfo['role'] != 7):?>
                    审核人：<input type="text" name="check_uname" id="check_uname" value="<?php echo isset($search_data['check_uname']) ? $search_data['check_uname'] : '';?>">&nbsp;
                <?php endif;?>
                <button class="radius3" id="submitForm">搜索</button>&nbsp;
                <?php if($userinfo['role'] == 7):?>
                    <span id="addProject">添加</span>
                <?php endif;?>
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
                            <th class="head1">项目名称</th>
                            <th class="head0">项目编号(详情)</th>
                            <th class="head1">项目金额</th>
                            <th class="head0">项目周期</th>
                            <th class="head1">发布日期</th>
                            <?php if($userinfo['role'] != 7):?>
                                <th class="head0">融资利率</th>
                                <th class="head1">投资利率</th>
                                <th class="head0">状态</th>
                                <th class="head1">审核人(初审/复审)</th>
                            <?php else:?>
                                <th class="head0">利率</th>
                                <th class="head1">下期应还</th>
                                <th class="head0">状态</th>
                                <th class="head1">投资列表</th>
                            <?php endif;?>
                                <th class="head0">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $v): ?>
                        <tr>
                            <td class="center"><?php echo $v['pro_name'];?></td>
                            <td class="center"><a href="/Project/detail?id=<?php echo $v['id'];?>" class="table_edit"><?php echo $v['id'];?></a></td>
                            <td class="center"><span class="amount"><?php echo $v['amount'];?></span></td>
                            <td class="center"><?php echo $v['cycle'];?>个月</td>
                            <td class="center"><?php echo $v['create_time'];?></td>
                            <td class="center"><?php echo $v['year_rate_in'];?>%</td>
                            <?php if($userinfo['role'] != 7):?>
                                <td class="center">
                                    <?php echo $v['year_rate_out'];?>
                                </td>
                            <?php else:?>
                                <td class="center paymoney" uid="<?php echo $v['tenderee_uid'];?>" proid="<?php echo $v['id'];?>"></td>
                            <?php endif;?>
                            <td class="center" title="<?php if($v['status']==6):?><?php echo "驳回理由：".$v['reject_reason'];?><?php endif;?>">
                            <?php 
                                switch ($v['status']) {
                                    case 1:
                                        echo "待审核";
                                        break;
                                    case 2:
                                        echo "初审通过";
                                        break;
                                    case 5:
                                        echo "融资中";
                                        break;
                                    case 6:
                                        echo "<font color='red'>审核驳回</font>";
                                        break;
                                    case 10:
                                        echo "已满标";
                                        break;
                                    case 20:
                                        echo "未满标";
                                        break;
                                    case 25:
                                        echo "清算完成";
                                        break;
                                     case 30:
                                        echo "还款延时";
                                        break;
                                    default:
                                        echo "还款完成";
                                        break;
                                }
                            ?>  
                            </td>
                        <td class="center">
<?php if($userinfo['role'] == 7):?>
                                <a href="/Project/pusers?id=<?php echo $v['id']?>&pro_name=<?php echo $v['pro_name'];?>" class="table_edit">查看投资列表</a>
<?php if(in_array($v['status'],array(10,25,30,80))){ ?>
                                <a href="/Asset/paylist?pro_id=<?php echo $v['id']?>" class="table_edit">还款列表</a>
<?php } ?>
<?php else:?>
                                <?php 
                                    if(isset($checkusers[$v['audio_uid']])){
                                        echo $checkusers[$v['audio_uid']]['username'];
                                    }
                                    if(isset($checkusers[$v['raudio_uid']])){
                                        echo " / ".$checkusers[$v['raudio_uid']]['username'];
                                    }
                                ?>
                                &nbsp;
<?php endif;?>
                        </td>
                        <td class="center">
                            <?php if($userinfo['role'] != 7):?>
                                <a href="/Project/pusers?id=<?php echo $v['id']?>&pro_name=<?php echo $v['pro_name'];?>" class="table_edit">投资列表</a>&nbsp;&nbsp;
                                <?php if($v['status']==1 && $userinfo['role'] == 2 ):?>
                                    <a href="/Project/checkProject?id=<?php echo $v['id'];?>" class="table_edit">初审</a>
                                <?php endif;?>
                                <?php if($v['status']==2 && $userinfo['role'] == 4):?>
                                    <a href="/Project/checkProject?id=<?php echo $v['id'];?>" class="table_edit">复审</a>
                                <?php endif;?>
                            <?php else:?>
                                <?php if($v['status']==1 || $v['status'] == 6):?>
                                    <a href="/Project/editProject?id=<?php echo $v['id'];?>" class="edit">编辑</a> &nbsp; 
                                <?php endif;?>
                            <?php endif;?>
                            <?php if($userinfo['role'] == 1 && $v['status']==1 && $v['audio_uid']==0):?>
                                <a href="/Project/assigned?id=<?php echo $v['id'];?>" class="edit">指派初审</a> &nbsp;
                            <?php elseif($userinfo['role'] == 1 && $v['status']==1 && $v['audio_uid']!=0):?>
                                <a href="javascript:void(0);" class="edit">初审已指定</a> &nbsp;
                            <?php else:?>
                                &nbsp;
                            <?php endif;?>    
                        </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
<script>
window.onload=function ()
{
    //项目列表
    $(".amount").each(function(){
        var obj = $(this);
        var avalue = tran(obj.html());
        obj.html(avalue);
    })
}
$(function(){
    $("#addProject").click(function(){
        window.location.href = "/Project/addProject";
    })

    $(".paymoney").each(function(){
        var uid    = $(this).attr('uid');
        var pro_id = $(this).attr('proid');
        var obj    = $(this);
        $.ajax({
            url:'/Asset/getPayforMoney',
            data:{uid:uid,pro_id:pro_id},
            type:"post",
            dataType: "json",
            success:function(msg){
                obj.html(msg.money);
            }
        })
    })

    $("#submitForm").click(function(){
        var pro_id     = $.trim($("#pro_id").val());
        var amount     = $.trim($("#amount").val());
        var cycle      = $("#cycle").val();
        var time_start = $.trim($("#time_start").val());
        var time_end   = $.trim($("#time_end").val());
        var status     = $("#status").val();
        var check_uname= $.trim(encodeURIComponent($("#check_uname").val()));
            check_uname = check_uname== "undefined" ? "" : check_uname;
        window.location.href = "/Project/index?pro_id="+pro_id+"&amount="+amount+"&cycle="+cycle+"&time_start="+time_start+"&time_end="+time_end+"&status="+status+"&check_uname="+check_uname;
    })
})

</script>
