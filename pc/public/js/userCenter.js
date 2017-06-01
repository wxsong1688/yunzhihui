/**
 * Created by Administrator on 2016/3/7.
 */
function change_height(){
  var iframe=$('#rightFrame', parent.document);
  
  var iframe1=$('#leftFrame', parent.document);
  iframe1.height("693");
  //取得框架元素
  if($("body").height()> 693) {
	  iframe.height($("body").height());
  } else {
	  iframe.height("693");
	  $("body").height("693");
  }
  
}