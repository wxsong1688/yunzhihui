<body class="withvernav" id="bob">
<div class="bodywrapper">
    <div class="topheader">
        <div class="left">
            <h1 class="logo"><span>云智慧</span></h1>
            <span class="slogan">后台管理系统</span>
            <br clear="all" />
            
        </div><!--left-->
        
        <div class="right">
            <div class="userinfo">
                <img src="/static_develop/images/thumbs/avatar.png" alt="" />
                <span><?php echo $username;?></span>
                <span id="logout">退出</span>
            </div><!--userinfo-->
        </div><!--right-->
    </div><!--topheader-->
    <!--定义全部模块的方法-->

	<div class="vernav2 iconmenu">
        <ul>
		<!--用户管理-->
		<?php if(in_array($role,array(1,2,3,4))):?>
	        <li class="showMenu <?php echo $route['controller'] == 'User'?'current':''; ?>">
	            <a href="javascript:;"  class="support">用户管理</a><span class="arrow"></span>
	        </li>
	        <ul style="display:<?php echo $route['controller'] == 'User'?'block':'none';?>;">
	           	<li class="<?php echo $route['action'] == 'manager_index' && $route['controller'] == 'User'?'current':'';?>"><a href="/User/manager_index">管理员列表</a></li>
	           	<li class="<?php echo $route['action'] == 'finance_index' && $route['controller'] == 'User'?'current':'';?>"><a href="/User/finance_index">内部融资人列表</a></li>
	           	<li class="<?php echo $route['action'] == 'invest_index' && $route['controller'] == 'User'?'current':'';?>"><a href="/User/invest_index">投资人列表</a></li>
	           	<?php if(in_array($role,array(1,2))):?>
	           		<li class="<?php echo $route['action'] == 'level' && $route['controller'] == 'User'?'current':'';?>"><a href="/User/level">投资人等级管理</a></li>
	       		<?php endif;?>
	        </ul>
        <?php endif;?>

        <!--项目管理-->
        <li class="showMenu <?php echo $route['controller'] == 'Project'?'current':''; ?>">
            <a href="javascript:;"  class="widgets">项目管理</a><span class="arrow"></span>
        </li>
        <ul style="display:<?php echo $route['controller'] == 'Project'?'block':'none';?>;">
        	<?php if(in_array($role,array(1,2,3,4))):?>
	            <li class="<?php echo $route['action'] == 'index' && $route['controller'] == 'Project'?'current':'';?>"><a href="/Project/index">项目管理</a></li>
	            <li class="<?php echo $route['action'] == 'zqzr' && $route['controller'] == 'Project'?'current':'';?>"><a href="/Project/zqzr">债权转让管理</a></li>
            <?php endif;?>
            <?php if(in_array($role,array(1,2))):?>
            	<li class="<?php echo $route['action'] == 'cycle' && $route['controller'] == 'Project'?'current':'';?>"><a href="/Project/cycle">项目周期管理</a></li>
			<?php endif;?>
			<?php if(in_array($role,array(7))):?>
                <li class="<?php echo $route['action'] == 'index' && $route['controller'] == 'Project'?'current':'';?>"><a href="/Project/index">我的项目</a></li>
	            <li class="<?php echo $route['action'] == 'addProject' && $route['controller'] == 'Project'?'current':'';?>"><a href="/Project/addProject">发布借款</a></li>
            <?php endif;?>
        </ul>

        <!--资产管理-->
		<?php if(in_array($role,array(1,2,3,4))):?>
	        <li class="showMenu <?php echo $route['controller'] == 'Asset'?'current':''; ?>">
	            <a href="javascript:;"  class="elements">资产管理</a><span class="arrow"></span>
	        </li>
	        <ul style="display:<?php echo $route['controller'] == 'Asset'?'block':'none';?>;">
	        	<?php if(in_array($role,array(1,2))):?>
	            	<li class="<?php echo $route['action'] == 'platform'?'current':'';?>"><a href="/Asset/platform">平台资产管理</a></li>
	            <?php endif;?>
	            <li class="<?php echo $route['action'] == 'finance'?'current':'';?>"><a href="/Asset/finance">融资人资产管理</a></li>
	            <li class="<?php echo $route['action'] == 'invest'?'current':'';?>"><a href="/Asset/invest">投资人资产管理</a></li>
	        </ul>
	    <?php else:?>
			<li class="showMenu <?php echo $route['controller'] == 'Asset' && $route['action'] == 'myindex'?'current':''; ?>">
	            <a href="/Asset/myindex" class="elements">资产管理</a>
	        </li>
	        <li class="showMenu <?php echo $route['controller'] == 'Asset' && $route['action'] == 'account'?'current':''; ?>">
	            <a href="/Asset/account"  class="addons">账户设置</a>
	        </li>
        <?php endif;?>

        <!--系统管理-->
		<?php if(in_array($role,array(1,2,3,4))):?>
	        <li class="showMenu <?php echo $route['controller'] == 'System'?'current':''; ?>">
	            <a href="javascript:;"  class="addons">系统设置</a><span class="arrow"></span>
	        </li>
	        <ul style="display:<?php echo $route['controller'] == 'System'?'block':'none';?>;">
	            <li class="<?php echo $route['action'] == 'help'?'current':'';?>"><a href="/System/help">常见问题</a></li>
	            <li class="<?php echo $route['action'] == 'log'?'current':'';?>"><a href="/System/log">日志管理</a></li>
	        </ul>
        <?php endif;?>
        </ul>
		<br /><br />
    </div>
<script type="text/javascript">
$(".showMenu").click(function(){
	//alert("das");return false;
    var obj = $(this);
    //alert($(this).next('ul').html());return false;//不兼容
    obj.next('ul').toggle();
})
$("#logout").click(function(){
    window.location.href="/Login/logout";
})
/*$("#zichanman").click(function(){
    window.location.href = $(this).parent().next('ul').find('li:first a').attr('href');
})*/
//写入cookie
function SetCookie(name,value)
{
    var Days = 1; //此 cookie 将被保存 1 天
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+";domain=admin.antxd.com";
}
///删除cookie
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString()+";domain=admin.antxd.com";
}
//读取cookie
function getCookie(name)
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null)
    return unescape(arr[2]);
    return null;
}
</script>
