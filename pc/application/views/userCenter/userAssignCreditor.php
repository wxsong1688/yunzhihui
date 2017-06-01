<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>我的投资</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <!-- <link rel="stylesheet" type="text/css" href="/public/css/jspage.css"></link> -->
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userAssignCreditor.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/newPage.css"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/userAssignCreditor.js"></script>
    <script type="text/javascript" src="/public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/jquery.page.js"></script>
	
    <script type="text/javascript">
        $(function(){
            $(".invest-table tr:even").addClass("even");

            $(".cancelCredit").click(function(){
                // parent.modalNew('bob',"撤销转让",'确认撤销转让该项目？','','','','','确定','取消','/Usercenter',"");
                // parent.document.getElementById("sure_span").style.display="none";
                var credit_id = $(this).attr("data-id");
                if(confirm("确认撤销转让该项目？")){
                    $.ajax({
                        type: 'POST',
                        url: "/Projectcredit/revokeCredit",
                        data: {'credit_id':credit_id},
                        dataType: 'json',
                        success: function(data){
                            if(data.code==0){
                                alert("该转让已撤销成功！");
                            }else{
                                alert("该转让撤销失败！");
                            }
                        },
                    });
                    parent.window.location = "/Usercenter";
                }else{
                    return;
                }
            });
        })
    </script>
