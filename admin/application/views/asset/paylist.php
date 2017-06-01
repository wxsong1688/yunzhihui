<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">还款管理</h1>
        <span class="pagedesc"></span>
    </div><!--pageheader-->
    <div class="contenttitle2">
        <h3>还款列表</h3>
    </div>
    <table cellpadding="0" cellspacing="0" border="0" id="table1" class="stdtable stdtablecb">
        <thead>
            <tr>
                <th class="head1">还款日期</th>
                <th class="head0">还款金额</th>
                <th class="head1">所属项目编号</th>
            </tr>
        </thead>
        <tbody>
<?php if(!empty($list)){foreach($list as $v){ ?>
            <tr>
                <td class="center"><?php echo isset($v['calcu_end']) ? date("Y-m-d",strtotime($v['calcu_end'])) : "" ;?></td>
                <td class="center"><?php echo isset($v['repay_amount']) ? number_format($v['repay_amount'],2) : "";?>元 <?php if($v['repay_interest'] != $v['repay_amount']){echo "(本金+利息)";}?></td>
                <td class="center"><?php echo isset($pro_info['pro_num']) ? $pro_info['pro_num'] : '' ;?></td>
            </tr>
<?php } } ?>
        </tbody>
    </table>