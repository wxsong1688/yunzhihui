<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">用户管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<?php 
    $userinfo = $item[0];
?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>编辑用户</h3>
    </div>
    <input type="hidden" value="<?php echo $userinfo['type'];?>" id="isbackuser">
    <form class="stdform" action="editUserData" method="post" id="thisForm" >
        <input type="hidden" value="<?php echo $userinfo['uid'];?>" name="uid" id="uid">
        <p>
            <label><font color="red">*</font>&nbsp;用户名</label>
            <span class="field">
                <input type="text" id="username" name="username" class="smallinput" value="<?php echo $userinfo['username'];?>"/>
            </span>
            <small class="error" >请填写用户名</small>
        </p>

            <p>
                <label><font color="red">*</font>&nbsp;密码</label>
                <span class="field">
                    <input type="password" id="pwd1" name="pwd1" class="smallinput" />
                </span>
                <small class="desc" >不填默认为不修改</small>
            </p>
            <p id="pwd1_repeat_p" style="display:none;">
                <label><font color="red">*</font>&nbsp;确认密码</label>
                <span class="field">
                    <input type="password" id="pwd1_repeat" name="pwd1_repeat" class="smallinput" />
                </span>
                <small class="error" >请填写确认密码</small>
            </p>

        <p>
            <label><font color="red">*</font>&nbsp;用户类型</label>
            <span class="field">
                <select id="type" style="background:#ccc;" disabled="disabled" name="type" class="uniformselect">
                    <option value="0">请选择</option>
                    <option value="2" <?php if($userinfo['type']==2):?>selected="selected"<?php endif;?>>普通管理员</option>
                    <option value="3" <?php if($userinfo['type']==3):?>selected="selected"<?php endif;?>>客服</option>
                    <option value="4" <?php if($userinfo['type']==4):?>selected="selected"<?php endif;?>>风险控制管理员</option>
                </select>
            </span>
            <small class="error">请选择用户类型</small>
        </p>

       
            <p class="stdformbutton">
                <button class="submit radius2" id="submitForm">提交</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
            </p>
            </form>
        </div>
    </div>
<script type="text/javascript">
$("#pwd1").blur(function(){
    if($("#pwd1").val() != ''){
        $("#pwd1_repeat_p").show();
    }else{
        $("#pwd1_repeat_p").hide();
    }
})
$("#submitForm").click(function(){
    $(".error").hide();
    $("#username").parent().next().html('请填写用户名');

    var isbackuser = $("#isbackuser").val();
    if(isbackuser == 1 || isbackuser ==2 || isbackuser ==3 || isbackuser ==7){
        flag = checkRequired($("#username"));
        if(!flag){ 
            return false; 
        }
    }

    if($("#pwd1").val() != ''){
        flag = checkRequired($("#pwd1_repeat"));
        if(!flag){ 
            return false; 
        }
        if($("#pwd1_repeat").val() != $("#pwd1").val()){
            $("#pwd1_repeat").parent().next().html("两次密码不一致");
            $("#pwd1_repeat").parent().next().show();
            return false;
        }
    }


    //验证用户名,手机号和邮箱的唯一性
    var username = $.trim(encodeURIComponent($("#username").val()));
    var uid      = $("#uid").val();

    var pjsonData = {};
    if(username != ''){
        pjsonData.username = username;
    }

    pjsonData.uid = uid;

    $.ajax({
        url: '/User/checkUserInfo',
        data: pjsonData,
        type: "post",
        async: false,
        dataType: "json",
        success: function (d) {
            if(d.username && d.username == true){
                $("#username").parent().next().html('用户名已存在');
                $("#username").parent().next().show()
                return false;
            }else{
                $("#thisForm").submit();
            }
        }
    });
    return false;   
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
</script>