</head>
<body>
<div class="userAssignCreditor user-all" id="userAssignCreditor">
    <div class="user-section user-right-height" id="userAssignCreditor-section">
        <p class="userAssignCreditor-title usercenter-title">债权转让</p>
        <div class="userAssignCreditor-content">
            <div class="userCreditoe-con-section1">
                <ul class="float-left">
                    <li class="float-left text-center ucaccrent">可转项目</li>
                    <li class="float-left text-center">可转债权</li>
                    <li class="float-left text-center">转让中</li>
                    <li class="float-left text-center">已转让</li>
                </ul>

                <div class="clear"></div>
            </div>
            <div class="userCreditor-con-sec10">
                <ul class="float-left">
                    <li>
                        <table border="0" class="userCreditor-table">
                            <thead>
                                <tr class="even">
                                    <th width="110">项目编号</th>
                                    <th width="32"></th>
                                    <th width="98">投标金额</th>
                                    <th width="32"></th>
                                    <th width="80">年化收益</th>
                                    <th width="32"></th>
                                    <th width="80">项目周期</th>
                                    <th width="32"></th>
                                    <th width="80">剩余时间</th>
                                    <th width="32"></th>
                                    <th width="100">转让折价率</th>
                                    <th width="32"></th>
                                    <th width="125">操作</th>
                                </tr>
                            </thead>
                            <tbody id="pageContent-tbody1">
                            <?php foreach ($creditp as $k => $v): ?>
                                <tr <?php if($k%2==1){echo 'class="even"';}?>>
                                    <td class="font-style3"><?php echo isset($v['pro_id']) ? $v['pro_num'] : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style3"><?php echo isset($v['pro_id']) ? number_format($v['invest_sum']) : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['pro_id']) ? round($v['year_rate_out'],2) : "" ;?>%</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo isset($v['pro_id']) ? $v['cycle']*30 : "" ;?>天</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['pro_id']) ? $v['remain_date'] : "" ;?>天</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo $v['pro_status']==10 ? round($v['discountRatio']*100,2)."%" : "--" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td>
                                        <a href="/Projectcredit/assignCreditorCon?mpid=<?php echo $v['item_id']?>" target="_blank" class="font-style4">转让该项目</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="tcdPageCode" id="tcdPageCode1"></div>
                    </li>

                    <li class="display-none">
                        <table border="0" class="userCreditor-table">
                            <thead>
                            <tr class="even">
                                <th width="110">项目编号</th>
                                <th width="32"></th>
                                <th width="98">投标金额</th>
                                <th width="32"></th>
                                <th width="80">年化收益</th>
                                <th width="32"></th>
                                <th width="80">项目周期</th>
                                <th width="32"></th>
                                <th width="80">剩余时间</th>
                                <th width="32"></th>
                                <th width="100">转让折价率</th>
                                <th width="32"></th>
                                <th width="125">操作</th>
                            </tr>
                            </thead>
                            <tbody id="pageContent-tbody2">
                            <?php foreach ($credit as $k => $v): ?>
                                <tr <?php if($k%2==1){echo 'class="even"';}?>>
                                    <td class="font-style3"><?php echo isset($v['pro_id']) ? $v['pro_num'] : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style3"><?php echo isset($v['pro_id']) ? number_format($v['credit_amount']) : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['pro_id']) ? round($v['year_rate_out'],2) : "" ;?>%</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo isset($v['pro_id']) ? $v['cycle']*30 : "" ;?>天</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['pro_id']) ? $v['remain_date'] : "" ;?>天</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo $v['pro_status']==10 ? round($v['discountRatio']*100,2)."%" : "--" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td>
                                        <a href="/Projectcredit/assignCreditorCon?mpid=<?php echo $v['item_id'];?>&credit_id=<?php echo $v['credit_id']?>" target="_blank" class="font-style4">债权转让</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="tcdPageCode" id="tcdPageCode2"></div>
                    </li>
                    <li class="display-none">
                        <table border="0" class="userCreditor-table">
                            <thead>
                            <tr class="even">
                                <th width="110">项目编号</th>
                                <th width="32"></th>
                                <th width="98">投标金额</th>
                                <th width="32"></th>
                                <th width="80">年化收益</th>
                                <th width="32"></th>
                                <th width="80">项目周期</th>
                                <th width="32"></th>
                                <th width="80">剩余时间</th>
                                <th width="32"></th>
                                <th width="100">转让折价率</th>
                                <th width="32"></th>
                                <th width="125">操作</th>
                            </tr>
                            </thead>
                            <tbody id="pageContent-tbody3">
                            <?php foreach ($crediting as $k => $v): ?>
                                <tr <?php if($k%2==1){echo 'class="even"';}?>>
                                    <td class="font-style3"><?php echo isset($v['pro_num']) ? $v['pro_num'] : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style3"><?php echo isset($v['invest_sum']) ? number_format($v['invest_sum']) : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['year_rate_out']) ? round($v['year_rate_out'],2) : "" ;?>%</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo isset($v['cycle']) ? $v['cycle']*30 : "" ;?>天</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['remain_date']) ? $v['remain_date'] : "" ;?>天</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo $v['pro_status']==10 ? round($v['discountRatio']*100,2)."%" : "--" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td>
                                        <a href="javascript:void(0);" data-id="<?php echo $v['credit_id']?>" class="cancelCredit font-style4">撤销转让</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="tcdPageCode" id="tcdPageCode3"></div>
                    </li>
                    <li class="display-none">
                        <table border="0" class="userCreditor-table">
                            <thead>
                            <tr class="even">
                                <th width="110">项目编号</th>
                                <th width="32"></th>
                                <th width="98">投标金额</th>
                                <th width="32"></th>
                                <th width="80">年化收益</th>
                                <th width="32"></th>
                                <th width="80">项目周期</th>
                                <th width="32"></th>
                                <th width="80">转让折价率</th>
                                <th width="32"></th>
                                <th width="100">成交日期</th>
                                <th width="32"></th>
                                <th width="125">债权接收人</th>
                            </tr>
                            </thead>
                            <tbody id="pageContent-tbody4">
                            <?php foreach ($credited as $k => $v): ?>
                                <tr <?php if($k%2==1){echo 'class="even"';}?>>
                                    <td class="font-style3"><?php echo isset($v['pro_id']) ? $v['pro_num'] : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style3"><?php echo isset($v['pro_id']) ? number_format($v['invest_sum']) : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['pro_id']) ? round($v['year_rate_out'],2) : "" ;?>%</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo isset($v['pro_id']) ? $v['cycle']*30 : "" ;?>天</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style5"><?php echo isset($v['pro_id']) ? round($v['discountRatio']*100,2)."%" : "--" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4"><?php echo isset($v['pro_id']) ? date("Y-m-d",strtotime($v['deal_time'])) : "" ;?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style3"><?php echo $v['buyer_uname']?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="tcdPageCode" id="tcdPageCode4"></div>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">  
change_height();
$(function(){
    $("#tcdPageCode1").createPage({
        current:1,
        pageContentId: "pageContent-tbody1",
        showCount: 6,
        backFn:function(p){
            console.log(p);
        }
    });
});

$(function(){
    $("#tcdPageCode2").createPage({
        current:1,
        pageContentId: "pageContent-tbody2",
        showCount: 6,
        backFn:function(p){
            console.log(p);
        }
    });
});

$(function(){
    $("#tcdPageCode3").createPage({
        current:1,
        pageContentId: "pageContent-tbody3",
        showCount: 6,
        backFn:function(p){
            console.log(p);
        }
    });
});

$(function(){
    $("#tcdPageCode4").createPage({
        current:1,
        pageContentId: "pageContent-tbody4",
        showCount: 6,
        backFn:function(p){
            console.log(p);
        }
    });
});
</script> 
</body>
</html>
