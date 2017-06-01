<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">项目管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<?php 
$prod_info = $item[0];
$prod_status = $item[0]['status'];
?>
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>审核项目</h3>
    </div>
    <form class="stdform" action="checkProjectData" method="post" id="thisForm" enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $prod_info['id'];?>" name="pid">
        <input type="hidden" value="<?php echo $prod_status;?>" name="prod_status" id="prod_status">
        
        <div class="lit_title"><h5>项目信息</h5></div>
        <p>
            <label><font color="red">*</font>&nbsp;项目名称</label>
            <span class="field">
                <input type="text" id="pro_name" name="pro_name" class="smallinput" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>  value="<?php echo $prod_info['pro_name'];?>"/>
            </span>
            <small class="error" >请填写项目名称</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;融资金额</label>
            <span class="field">
                <input style="background:#ccc;" readonly="readonly"  type="text" id="amount" name="amount" class="smallinput" value="<?php echo $prod_info['amount'];?>" />&nbsp;元
            </span>
            <small class="error">请填写融资金额</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;项目周期</label>
            <span class="field">
                <select style="background:#ccc;"  disabled="disabled"  id="cycle" name="cycle" class="uniformselect">
                    <option value="0">请选择</option>
                    <?php foreach($cycleinfo as $k=>$v):?>
                        <?php if($v['ifshow'] == 1):?>
                            <option value="<?php echo $v['cycle'];?>" <?php echo $prod_info['cycle']==$v['cycle']?'selected':'';?>><?php echo $v['cycle']*30;?>天</option>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
            </span>
            <small class="error">项目周期备选项更改，原备选周期删除</small>
        </p>
        <?php 
            $companyinfo   = unserialize($prod_info['companyinfo']);
            $projectinfo   = unserialize($prod_info['projectinfo']);
            $financierinfo = unserialize($prod_info['financierinfo']);
        ?>
        <p>
            <label><font color="red">*</font>&nbsp;用途</label>
            <span class="field">
                <input type="text" id="proj_use" name="proj_use" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>class="smallinput" value="<?php echo $projectinfo['proj_use'];?>"/>
            </span>
            <small class="error">请填写用途</small>
        </p> 
        <p>
            <label>融资照片</label>
            <span class="field">
                <?php if($prod_status!=2):?>
                    <div class="uploader" id="uniform-undefined">
                        <input type="file" id="uploadFile" name="uploadFile" size="19" style="opacity: 0;">
                        <span class="filename" id="filename"></span>
                        <span class="action">选择文件</span>   
                        <input type="hidden" name="source_img" value="<?php echo isset($projectinfo['proj_rzpic']) ? $projectinfo['proj_rzpic'] : '' ;?>">   
                    </div>
                <?php endif;?>
                <?php if(isset($projectinfo['proj_rzpic'])):?>
                <div id="imgShow" style="border: 1px solid #ccc; margin-left: 220px; margin-top: 20px;margin-right: 250px;margin-bottom: 20px;"><img height="200" width="200" src="<?php echo $projectinfo['proj_rzpic'];?>" id="showImg"></div>
            <?php endif;?>
            </span>
        </p>

        <div class="lit_title"><h5>借款方信息</h5></div>
        <p>
            <label>
            <font color="red">*</font>&nbsp;平台用户名</label>
            <span class="field">
                <input style="background:#ccc;" readonly="readonly" type="text" id="financier_username" name="financier_username" class="smallinput" value="<?php echo $financierinfo['financier_username'];?>"/>
            </span>
        </p>
        <p>
            <label>
            <font color="red">*</font>&nbsp;姓名</label>
            <span class="field">
                <input style="background:#ccc;" readonly="readonly" type="text" id="financier_realname" name="financier_realname" class="smallinput" value="<?php echo isset($financierinfo['financier_realname'])?$financierinfo['financier_realname']:'';?>"/>
            </span>
            <small class="error">借款人姓名不能为空，请联系管理员</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;年龄</label>
            <span class="field">
                <input type="text" id="financier_year" name="financier_year" style="background:#ccc;" readonly="readonly"  class="smallinput" value="<?php echo $financierinfo['financier_year'];?>"/>
            </span>
            <small class="error">请填写借款人年龄</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;性别</label>
            <span class="formwrapper">
                <input type="hidden" value="<?php echo $financierinfo['financier_sex'];?>" name="financier_sex">
                <input type="radio" style="background:#ccc;" disabled  class="financier_sex" name="financier_sex"  <?php echo $financierinfo['financier_sex'] == 1 ? 'checked':'';?> value="1"/>&nbsp;男&nbsp;&nbsp;
                <input type="radio" style="background:#ccc;" disabled  name="financier_sex" value="2" <?php echo $financierinfo['financier_sex']==2 ? 'checked':'';?>/>&nbsp;女
            </span>
            <small class="error">请填写借款人性别</small>
        </p>        
        <p>
            <label><font color="red">*</font>&nbsp;婚姻状况</label>
            <span class="formwrapper">
            <input type="hidden" value="<?php echo $financierinfo['financier_mar'];?>" name="financier_mar">
                <input style="background:#ccc;" disabled type="radio" class="financier_mar" name="financier_mar"  <?php echo $financierinfo['financier_mar'] == 1 ? 'checked':'';?> value="1"/>&nbsp;已婚&nbsp;&nbsp;
                <input style="background:#ccc;" disabled type="radio" name="financier_mar" value="2" <?php echo $financierinfo['financier_mar'] == 2 ? 'checked':'';?>/>&nbsp;未婚&nbsp;&nbsp;
                <input style="background:#ccc;" disabled type="radio" name="financier_mar" value="0" <?php echo $financierinfo['financier_mar'] == 0 ? 'checked':'';?>/>&nbsp;其他
            </span>
            <small class="error">请填写借款人婚姻状况</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;企业行业</label>
            <span class="field">
                <input type="text" id="comp_industry" name="comp_industry" class="smallinput" value="<?php echo $companyinfo['comp_industry'];?>" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>/>
            </span>
            <small class="error">请填写企业行业</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;企业规模</label>
            <span class="field">
                <input type="text" id="comp_scale" name="comp_scale" class="smallinput" value="<?php echo $companyinfo['comp_scale'];?>" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>/>
            </span>
            <small class="error">请填写企业规模</small>
        </p>
        <p>
                <label><font color="red">*</font>&nbsp;抵押担保方式</label>
                <span class="field">
                    <input type="text" id="comp_guarantee" name="comp_guarantee" class="smallinput" value="<?php echo $companyinfo['comp_guarantee'];?>" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>/>
                </span>
                <small class="error">请填写抵押担保方式</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;借款项目详述</label>
            <span class="field">
                <textarea id="proj_desc" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?> name="proj_desc" cols="80" rows="5" class="longinput"><?php echo $projectinfo['proj_desc'];?></textarea>
            </span>
            <small class="error">请填写借款项目详述</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;项目类型</label>
            <span class="field">
                <select id="type" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>  name="type" class="uniformselect">
                    <option value="0">请选择</option>
                    <!--<option value="1" <?php //echo $prod_info['type']==1?'selected':'';?>>新手专享</option>-->
                    <option value="2" <?php echo $prod_info['type']==2?'selected':'';?>>普惠金融</option>
                    <option value="3" <?php echo $prod_info['type']==3?'selected':'';?>>精英理财</option>
                    <option value="4" <?php echo $prod_info['type']==4?'selected':'';?>>高端定制</option>
                </select>
            </span>
            <small class="error">请选择项目类型</small>
        </p>
        <div class="lit_title"><h5>利率信息</h5></div>
        <p>
            <label><font color="red">*</font>&nbsp;融资利率</label>
            <span class="field">
                <input type="text" id="year_rate_in" name="year_rate_in" class="smallinput" value="<?php echo $prod_info['year_rate_in']=='0.000'?'':$prod_info['year_rate_in'];?>" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>/>&nbsp;%
            </span>
            <small class="desc">范围：0%-50%之间；融资利率大于投资利率</small> 
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;投资利率</label>
            <span class="field">
                <input type="text" id="year_rate_out" name="year_rate_out" class="smallinput" value="<?php echo $prod_info['year_rate_out']=='0.000'?'':$prod_info['year_rate_out'];?>" <?php if($prod_status==2):?>style="background:#ccc;" disabled="disabled"<?php endif;?>/>&nbsp;%
            </span>
            <small class="desc">范围：0%-50%之间</small>
        </p>
        <div class="lit_title"><h5>云智慧平台审核</h5></div>
            <span class="formwrapper">
                <?php if($prod_status==2):?>
                    <div class="checker disabled">
                        <span class="checked"><input class="checkbox" type="checkbox" id="checker1" disabled="disabled" style="opacity: 0;"></span>
                    </div>借款人身份验证（身份证，户口本等）<br>
                    <div class="checker disabled">
                        <span class="checked"><input class="checkbox" type="checkbox" id="checker2" disabled="disabled" style="opacity: 0;"></span>
                    </div>企业执照认证（营业执照，组织机构代码证，税务登记证等）<br>
                    <div class="checker disabled">
                        <span class="checked"><input type="checkbox" class="checkbox" id="checker3" disabled="disabled" style="opacity: 0;"></span>
                    </div>借款人面对面详谈<br>
                    <div class="checker disabled">
                        <span class="checked"><input type="checkbox" class="checkbox" id="checker4" disabled="disabled" style="opacity: 0;"></span>
                    </div>企业实地考察（企业行业发展趋势，经营情况）<br>
                    <div class="checker disabled">
                        <span class="checked"><input type="checkbox" class="checkbox" id="checker5" disabled="disabled" style="opacity: 0;"></span>
                    </div>融资满标后，不定期进行项目跟踪和监督<br>
                    <div class="checker disabled">
                        <span <?php if($prod_info['notice']!=''):?>class="checked"<?php endif;?>><input type="checkbox" class="checkbox" id="checker6" disabled="disabled" style="opacity: 0;"></span>
                    </div>备注&nbsp;<input type="text" value="<?php echo $prod_info['notice'];?>" id="notice" name="notice" style="width:190px; padding:3px 3px;">&nbsp;(可选)
                    <br>
                <?php else:?>
                    <div class="checker">
                    <span><input class="checkbox" type="checkbox" id="checker1" style="opacity: 0;"></span>
                    </div>借款人身份验证（身份证，户口本等）<br>
                    <div class="checker">
                        <span><input class="checkbox" type="checkbox" id="checker2" style="opacity: 0;"></span>
                    </div>企业执照认证（营业执照，组织机构代码证，税务登记证等）<br>
                    <div class="checker">
                        <span><input type="checkbox" class="checkbox" id="checker3" style="opacity: 0;"></span>
                    </div>借款人面对面详谈<br>
                    <div class="checker">
                        <span><input type="checkbox" class="checkbox" id="checker4" style="opacity: 0;"></span>
                    </div>企业实地考察（企业行业发展趋势，经营情况）<br>
                    <div class="checker">
                        <span><input type="checkbox" class="checkbox" id="checker5" style="opacity: 0;"></span>
                    </div>融资满标后，不定期进行项目跟踪和监督<br>
                    <!--<div class="checker">
                        <span><input type="checkbox" class="checkbox" id="checker6" style="opacity: 0;"></span>
                    </div>备注&nbsp;<input type="text" id="notice" name="notice" style="width:190px; padding:3px 3px;">&nbsp;(可选)-->
                    <br>
                <?php endif;?>
            </span>

            <small class="error">审核条件必须全部勾选，才能审核通过</small>
            <p id="reject_proj_p" style="display:none;">
                <label>&nbsp;驳回理由</label>
                <span class="field">
                    <input type="text" id="reject_reason" name="reject_reason" class="smallinput" />&nbsp;
                </span>
                <small class="error" style="display:block;">审核驳回请填写驳回理由(驳回后审核信息将不保存)</small>
            </p>
            <input type="hidden" value="0" id="ifreject" name="ifreject">
            <p class="stdformbutton">
                <button class="submit radius2" id="submitForm"><?php if($prod_status==1):?>初审通过<?php else:?>复审通过<?php endif;?></button>
                <input type="reset" class="reset radius2" value="驳回" id="reject_proj" />
            </p>
            </form>
        </div>
    </div>
