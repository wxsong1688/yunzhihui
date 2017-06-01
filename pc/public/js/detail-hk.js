/**
 * Created by Administrator on 2015/11/19.
 */
$(document).ready(function(){
    $(".detail-hk").click(function(){
        var item_id = $(this).attr("data-id");
        $.ajax({
            url: "/usercenter/get_repayment_list",
            dataType: "json",
            async: true,
            data: {"item_id":item_id},
            type: "GET",
            beforeSend: function() {
                //请求前的处理
            },
            success: function(data) {
                if(data.code!=0){
                    alert(data.msg);return;
                }
                var parentFrame = $(window.parent.document);
                var $tableGray = parentFrame.find("#table_gray");
                var parentBodyHeight = parentFrame.find("#bob").height();
                var parentBodyWidth = parentFrame.find("#bob").width();
                var w2 = parentFrame.find(".invest-madal").width();
                var w = (parentBodyWidth-w2)/2/parentBodyWidth*100;
                parentFrame.find(".imsec2-ul-out").show();
                parentFrame.find(".invest-madal").show();
                parentFrame.find(".imsec2-ul-out").height(parentBodyHeight);
                var trHtml = "";
                for(var i in data.res){console.info(i);
                    trHtml += "<tr class='paybacklist_hide'>";
                    trHtml += "<td>"+data.res[i].calcu_end+"</td>";
                    trHtml += "<td>"+data.res[i].repay_principal+"</td>";
                    trHtml += "<td>"+data.res[i].repay_interest+"</td>";
                    trHtml += "<td>0</td>";
                    trHtml += "<td>"+data.res[i].repay_amount+"</td>";
                    if(data.res[i].is_finish == 1){
                        trHtml += "<td class='green'>已回款</td>";
                    }else{
                        trHtml += "<td>未回款</td>";
                    }
                    trHtml += "</tr>"
                }
                $tableGray.find("tbody").html(trHtml);
            },
        });
    });
});
