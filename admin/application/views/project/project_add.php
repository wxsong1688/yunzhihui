<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">项目管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>添加项目</h3>
    </div>
    <form class="stdform" action="addProjectData" method="post" id="thisForm" enctype="multipart/form-data">
        <div class="lit_title"><h5>项目信息</h5></div>
        <p>
            <label><font color="red">*</font>&nbsp;项目名称</label>
            <span class="field">
                <input type="text" id="pro_name" name="pro_name" class="smallinput" />
            </span>
            <small class="error" >请填写项目名称</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;融资金额</label>
            <span class="field">
                <input type="text" id="amount" name="amount" class="smallinput" onkeyup="tranValue('amount')" />&nbsp;元
            </span>
            <small class="error">请填写融资金额</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;项目周期</label>
            <span class="field">
                <select id="cycle" name="cycle" class="uniformselect">
                    <option value="0">请选择</option>
                    <?php foreach($cycleinfo as $k=>$v):?>
                        <?php if($v['ifshow'] == 1):?>
                            <option value="<?php echo $v['cycle'];?>"><?php echo $v['cycle']*30;?>天</option>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
            </span>
            <small class="error">请选择项目周期</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;用途</label>
            <span class="field">
                <input type="text" id="proj_use" name="proj_use" class="smallinput" />
            </span>
            <small class="error">请填写用途</small>
        </p>
        <p>
            <label>融资照片</label>
            <span class="field">
            <div class="uploader" id="uniform-undefined">
                <input type="file" id="uploadFile" name="uploadFile" size="19" style="opacity: 0;">
                <span class="filename" id="filename"></span>
                <span class="action">选择文件</span>      
            </div>
            <!-- <img src="http://m.zhongsou.com/img/m_logoicon.png" id="showImg">    -->              
            </span>
        </p>

        <div class="lit_title"><h5>借款方信息</h5></div>
        <p>
            <label>平台用户名</label>
            <span class="field">
                <input style="background:#ccc;" type="text" id="financier_username"  readonly="readonly" name="financier_username" class="smallinput" value="<?php echo $userinfo['username'];?>"/>
            </span>
        </p>
        <p>
            <label>
            <font color="red">*</font>&nbsp;姓名</label>
            <span class="field">
                <input style="background:#ccc;" type="text" id="financier_realname"  readonly="readonly" name="financier_realname" class="smallinput" value="<?php echo $userinfo['realname'];?>"/>
            </span>
            <small class="error">借款人姓名不能为空，请联系管理员</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;年龄</label>
            <span class="field">
                <input type="text" style="background:#ccc;" id="financier_year" name="financier_year" readonly="readonly" value="<?php echo $userinfo['age'];?>" class="smallinput" />
            </span>
            <small class="error">请填写借款人年龄</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;性别</label>
            <span class="formwrapper">
                <input type="radio" class="financier_sex" name="financier_sex"  value="1"/>&nbsp;男&nbsp;&nbsp;
                <input type="radio" name="financier_sex" value="2"/>&nbsp;女
            </span>
            <small class="error">请填写借款人性别</small>
        </p>        
        <p>
            <label><font color="red">*</font>&nbsp;婚姻状况</label>
            <span class="formwrapper">
                <input type="radio" class="financier_mar" name="financier_mar"  value="1"/>&nbsp;已婚&nbsp;&nbsp;
                <input type="radio" name="financier_mar" value="2"/>&nbsp;未婚&nbsp;&nbsp;
                <input type="radio" name="financier_mar" value="0"/>&nbsp;其他
            </span>
            <small class="error">请填写借款人婚姻状况</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;企业行业</label>
            <span class="field">
                <input type="text" id="comp_industry" name="comp_industry" class="smallinput" />
            </span>
            <small class="error">请填写企业行业</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;企业规模</label>
            <span class="field">
                <input type="text" id="comp_scale" name="comp_scale" class="smallinput" />
            </span>
            <small class="error">请填写企业规模</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;抵押担保方式</label>
            <span class="field">
                <input type="text" id="comp_guarantee" name="comp_guarantee" class="smallinput" />
            </span>
            <small class="error">请填写抵押担保方式</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;借款项目详述</label>
            <span class="field">
                <textarea id="proj_desc" name="proj_desc" cols="80" rows="5" class="longinput"></textarea>
            </span>
            <small class="error">请填写借款项目详述</small>
        </p>
            <p class="stdformbutton">
                <button class="submit radius2" id="submitForm">提交</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
            </p>
            </form>
        </div>
    </div>
<script type="text/javascript">
$("#uploadFile").change(function(){
    /*$("#showImg").attr('src',$(this).val());*/
    $("#filename").html($(this).val());
})
$("#submitForm").click(function(){
    $(".error").hide();
    $("#amount").parent().next().html("请填写融资金额");
    //必填项验证
    var flag = 1;
    flag = checkRequired($("#pro_name"));
    if(!flag){ 
        return false; 
    }
    flag = checkRequired($("#amount"));
    if(!flag){ 
        return false; 
    }
    //发布金额最少必须是一万，最小以万为单位
    var amount = $("#amount").val();
    amount = amount.replace(/,/g,"");
    if(parseInt(amount)<100){
        $("#amount").parent().next().html("融资金额不能少于100");
        $("#amount").parent().next().show();
        return false;
    }
    if(parseInt(amount) % 100 != 0){
        $("#amount").parent().next().html("融资金额最小单位为100");
        $("#amount").parent().next().show();
        return false;
    }
    flag = checkRequired($("#cycle"),1);
    if(!flag){ 
        return false; 
    }
    flag = checkRequired($("#proj_use"));
    if(!flag){ 
        return false;
    }

    flag = checkRequired($("#financier_realname"));
    if(!flag){ 
        return false; 
    }

    var financier_sex = $('input:radio[name="financier_sex"]:checked').val();
    if(financier_sex == undefined)
    {
        $(".financier_sex").parent().next().show();
        return false;
    }

    flag = checkRequired($("#financier_year"));
    if(!flag){ 
        return false; 
    }

    var financier_mar = $('input:radio[name="financier_mar"]:checked').val();
    if(financier_mar == undefined)
    {
        $(".financier_mar").parent().next().show();
        return false;   
    }

    flag = checkRequired($("#comp_industry"));
    if(!flag){ 
        return false; 
    }
    flag = checkRequired($("#comp_scale"));
    if(!flag){ 
        return false; 
    }

    flag = checkRequired($("#comp_guarantee"));
    if(!flag){ 
        return false; 
    }

    flag = checkRequired($("#proj_desc"));
    if(!flag)
    {
        return false;
    }else{
        $("#thisForm").submit();
    }
})
</script>