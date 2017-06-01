/**
 * Created by Administrator on 2015/10/22.
 */

$(function(){
    var $withdrawWays = $(".withdraw-ways>li");
    $withdrawWays.each(function(){
        $(this).click(function(){       
            $(this).addClass("withdraw-current");
            $(this).siblings("li").removeClass("withdraw-current");
            $(this).find("span").show();
            $(this).siblings("li").find("span").hide();
            $("#cashtype").val('');
            $("#cashtype").val($(this).attr("id"));
            var rate = 0.00;
            var withdrawalsMoney = $("#withdrawalsMoney").val();
            var outMoneytype = $(".withdraw-current").attr("id");
            if( withdrawalsMoney > 0 ){
                if( outMoneytype=="GENERAL" ){
                    rate = 2;
                    $("#rate").html('');
                    $("#rate").html(rate.toFixed(2));
                }else{
                    rate = withdrawalsMoney*0.0005+2;
                    $("#rate").html('');
                    $("#rate").html(rate.toFixed(2));
                }
            }else{
                $("#rate").html('');
                $("#rate").html('0.00');
            }
            
        })
    });
    var $ban = $(".bank-ka>ul>li").not(":last");
    $ban.each(function(){
        $(this).click(function(){            
            $(this).find("span.select-curr").show();
            $(this).siblings("li").find("span.select-curr").hide();
        });
    })
});