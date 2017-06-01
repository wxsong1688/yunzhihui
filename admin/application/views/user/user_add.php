<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">用户管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>添加用户</h3>
    </div>
    <form class="stdform" action="addUserData" method="post" id="thisForm" >
    <input type="hidden" value="<?php echo $from;?>" id="action_from" name="action_from">
        <p>
            <label><font color="red">*</font>&nbsp;用户名</label>
            <span class="field">
                <input type="text" id="username" name="username" class="smallinput" />
            </span>
            <small class="error" >请填写用户名</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;密码</label>
            <span class="field">
                <input type="password" id="pwd1" name="pwd1" class="smallinput" />
            </span>
            <small class="error" >请填写密码</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;确认密码</label>
            <span class="field">
                <input type="password" id="pwd1_repeat" name="pwd1_repeat" class="smallinput" />
            </span>
            <small class="error" >请填写确认密码</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;用户类型</label>
            <span class="field">
            <?php if($from==1):?>
                <select id="type" name="type" class="uniformselect">
                    <option value="0">请选择</option>
                    <?php if($userinfo['role']==1):?>
                        <option value="2">普通管理员</option>
                        <option value="4">风险控制管理员</option>
                    <?php endif;?>
                    <option value="3">客服</option>
                </select>
            <?php else:?>
                <select id="type" name="type" class="uniformselect" readonly>
                    <option value="7">内部融资人</option>
                </select>
            <?php endif;?>
            </span>
            <small class="error">请选择用户类型</small>
        </p>
        <div id="nbrz_info" <?php if($from==1):?>style="display:none;"<?php endif;?>>
            <p>
                <label><font color="red">*</font>&nbsp;电话号码</label>
                <span class="field">
                    <input type="text" id="phone" name="phone" class="smallinput" />
                </span>
                <small class="error" >请填写电话号码</small>
            </p>
            <p>
                <label><font color="red">*</font>&nbsp;电子邮箱</label>
                <span class="field">
                    <input type="text" id="email" name="email" class="smallinput" />
                </span>  
                <small class="error">电子邮箱已存在</small>
            </p>

        </div>
       
            <p class="stdformbutton">
                <button class="submit radius2" id="submitForm">提交</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
            </p>
            </form>
        </div>
    </div>
<script type="text/javascript">
$("#type").change(function(){
    $(".error").hide();
    if($(this).val() == 7){
        $("#nbrz_info").show();
    }else{
        $("#nbrz_info").hide();
    }
})
$("#submitForm").click(function(){
    $(".error").hide();
    var from = $("#action_from").val();
    $("#username").parent().next().html('请填写用户名');
    $("#phone").parent().next().html('请填写电话号码');
    $("#email").parent().next().html('请填写邮箱');

    flag = checkRequired($("#username"));
    if(!flag){ 
        return false; 
    }

    flag = checkRequired($("#email"));
    if(!flag && from==2){
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

    flag = checkRequired($("#type"),1);
    if(!flag){ 
        return false; 
    }

    if($("#type").val() == 7){
        flag = checkRequired($("#phone"));
        if(!flag){ 
            return false; 
        }
    }

    //验证用户名,手机号和邮箱的唯一性
    var username = $.trim(encodeURIComponent($("#username").val()));
    var phone    = $.trim($("#phone").val());
    var email    = $.trim($("#email").val());
    
    var pjsonData = {
        'username' : username,
    }
    if(phone != '')
    {
        pjsonData.phone =  phone;
    }
    if(email != '')
    {
        pjsonData.email =  email;
    }

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
            }else if(d.phone && d.phone == true){
                $("#phone").parent().next().html('电话号码已存在');
                $("#phone").parent().next().show();
                return false;
            }else if(d.email && d.email == true){
                $("#email").parent().next().show();
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
    if($.trim(item.val()) == data)
    {
        item.parent().next().show();
        return 0;
    }
    return 1;
}
</script>