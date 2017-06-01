/**
 * Created by houwenli on 2015/7/7.
 */

$().ready(function(e) {
    $("#wel_one").animate({left:"146px"},1400,"swing");
    $("#wel_two").animate({left:"146px"},1600,"swing");
    $("#wel_three").animate({left:"146px"},1200,"swing");
    $("#wel_four").animate({left:"146px"},1000,"swing");
    var url = window.location.href;//获取当前URL
    if(url.indexOf("?") > 0 ) {
        var cutUrl = url.split("?");//用"?"将URL分割成2部分
        var par =  cutUrl[1];
        var idParCut = par.split("=");
        var tid = idParCut[1];
        var pageid =tid-1;
        if(pageid == 1){
            $("#kf_one").animate({left:"170px"},1400,"swing");
            $("#kf_two").animate({left:"170px"},1800,"swing");
            $("#kf_three").animate({left:"170px"},1200,"swing");
            $("#kf_four").animate({left:"170px"},1000,"swing");
        }
        if(pageid == 2){
            $("#dz_one").animate({left:"210px"},1400,"swing");
            $("#dz_two").animate({left:"210px"},1200,"swing");
            $("#dz_three").animate({left:"210px"},1000,"swing");
            $("#kf_four").animate({left:"210px"},1000,"swing");
        }
    }

    $f.create($f.slide,{parent:'.banner_container',target:'.banner_content',nav:'.banner_nav li',start:pageid,easing:'easeInOutExpo',duration:1000,time:20000,auto:true,dir:0,current:'current'});
    var pos = {
        0:146,1:146,2:146,3:890

    },offset = 600,time_one=1400,time_two=1800,time_three=1200,time_four=1000;
    $('.banner_content').each(function(index, element) {

        $(this).bind('slideInPos',function(){

            $(this).find('.banner_link_one').stop().css({left:pos[index]-offset}).animate({left:pos[index]},time_one,'swing');
            $(this).find('.banner_link_two').stop().css({left:pos[index]-offset}).animate({left:pos[index]},time_two,'swing');
            $(this).find('.banner_link_three').stop().css({left:pos[index]-offset}).animate({left:pos[index]},time_three,'swing');
            $(this).find('.banner_link_four').stop().css({left:pos[index]-offset}).animate({left:pos[index]},time_four,'swing');

        }).bind('slideInNeg',function(){

            $(this).find('.banner_link_one').stop().css({left:pos[index]+offset}).animate({left:pos[index]},time_one,'swing');
            $(this).find('.banner_link_two').stop().css({left:pos[index]+offset}).animate({left:pos[index]},time_two,'swing');
            $(this).find('.banner_link_three').stop().css({left:pos[index]+offset}).animate({left:pos[index]},time_three,'swing');
            $(this).find('.banner_link_four').stop().css({left:pos[index]+offset}).animate({left:pos[index]},time_four,'swing');

        }).bind('slideOutPos',function(){

            $(this).find('.banner_link_one').stop().css({left:pos[index]}).animate({left:pos[index]+offset},time_one,'swing');
            $(this).find('.banner_link_two').stop().css({left:pos[index]}).animate({left:pos[index]+offset},time_two,'swing');
            $(this).find('.banner_link_three').stop().css({left:pos[index]}).animate({left:pos[index]+offset},time_three,'swing');
            $(this).find('.banner_link_four').stop().css({left:pos[index]}).animate({left:pos[index]+offset},time_four,'swing');

        }).bind('slideOutNeg',function(){

            $(this).find('.banner_link_one').stop().css({left:pos[index]}).animate({left:pos[index]-offset},time_one,'swing');
            $(this).find('.banner_link_two').stop().css({left:pos[index]}).animate({left:pos[index]-offset},time_two,'swing');
            $(this).find('.banner_link_three').stop().css({left:pos[index]}).animate({left:pos[index]-offset},time_three,'swing');
            $(this).find('.banner_link_four').stop().css({left:pos[index]}).animate({left:pos[index]-offset},time_four,'swing');

        });

    });

});
function mouseIn(id){
    $(id).children().stop(true,true).animate({opacity:"1"},400,"easeInOutBounce")
}
function mouseOut(id){
    $(id).children().stop(true,true).animate({opacity:"0"},400,"easeInOutBounce")
}

$(".prove_index").click(function(){
    alert("123");
    $(".banner_nav li").eq($(".banner_nav li.current").index()-1).children("a").click();
});

$(".next_index").click(function(){
    $(".banner_nav li").eq($(".banner_nav li.current").index()+1).children("a").click();
    if($(".banner_nav li.current").index()==1){
        //alert('123');
        $(".banner_nav li").eq(0).children("a").click();
    };
});

$(".banner_container").mouseover(function(){
    $(".prove_index,.next_index").show()
});
$(".banner_container").mouseout(function(){
    $(".prove_index,.next_index").hide()
});

$(document).ready(function(e) {
    var url = window.location.href //获取当前URL
    if(url.indexOf("?") > 0 ) {
        var cutUrl = url.split("?");//用"?"将URL分割成2部分
        var par =  cutUrl[1];
        var idParCut = par.split("=");
        var tid = idParCut[1];
        if(tid==2){$(".banner_nav li").eq(1).children("a").click()}
        if(tid==3){$(".banner_nav li").eq(2).children("a").click()}
        if(tid==4){$(".banner_nav li").eq(3).children("a").click()}
        if(tid==5){$(".banner_nav li").eq(4).children("a").click()}
        if(tid==6){$(".banner_nav li").eq(5).children("a").click()}
        if(tid==7){$(".banner_nav li").eq(6).children("a").click()}
        else{return false}
    }
    else{
        return false;
    }
});

$(function(){
    $(".bb-part1-left").each(function(index){
        $(this).find("img").rotate({
            bind: {
                mouseover: function () {
                    $(this).rotate({
                        duration: 1000,
                        angle: 0,
                        animateTo: 360
                    })
                }
            }
        });
    });
    var $temp = $(".password>input");
    $temp.focus(function(){
        $(".password>label").hide();
    });
    $temp.blur(function(){
        if($temp.val()){
            $(".password>label").hide();
        } else {
            $(".password>label").show();
        }
    });
});