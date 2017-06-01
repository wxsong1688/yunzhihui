<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>悦生活-我的投资</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/header.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/banner.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/index-middle.css"></link>
    <link rel="stylesheet" type="text/css" href="/public/css/footer.css"></link>
    <script type="text/javascript" src="/public/js/jquery.min.js"></script>
    <script type="text/javascript" src="/public/js/jQueryRotate.2.2.js"></script>
    <script type="text/javascript" src="/public/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/public/js/i.js"></script>
    <script type="text/javascript" src="/public/js/header.js"></script>
    <script type="text/javascript" src="/public/js/index.js"></script>
    <script type="text/javascript" src="/public/js/banner.js"></script>
    <script type="text/javascript" src="/public/js/widthSet.js"></script>
</head>
<SCRIPT LANGUAGE="javascript">
function opendetail(pid){
	window.open ('/project/myprojectdetail?pro_id='+pid, 'newwindow', 'height=400, width=400, top=200, left=400, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
}
function confirmAct(pid,mpid)
{
    if(confirm('确定要执行此操作吗?'))
    {
        $.ajax({
            url:'/projectcredit/docredit',  
            data:{pid:pid,mpid:mpid},  
            error:function(){  
                alert("error occured!!!");  
            },  
            success:function(data){  
                if(data=='success'){
                    alert("债权转让成功！");
                    location.href = "/projectcredit/index";
                }else{
                    alert(data);
                    location.href = "/project/myproject";
                }
            }  
       
        }); 
    }
    return false;
} 

</SCRIPT> 
<body>
<div class="footer-center show-center">
我的投资： <?php if(empty($user)){echo "<a href='/login'>登陆</a>";}else{echo "用户名：".$user;}?><br/><br/>
<table>
<tr>
<td width=10%>ID</td>
<td width=10%>项目名称</td>
<td width=10%>项目总额</td>
<td width=10%>项目状态</td>
<td width=10%>我的投资金额</td>
<td width=10%>操作</td>
</tr>
<?php foreach ($mypro as $k =>$v):?>
<tr>
<td><?php echo $v['id'];?></td>
<td><?php echo $v['myProInfo']['pro_name'];?></td>
<td><?php echo sprintf("%.2f", $v['myProInfo']['amount']);?></td>
<td><?php echo $v['myProInfo']['stat'];?> <?php echo $v['myProInfo']['credit_stat'];?></td>
<td><?php echo sprintf("%.2f", $v['invest_sum']);?></td>
<td>
	<button type="button" onclick="opendetail(<?php echo $v['pro_id'];?>)">查看详情</button>
    <?php if($v['credit_status']==0){?>
	<button type="button" onclick="return confirmAct(<?php echo $v['pro_id'];?>,<?php echo $v['id'];?>);">债权转让</button>
    <?php }?>
</td>
</tr>
<?php endforeach;?>
</table>
</div>
</body>
</html>