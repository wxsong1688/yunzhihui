<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>我的账户</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/page.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/userCenter.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/datepicker.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/fundRunning.css"></link>
    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/fundRunning.js"></script>
    <script type="text/javascript" src="/public/js/datePicker/WdatePicker.js"></script>
	
</head>
<body >
<div class="fundRunning user-all" id="fundRunning">
    <div class="user-section user-right-height" id="fundRunning-section">
        <p class="fundRunning-title usercenter-title">资金流水</p>
        <div class="fundRunning-content">
            <div class="fundRunning-content1">
                <div class="fund-section-part1">
                    <label class="findTime font-style1 float-left">查询时间:</label>
                    <div class="float-left dateCanlendar" id="search">
                        <form id="J_Search" action="/Usercenter/fundRunning" method="get">
                            <ul class="float-left">
                                <li class="float-left" style="margin-top:6px;">
                                    <input onclick="WdatePicker()" name="time_start" id="time_start" class="Wdate" value="<?php echo isset($searchData['time_start'])?$searchData['time_start']:'';?>">
                                </li>
                                <li class="float-left" style="margin-top:8px;">&nbsp;-&nbsp;</li>
                                <li class="float-left" style="margin-top:6px;">
                                    <input onclick="WdatePicker()" name="time_end" id="time_end" class="Wdate" value="<?php echo isset($searchData['time_end'])?$searchData['time_end']:'';?>">
                                </li>
                                <li class="float-left J_searchb"><input id="J_search_btn" type="submit" class="f-btn" value="查询" /></li>
                            </ul>
							<div class="float-left fundr-lef"><!-- color-differ -->
                            <input type="hidden" id="timedate" value="<?php echo isset($searchData['date'])?$searchData['date']:''; ?>"/>
								<span class="inline-block font-style1 singleTime gototime <?php if(isset($searchData['date']) && $searchData['date']==1){echo "color-differ";}?>" tag="1">今天</span>
								<span class="inline-block font-style1 singleTime gototime <?php if(isset($searchData['date']) && $searchData['date']==2){echo "color-differ";}?>" tag="2">近一个月</span>
								<span class="inline-block font-style1 singleTime gototime <?php if(isset($searchData['date']) && $searchData['date']==3){echo "color-differ";}?>" tag="3">近三个月</span>
                            </div>
							<div class="clear"></div>
                        </form>
                        <input type="hidden" id="runningType" value="<?php echo isset($searchData['runningType'])?$searchData['runningType']:''; ?>"/>
                        <script type="text/javascript">
                            $(".gototime").click(function(){
                                var gtag = $(this).attr('tag');
                                var runningType = $("#runningType").val();
                                if(runningType != ''){
                                    window.location.href="/Usercenter/fundRunning?date="+gtag+"&runningType="+runningType;
                                }else{
                                    window.location.href="/Usercenter/fundRunning?date="+gtag;
                                }
                                
                            })
                        </script>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <div class="fund-section-part2">
                <label class="font-style2 float-left">查询类型：</label>
                <?php 
                    $runningTypes = array();
                    if(isset($searchData['runningType']) && !empty($searchData['runningType'])){
                        $runningTypes = explode(",", $searchData['runningType']);
                    }
                ?>
                <ul class="float-left">
                    <li>
                        <input type="checkbox" name="fund-stateAll" class="gototypeAll" <?php if(!isset($searchData['runningType']) || count($runningTypes)==4):?>checked<?php endif;?>>
                        <span>所有数据</span>
                    </li>
                    <li>
                        <input type="checkbox" name="fund-state" class="gototype" tag="recharge" <?php if(in_array('recharge',$runningTypes) || !isset($searchData['runningType'])):?>checked<?php endif;?>>
                        <span>充值</span>
                    </li>
                    <li>
                        <input type="checkbox" name="fund-state" class="gototype" tag="invest" <?php if(in_array('invest',$runningTypes) || !isset($searchData['runningType'])):?>checked<?php endif;?>>
                        <span>投资</span>
                    </li>
                    <li>
                        <input type="checkbox" name="fund-state" class="gototype" tag="reback" <?php if(in_array('reback',$runningTypes) || !isset($searchData['runningType'])):?>checked<?php endif;?>>
                        <span>回款</span>
                    </li>
                    <li>
                        <input type="checkbox" name="fund-state" class="gototype" tag="withdraw" <?php if(in_array('withdraw',$runningTypes) || !isset($searchData['runningType'])):?>checked<?php endif;?>>
                        <span>提现</span>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
             <script type="text/javascript">
                $(".gototypeAll").click(function(){
                    $("input[name='fund-state']").attr("checked",$(this).attr("checked"));
                    if($(this).attr("checked") == true){
                        var typestr = "recharge,invest,reback,withdraw";
                    }else{
                        var typestr = "";
                    }
                    var searchstr = getSearchStr();
                    window.location.href = "/Usercenter/fundRunning?runningType="+typestr+searchstr;
                })

                function getSearchStr()
                {
                    var search_str = '';
                    var time_start = $("#time_start").val();
                    if(time_start!=''){
                          search_str += '&time_start='+time_start;  
                    }
                    var time_end = $("#time_end").val();
                    if(time_end!=''){
                          search_str += '&time_end='+time_end;  
                    }
                    var timedate = $("#timedate").val();
                    if(timedate!=''){
                          search_str += '&date='+timedate;  
                    }
                    return search_str;
                }

                $(".gototype").click(function(){
                    var types = [];
                    $(".gototype").each(function(){
                        if($(this).attr('checked') == true){
                            var tag = $(this).attr('tag');
                            types.push(tag);
                        }
                    })
                    if(types.length>=4){
                        $("input[name='fund-stateAll']").attr("checked","checked");
                    }else{
                        $("input[name='fund-stateAll']").attr("checked","");
                    }

                    //是否执行搜索事件
                    if(types.length>=1){
                        var typestr = types.join(",");
                    }else{
                        var typestr = "";
                    }
                    var searchstr = getSearchStr();
                    window.location.href = "/Usercenter/fundRunning?runningType="+typestr+searchstr;
                })
                
            </script>
            <div class="fund-section-part3">
                <table class="fund-table" border="0">
                    <thead>
                        <tr>
                            <th width="124">时间</th>
                            <th width="32"></th>
                            <th width="62">操作类型</th>
                            <th width="32"></th>
                            <th width="165">操作描述</th>
                            <th width="32"></th>
                            <th width="60">收入</th>
                            <th width="32"></th>
                            <th width="60">支出</th>
                            <th width="32"></th>
                            <th width="98">账户余额</th>
                        </tr>
                        <?php foreach($data as $k => $v){ ?>
                            <tr style="<?php if($k%2==1){echo 'background-color:#efefef;';}?>">
                                <td class="font-style3"><?php echo $v['create_time'];?></td>
                                <td class="font-style3">|</td>
                                <td class="font-style3"><?php echo $v['type'];?></td>
                                <td class="font-style3">|</td>
                                <td class="font-style4"><?php echo $v['comment'];?></td>
                                <td class="font-style3">|</td>
                                <?php if($v['ispay']==1){ ?>
                                    <td class="font-style5">-</td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4">-<?php echo $v['amount'];?></td>
                                <?php }else{ ?>
                                    <td class="font-style5">+<?php echo $v['amount'];?></td>
                                    <td class="font-style3">|</td>
                                    <td class="font-style4">-</td>
                                <?php } ?>
                                <td class="font-style3">|</td>
                                <td class="font-style5">￥<?php echo $v['remaining_amount'];?></td>
                            </tr>
                        <?php } ?>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
            <div class="list-pagination">
                <div class="y-pagination">
                    <?php echo $this->pageclass->show(1); ?>
                    <span class="p-elem p-item-go">第<input class="p-ipt-go" id="p-ipt-go" onkeyup="this.value=this.value.replace(/\D/g,'')" value="<?php echo isset($searchData['pg'])?$searchData['pg']:'';?>" onafterpaste="this.value=this.value.replace(/\D/g,'')"  >页<a href="javascript:void(0);" class="p-btn-go" id="p-btn-go">GO</a></span>
                </div>
            </div>
    </div>
</div>
<script type="text/javascript" src="/public/js/userCenter.js"></script>
<script type="text/javascript">  
	change_height();
    $("#p-btn-go").click(function(){
        var page = parseInt($("#p-ipt-go").val());
        if(isNaN(page) || page<=0){
            alert("请输入正确页数");return;
        }
        window.location="/Usercenter/fundRunning?pg="+page;
    });
</script>

</body>
</html>