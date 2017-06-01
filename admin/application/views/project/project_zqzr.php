<!--
债权转让：债券笔数
项目编号，出让人，接收人，转让时间
项目编号，出让人，接收人，债券价格，债券价值，转让时间，时间比例
-->
<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">债权转让管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>债权转让列表</h3>
            </div><!--contenttitle-->
            <div class="tableoptions tableoptions_new"> 
                项目编号：
                    <input type="text" name="pro_id" id="pro_id" value="<?php echo isset($search_data['pro_id'])?$search_data['pro_id']:''?>">&nbsp;
                出让人：
                    <input type="text" name="creditor_name" id="creditor_name" value="<?php echo isset($search_data['creditor_name'])?$search_data['creditor_name']:''?>" >&nbsp;
                <!-- 接收人：
                    <input type="text" name="buyer_uid" id="buyer_uid" value="<?php echo isset($search_data['buyer_uid'])?$search_data['buyer_uid']:''?>" >&nbsp; -->
                转让时间：
                    <input  class="Wdate" type="text" onclick="WdatePicker()" name="time_start" id="time_start" value="<?php echo isset($search_data['time_start']) ? $search_data['time_start'] : '';?>"/>&nbsp;-&nbsp;
                    <input  class="Wdate" type="text" onclick="WdatePicker()" name="time_end" id="time_end" value="<?php echo isset($search_data['time_end']) ? $search_data['time_end'] : '';?>"/>&nbsp;-&nbsp;
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
                        <tr>
                            <th class="head1">项目编号</th>
                            <th class="head1">发布时间</th>
                            <th class="head0">转让时间</th>
                            <th class="head0">出让人</th>
                            <th class="head1">接收人</th>
                            <th class="head0">债券价值</th>
                            <th class="head1">债券价格</th>
                            <th class="head1">结束时间</th>
                            <th class="head1">折价比例</th>
                            <th class="head1">状态</th>
                        </tr>
                    </thead>        
                    <tbody>
                        <?php foreach($list as $v):?>
                        <tr>
                            <td class="center"><?php echo $v['pro_id'];?></td>
                            <td class="center"><?php echo $v['create_time'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['deal_time'];?>&nbsp;</td>
                            <td class="center"><?php echo isset($usernames[$v['creditor_id']])?$usernames[$v['creditor_id']]['username']:'';?>&nbsp;</td>
                            <td class="center"><?php echo isset($usernames[$v['buyer_uid']])?$usernames[$v['buyer_uid']]['username']:'';?>&nbsp;</td>                           
                            <td class="center"><?php echo $v['credit_amount'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['discount']?$v['credit_amount']*$v['discount']:'';?>&nbsp;</td>
                            <td class="center"><?php echo $v['end_time']?$v['end_time']:'';?>&nbsp;</td>
                            <td class="center"><?php echo $v['discount']?$v['discount']:'';?>&nbsp;</td>
                            <td class="center">
                            <?php 
                                switch ($v['status']) {
                                    case 1:
                                        echo "转让中";
                                        break;
                                    case 10:
                                        echo "转让成功";
                                        break;
                                    default:
                                        echo "转让撤销";
                                        break;
                                }
                            ?>&nbsp;
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
<script>
$(function(){
    $("#submitForm").click(function(){
        var pro_id      = $.trim($("#pro_id").val());
        var creditor_name = $.trim($("#creditor_name").val());
        /*var buyer_uid   = $("#buyer_uid").val();*/
        var time_start   = $.trim($("#time_start").val());
        var time_end     = $.trim($("#time_end").val());
        window.location.href = "/Project/zqzr?pro_id="+pro_id+"&creditor_name="+creditor_name+"&time_start="+time_start+"&time_end="+time_end;
    })
})
</script>