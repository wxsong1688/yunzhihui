<!--
　日期   操作类型   收支描述  收入  支出   账户余额   备注
-->
<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">投资人资产管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>资金流水列表</h3>
            </div><!--contenttitle-->
            <div class="tableoptions tableoptions_new"> 
            <input type="hidden" value="<?php echo $search_data['uid'];?>" id="uid">
                时间：
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
                            <th class="head1">日期</th>
                            <th class="head1">操作类型</th>
                            <th class="head0">收支描述</th>
                            <th class="head0">收入/支出</th>
                            <th class="head1">账户余额</th>
                            <th class="head0">备注</th>
                        </tr>
                    </thead>        
                    <tbody>
                        <?php foreach($list as $v):?>
                        <tr>
                            <td class="center"><?php echo $v['create_time'];?></td>
                            <td class="center">
                            <?php 
                                switch ($v['type']) {
                                    case 1:
                                        echo "充值";
                                        break;
                                    case 2:
                                        echo "提现";
                                        break;
                                    case 3:
                                        echo "充值手续费";
                                        break;
                                    case 4:
                                        echo "提现手续费";
                                        break;
                                    case 5:
                                        echo "投标冻结";
                                        break;
                                    case 6:
                                        echo "投标";
                                        break;
                                    case 7:
                                        echo "回款利息";
                                        break;
                                    case 8:
                                        echo "回款本息";
                                        break;
                                    case 9:
                                        echo "债权出让";
                                        break;
                                     case 9:
                                        echo "购买债权";
                                        break;
                                    default:
                                        echo "";
                                        break;
                                }
                            ?>
                            &nbsp;</td>
                            <td class="center"><?php echo $v['type_desc'];?>&nbsp;</td>
                            <td class="center"><?php if(in_array($v['type'],array(1,7,8,9))):?>+<?php else:?>-<?php endif;?><?php echo $v['amount'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['balance'];?>&nbsp;</td>
                            <td class="center"><?php echo $v['comment'];?>&nbsp;</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
<script>
$(function(){
    $("#submitForm").click(function(){
        var uid          = $.trim($("#uid").val());
        var time_start   = $.trim($("#time_start").val());
        var time_end     = $.trim($("#time_end").val());
        //时间判断：年份不能大于当年时间，时间必须都在一年内
        var date = new Date().getFullYear();
        var minDate = new Date(time_start).getFullYear();
        var maxDate = new Date(time_end).getFullYear();

        if(minDate > date || maxDate > date){
            alert("时间范围不能超过当前年份");
            return false;
        }

        if((time_start != "" && time_end !="") && (minDate != maxDate))
        {
            alert("时间范围必须在同一年内");
            return false;
        }

        if(minDate < 2015 || maxDate < 2015){
            alert("时间范围必须在2015年以后");
            return false;
        }

        window.location.href = "/Asset/investFlow?uid="+uid+"&time_start="+time_start+"&time_end="+time_end;
    })
})
</script>