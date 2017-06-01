/**
 * Created by Administrator on 2015/9/9.
 */
$(function(){
    var proid = $("#pid").val();
    $(".confirm-tz").click(function(){
        if ( !isNaN($("#invest_sum").val()) && $("#invest_sum").val().trim().length != 0 ) {
            $.ajaxSetup({ async : false });  
            $.post('/project/checkInvest', {buid:$('#buid').val(),remain_amount:$('#remain_amount').val(),pid:$('#pid').val(),ptype:$('#ptype').val(),invest_sum:$('#invest_sum').val()}, function(msg) {
                var obj = eval('('+msg+')');
                if(obj.code==1){
                    modalNew('bob',"投资",'请在新打开的汇付页面进行投资操作','','','','','已完成','','/FinanciaTransactions','');
                    $(".register-success span").hide();
                    $("#bid").submit();
                }else{
                    //alert(jQuery.trim(unescape(obj.msg)));
                    $(".errinfo").html('');
                    modalNew('bob',"投资",obj.msg,'','','','','确认','','/Project/bid?pro_id='+proid,'');
                    return;
                    //$(".errinfo").html(obj.msg);
                }
            });            
        }else{
            $(".errinfo").html("请正确填写投资金额！");
            $("#invest_sum").val('');
            $("#invest_sum").focus();
            return;
        }
        
    });

    for(var i =0; i< $(".assign-title").length; i++){
        changeWidth(i);
    }
    function changeWidth(i){
        var width1 = $(".assign-title").eq(i).width();
        var width2 = $(".pro-detail").eq(i).width();
        $(".blue-line").eq(i).width(width1-width2-10);
    }
});