<script type="text/javascript">
window.onload=function ()
{
    var avalObj = $("#amount");
    var naval = tran(avalObj.val());
    avalObj.val(naval);
}

$("#uploadFile").change(function(){
    /*$("#showImg").attr('src',$(this).val());*/
    $("#imgShow").hide();
    $("#filename").html($(this).val());
})

$(".checkbox").click(function(){
    var checked = $(this).parent().attr('class');
    if(checked == "checked"){
        $(this).parent().removeClass('checked');
    }else{
        $(this).parent().addClass('checked');
    }
})

$("#reject_proj").click(function(){
    if($("#reject_proj_p").css('display') == 'none')
    {
        $("#reject_proj_p").show();
        $("#reject_proj_p").find('error').show();
    }else{
        flag = checkRequired($("#reject_reason"));
        if(!flag){ 
            $("#reject_reason").parent().next().show();
            return false;
        }
        $("#ifreject").val(1);
        $("#thisForm").submit();
    }
})

$("#submitForm").click(function(){
    $("#reject_proj_p").hide();
    //复审
    var prod_status = $("#prod_status").val();
    if(prod_status == 2){
        $("#thisForm").submit();
        return false;
    }

    $(".error").hide();
    //必填项验证
    var flag = 1;
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
    if(!flag){
        return false;
    }
    flag = checkRequired($("#type"),1);
    if(!flag){
        return false;
    }

    var year_rate_in = $("#year_rate_in").val();
    if($.trim(year_rate_in) == ''){
       year_rate_in  = 0;
    }
    year_rate_in = parseFloat(year_rate_in);
    if(year_rate_in <= 0 || year_rate_in >= 50)
    {
        var rate_in_obj = $("#year_rate_in").parent().next();
        rate_in_obj.removeClass('desc').addClass('error').show().html('融资利率必须在0%-50%之间');
        return false;
    }

    var year_rate_out = $("#year_rate_out").val();
    if($.trim(year_rate_out) == ''){
       year_rate_out  = 0;
    }
    year_rate_out = parseFloat(year_rate_out);
    if(year_rate_out <= 0 || year_rate_out >= 50)
    {
        var rate_out_obj = $("#year_rate_out").parent().next();
        rate_out_obj.removeClass('desc').addClass('error').show().html('投资利率必须在0%-50%之间');
        return false;
    }

    if(year_rate_in <= year_rate_out)
    {
        var rate_out_obj = $("#year_rate_out").parent().next();
        rate_out_obj.removeClass('desc').addClass('error').show().html('投资利率要小于融资利率');
        return false;
    }

    var s = "checked";
    var check1 = $("#checker1").parent().attr('class');
    var check2 = $("#checker2").parent().attr('class');
    var check3 = $("#checker3").parent().attr('class');
    var check4 = $("#checker4").parent().attr('class');
    var check5 = $("#checker5").parent().attr('class');

    if(check1!=s || check2!=s && check3!=s || check4!=s || check5!=s )
    {
        $("#checker1").parents().find('.formwrapper').next().show();
        return false;
    }
   
    var check6 = $("#checker6").parent().attr('class');
    if(check6 == s && $.trim($("#notice").val())=='')
    {
        $("#checker1").parents().find('.formwrapper').next().html('请填写备注信息');
        $("#checker1").parents().find('.formwrapper').next().show();
        return false;
    }

    var checkuser = $("#checkuser").val();
    flag = checkRequired($("#checkuser"),1);
    if(!flag){
        console.log("dasd");
        return false;
    }

    $("#thisForm").submit();
})
</script>