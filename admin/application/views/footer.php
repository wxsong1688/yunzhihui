</div>
</body>
<script type="text/javascript">
	function loadLlq()
	{
	    if(!+[1,]){
	        alert("请使用其他浏览器,该后台不支持ie");return;
	    }
	}
	loadLlq();
	$(function(){
		

		$(".paginate_button").click(function(){
            var curl = window.location.href;
            var page = $(this).attr('gotopage');
            if(curl.indexOf('page') == -1)
            {
                if(curl.indexOf('?') == -1){
                    window.location.href = curl+"?page="+page;
                }else{
                    window.location.href = curl+"&page="+page;
                }
            }else{
                var re = eval('/(page=)([^&]*)/gi');
                var nurl = curl.replace(re,"page"+'='+page);
                window.location.href = nurl;
            }
        })
	})

function checkRequired (item,isnum)
{
    var data = isnum == 1 ? 0 : '';
    if(item.val() == data)
    {
        item.parent().next().show();
        return 0;
    }
    return 1;
}

function tranValue(id)
{
    var val = $("#"+id).val();
    nval = tran(val);
    $("#"+id).val(nval);
}

function tran(str)
{
  var v, j, sj, rv = "";

  v = str.replace(/,/g,"").split(".");
  j = v[0].length % 3;
  sj = v[0].substr(j).toString();
  for (var i = 0; i < sj.length; i++)
  {
    rv = (i % 3 == 0) ? rv + "," + sj.substr(i, 1): rv + sj.substr(i, 1);
  }
  var rvalue = (v[1] == undefined) ? v[0].substr(0, j) + rv: v[0].substr(0, j) + rv + "." + v[1];
  if (rvalue.charCodeAt(0) == 44)
  {
    rvalue = rvalue.substr(1);
  }

  return rvalue;
}
</script>
</html>
