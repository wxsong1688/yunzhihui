/**
 * Created by Administrator on 2015/10/30.
 */
$(function(){

    $('.zf-pub').each(function(i){
        $(this).click(function(){
            $(this).addClass('zf-pub1');
            $(this).siblings().removeClass('zf-pub1');
            $('.zf-con0'+i).show();
            $('.zf-con0'+i).siblings(".zf-con-pub").hide();

        });
    });

    var temp0 = $(".zf-con-pub");
    temp0.each(function(t){
        var temp = $(this).find("ul>li");
        temp.each(function(m){
            $(this).click(function(){
                $(this).find("label").addClass("label-current");
                $(this).siblings("li").find("label").removeClass("label-current");
                $(this).find(".dg").show();
                $(this).siblings("li").find(".dg").hide();
            });
        })
    })
})