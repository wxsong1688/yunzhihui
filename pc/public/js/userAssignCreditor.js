/**
 * Created by Administrator on 2015/9/24.
 */
$(function(){
    var $demo = $(".userCreditoe-con-section1>ul>li");
    var $demo1 = $(".userCreditor-con-sec10>ul>li");
    $demo.each(function(i){
        $(this).click(function(){
            $(this).addClass("ucaccrent");
            $(this).siblings("li").removeClass("ucaccrent");
            $demo1.eq(i).show();
            $demo1.eq(i).siblings("li").hide();
        });

    })

});