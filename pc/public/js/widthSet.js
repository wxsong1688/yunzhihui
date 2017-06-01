/**
 * Created by Administrator on 2016/3/7.
 */
$(function(){
   fun();
   $(window).resize(function(){
      fun();
   });
   function fun(){
      resizeWidth($(".header"));
      resizeWidth($(".bid-middle"));
      resizeWidth($(".banner-all"));
      resizeWidth($(".banner-bottom"));
      resizeWidth($(".index-middle"));
      resizeWidth($(".userMain"));
      resizeWidth($(".footer"));
  
   }
   function resizeWidth(th){
      var w1 = $(window).width();
      if(w1<1200) {
         th.width("1200");
      } else {
         th.width(w1);
      }

   }
});