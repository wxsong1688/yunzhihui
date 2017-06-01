/**
 * Created by Administrator on 2015/10/27.
 */
$(function(){
    $(".invest-table tr:even").addClass("even");
    var $temp1 = $("input[name='invest-stateAll']");
    var $temp2 = $("input[name='invest-state']");
    var $temp3 = $("input[name='invest-state']:checked");
    $temp2.each(function () {
        $(this).click(function () {
            if ($("input[name='invest-state']:checked").length == $temp2.length) {
                $temp1.attr("checked", true);     //当元素全部选择后，控制全部的checkbox也处于选定状态
            } else {
                $temp1.attr("checked", false);    //只要有一个没有选择控制全选的checkbox是不会checked的
            }
        })

    });
    $temp1.click(function(){
        $("input[name='invest-state']").attr("checked",$(this).attr("checked"));
    });
    // var $demo = $(".investment-con-section1>ul>li");
    // var $demo1 = $(".investment-con-sec10>ul>li");
    // $demo.each(function(i){
    //     $(this).click(function(){
    //         $(this).addClass("ucaccrent");
    //         $(this).siblings("li").removeClass("ucaccrent");
    //         $demo1.eq(i).show();
    //         $demo1.eq(i).siblings("li").hide();
    //     });
    // })
});