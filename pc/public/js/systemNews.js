/**
 * Created by Administrator on 2015/9/21.
 */
$(function(){
    $('.d-list1').each(function(t){
        $(this).find('.tm4').click(function(){
            var tm4 = $(this);
            var $demo = $('.d-content-li');
            var bac = tm4.find("img").attr("src").split("-");
            var bac1 = bac[bac.length-1].split(".");
            if(bac1[0] == "jt"){

                $demo.eq(t).siblings("li").find('.d-detail').hide();
                $demo.eq(t).siblings().find('.tm4').find("img").attr('src',bac[0]+'-jt.gif');
                $demo.eq(t).find('.d-detail').show();
                tm4.find("img").attr('src',bac[0]+'-jt1.gif');
                var id = tm4.find("img").attr("id");
                if(tm4.attr('ifread') == 0){
                    $.ajax({
                        type: "POST",
                        url: "/Usercenter/systemNewsup",
                        data: {id:id},
                        dataType: "json",
                        success: function(data){
                                if(data==1){
                                    $("#stat_"+id).removeClass("tm3");
                                    $("#stat_"+id).html("已读");
                                    tm4.attr('ifread',1);
                                    //更新上一个iframe的未读数
                                }
                            }
                    });
                }
            } else {
                tm4.find("img").attr('src',bac[0]+'-jt.gif');
                $demo.eq(t).siblings().find('.tm4').find("img").attr('src',bac[0]+'-jt.gif');
                $demo.eq(t).find('.d-detail').hide();
            }
        })
    })
});