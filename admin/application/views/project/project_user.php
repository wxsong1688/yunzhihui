<div class="centercontent">
    <div class="pageheader">
        <h1 class="pagetitle">项目管理</h1>
        <span class="pagedesc"></span>
        </div><!--pageheader-->
        <div class="contenttitle2">
            <h3>投资列表</h3>
        </div><!--contenttitle-->
        <div class="tableoptions tableoptions_new"> 
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
                        <th class="head1">投标时间</th>
                        <th class="head0">项目名称</th>
                        <th class="head1">昵称</th>
                        <th class="head0">手机号</th>
                        <th class="head1">是否债权转出 / 转入</th>
                        <th class="head0">投资金额</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $v):?>
                    <tr>
                        <td class="center"><?php echo $v['create_time'];?></td>
                        <td class="center"><?php echo $v['pro_name'];?></td>
                        <td class="center"><?php echo $usernames[$v['uid']]['username'];?></td>
                        <td class="center"><?php echo $usernames[$v['uid']]['phone'];?></td>
                        <td class="center">
                            <?php if($v['credit_status']==0):?>
                                否
                            <?php else:?>
                                <?php if($v['credit_status'] == 1 || $v['credit_status'] == 10):?>
                                    转出
                                <?php else:?>
                                    转入
                                <?php endif;?>
                            <?php endif;?>
                        </td>
                        <td class="center"><?php echo $v['invest_sum'];?></td>
                    </tr> 
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>