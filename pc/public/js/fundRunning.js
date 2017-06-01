/**
 * Created by Administrator on 2015/9/17.
 */
$(function(){
    //ajaxRequest("runningType=all&timestart=&timeend=");
    $(".fund-table tr:even").addClass("even");

  /*  var $temp1 = $("input[name='fund-stateAll']");
    var $temp2 = $("input[name='fund-state']");
    var $temp3 = $("input[name='fund-state']:checked");*/

    //点击查询
    $("#J_search_btn").click(function(){
        //获取参数
        var runningType = getRunningType();
        var timestart = $("#time_start").val();
        var timeend = $("#time_end").val();
        if(timestart=='' || timeend==''){
            alert('请输入时间范围！');return false;
        }
        //ajaxRequest("runningType="+runningType+"&timestart="+timestart+"&timeend="+timeend);
        $("#J_Search").submit();
    })

    //点击今天
    /*$("span[name='today']").click(function(){
        var datetime = new Date();
        $("#time_start").val(datetime.getFullYear()+"-"+(datetime.getMonth()+1)+"-"+datetime.getDate());
        $("#time_end").val(datetime.getFullYear()+"-"+(datetime.getMonth()+1)+"-"+datetime.getDate());

        //获取参数
        var runningType = getRunningType();
        var timestart = $("#time_start").val();
        var timeend = $("#time_end").val();
        //ajaxRequest("runningType="+runningType+"&timestart="+timestart+"&timeend="+timeend);
    })

    //点击近一个月
    $("span[name='1month']").click(function(){
        var now = new Date();
        var ago = new Date(new Date()-30*24*3600*1000);
        $("#time_start").val(ago.getFullYear()+"-"+(ago.getMonth()+1)+"-"+ago.getDate());
        $("#time_end").val(now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate());

        //获取参数
        var runningType = getRunningType();
        var timestart = $("#time_start").val();
        var timeend = $("#time_end").val();
        //ajaxRequest("runningType="+runningType+"&timestart="+timestart+"&timeend="+timeend);
    })
*/
    //点击近一个月
    /*$("span[name='3month']").click(function(){
        var now = new Date();
        var ago = new Date(new Date()-90*24*3600*1000);
        $("#time_start").val(ago.getFullYear()+"-"+(ago.getMonth()+1)+"-"+ago.getDate());
        $("#time_end").val(now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate());

        //获取参数
        var runningType = getRunningType();
        var timestart = $("#time_start").val();
        var timeend = $("#time_end").val();
        //ajaxRequest("runningType="+runningType+"&timestart="+timestart+"&timeend="+timeend);
    })*/

    /*//点击单个类型
    $temp2.each(function () {
        $(this).click(function () {
            if ($("input[name='fund-state']:checked").length == $temp2.length) {
                $temp1.attr("checked", true);     //当元素全部选择后，控制全部的checkbox也处于选定状态
            } else {
                $temp1.attr("checked", false);    //只要有一个没有选择控制全选的checkbox是不会checked的
            }

            var runningType = getRunningType();
            var timestart = $("#time_start").val();
            var timeend = $("#time_end").val();
            //ajaxRequest("runningType="+runningType+"&timestart="+timestart+"&timeend="+timeend);
        })

    });
    //点击所有
    $temp1.click(function(){
        $("input[name='fund-state']").attr("checked",$(this).attr("checked"));
        var runningType = getRunningType();
        var timestart = $("#time_start").val();
        var timeend = $("#time_end").val();
        //ajaxRequest("runningType="+runningType+"&timestart="+timestart+"&timeend="+timeend);
    });
*/

    //处理数据参数
    function getRunningType()
    {
        var runningType = '';
        if($temp1.attr("checked") == true){
            runningType = 'all';
        }else{
            if($(".recharge").attr("checked") == true){//充值
                runningType += ',recharge';
            }
            if($(".invest").attr("checked") == true){//投资
                runningType += ',invest';
            }
            if($(".reback").attr("checked") == true){//回款
                runningType += ',reback';
            }
            if($(".withdraw").attr("checked") == true){//提现
                runningType += ',withdraw';
            }
        }
        return runningType;
    }

    function ajaxRequest(params)
    {
        $.ajax({
            type: "GET",
            url: "/Usercenter/fundRunningList",
            data: params,
            dataType: "json",
            success: function(data){
                var html = '';
                for(i in data){console.info(data[i]);
                    if(i%2!=0){
                        html += '<tr style="background-color:#efefef;">';
                    }else{
                        html += "<tr>";
                    }
                    
                    html += '<td class="font-style3" >'+data[i].create_time+'</td>';
                    html += '<td class="font-style3" >|</td>';
                    html += '<td class="font-style3" >'+data[i].type+'</td>';
                    html += '<td class="font-style3" >|</td>';
                    html += '<td class="font-style4" >'+data[i].comment+'</td>';
                    html += '<td class="font-style3" >|</td>';
                    if(data[i].ispay == 1){
                        html += '<td class="font-style5" >-</td>';
                        html += '<td class="font-style3" >|</td>';
                        html += '<td class="font-style4" >-'+data[i].amount+'</td>';
                    }else{
                        html += '<td class="font-style5" >+'+data[i].amount+'</td>';
                        html += '<td class="font-style3" >|</td>';
                        html += '<td class="font-style4" >-</td>';
                    }
                    html += '<td class="font-style3" >|</td>';
                    html += '<td class="font-style5" >￥'+data[i].remaining_amount+'</td>';
                    html += "</tr>";
                }
                $(".fund-table tbody").html(html);
            }
        });
    }


});
