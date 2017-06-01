/**
 * Created by houwenli on 2015/7/13.
 */

$(function(){
    var $imsec = $(".im-sec2-part00");
    $imsec.each(function(i){
        for(var m=0; m<$imsec.length;m++){
            var $outBar = $imsec.eq(m).find(".out-bar");
            var $bar00 = $outBar.find(".bar-00");
            var barNum = $.trim($outBar.siblings("label").text());
            $bar00.css("width",barNum);
        }
        $(this).hover(function(){
            var $outBar = $(this).find(".out-bar");
            var barNum = $.trim($outBar.siblings("label").text());
            $outBar.css("border","1px solid #BB1817");
            $outBar.siblings("label").css("color","#BB1817");
            $outBar.find(".bar-0"+i+"-slider").animate({"width":barNum},500);
        },function(){
            var $outBar = $(this).find(".out-bar");
            $outBar.css("border","1px solid #3399FF");
            $outBar.siblings("label").css("color","#3399FF");
            $outBar.find(".bar-0"+i+"-slider").animate({"width":"0"},500);
        });
    });



});
