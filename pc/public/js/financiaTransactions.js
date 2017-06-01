/**
 * Created by Administrator on 2015/9/8.
 */
$(function(){
	var pro = $("input[name='projectNavId']").val();
    var temp = $("#"+pro);
    var $highEndSection2 = $(".highEndSection2>ul>li");
    var $highEndSection1 = $(".highEndSection1>ul>li");
    changeWidth(0,2);
    /*
    proData(temp);
    $highEndSection1.each(function(m){
        $(this).click(function(){
            proData($(this));
        });
    })
    function proData(pro){
        pro.addClass("high-end-current");
        pro.siblings("li").removeClass("high-end-current");
        var i = pro.index();
        $.ajax({
            type: "GET",
            // async: false,
            url: "/usercenter/setcookie_finNav",
            data: {projectNavId:pro.attr("id")},
            dataType: "json",
            success: function(data){
                    alert(data);return;
                }
        });
        $highEndSection2.eq(i).show();
        $highEndSection2.eq(i).siblings("li").hide();
        if(i>0){
            changeWidth(i,2);
        }
    }*/
    var $projectKindLi = $(".projectKind>ul>li");
    $projectKindLi.each(function(t){
        $(this).click(function(){
            var $imgSrc = $(this).find("img").attr("src").split("-");
            if($imgSrc[1]=="01.png"){
                $(this).find("img").attr("src",$imgSrc[0]+"-00"+".png");
                //$(this).siblings("li").find("img").attr("src",$imgSrc[0]+"-01"+".png");
            } else {
                $(this).find("img").attr("src",$imgSrc[0]+"-01"+".png");
            }

        });
    });
    
    function changeWidth(i,m){
        var highWidth = $highEndSection2.eq(i).find(".high-part4-sec1").width();
        var bwidth = $highEndSection2.eq(i).find(".hps-title").width();
        $highEndSection2.eq(i).find(".high-line").width(highWidth-bwidth-m);
        var highWidth1 = $highEndSection2.eq(i).find(".high-part4-sec3").width();
        var bwidth1 = $highEndSection2.eq(i).find(".hps-title1").width();
        $highEndSection2.eq(i).find(".high-line1").width(highWidth1-bwidth1-2);
    }

});
