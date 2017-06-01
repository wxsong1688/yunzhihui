<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">账户设置</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<?php 
    $userinfo = $item[0];
?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>修改密码</h3>
    </div>
    <form class="stdform" action="editUserPwdData" method="post" id="thisForm" >
        <input type="hidden" value="<?php echo $userinfo['uid'];?>" name="uid" id="uid">
            <p>
                <label><font color="red">*</font>&nbsp;原密码</label>
                <span class="field">
                    <input type="password" id="pwd" name="pwd" class="smallinput" />
                </span>
                <small class="error" >请填写原密码</small>
            </p>
            <p>
                <label><font color="red">*</font>&nbsp;新密码</label>
                <span class="field">
                    <input type="password" id="pwd1" name="pwd1" class="smallinput" />
                </span>
                <small class="error" >请填写新密码</small>
            </p>
            <p id="pwd1_repeat_p">
                <label><font color="red">*</font>&nbsp;确认新密码</label>
                <span class="field">
                    <input type="password" id="pwd1_repeat" name="pwd1_repeat" class="smallinput" />
                </span>
                <small class="error" >请确认新密码</small>
            </p>
            <p class="stdformbutton">
                <button class="submit radius2" id="submitForm">提交</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
            </p>
            </form>
        </div>
    </div>
<script type="text/javascript">
$("#submitForm").click(function(){
    $(".error").hide();
    flag = checkRequired($("#pwd"));
    if(!flag){ 
        return false; 
    }
    flag = checkRequired($("#pwd1"));
    if(!flag){ 
        return false; 
    }
    flag = checkRequired($("#pwd1_repeat"));
    if(!flag){ 
        return false; 
    }

    if($("#pwd1_repeat").val() != $("#pwd1").val()){
        $("#pwd1_repeat").parent().next().html("两次密码不一致");
        $("#pwd1_repeat").parent().next().show();
        return false;
    }
    $("#thisForm").submit();
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