<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">用户管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>等级管理</h3>
    </div>
    <form class="stdform" action="editLevel" method="get" id="thisForm" >
        <p>
            <label><font color="red">*</font>&nbsp;一级</label>
            <span class="field">
                下限：<input type="text" id="level1_min" name="level1_min" class="smallinput"  value="<?php echo $lists[0]['min_money'];?>" style="background:#ccc;" readonly="readonly" />&nbsp;&nbsp;
                上限：<input type="text" id="level1_max" name="level1_max" class="smallinput"  value="<?php echo $lists[0]['max_money'];?>" onblur="dealLevel(1)"/>
            </span>
            <small class="error" style="margin-left:260px;"></small>
        </p>
        <p>
            <label></label>
            <span class="field">
                名称：<input type="text" name="level1_name" class="smallinput"  value="<?php echo $lists[0]['level_name'];?>" />&nbsp;&nbsp;
            </span>
            <small class="desc" style="margin-left:260px;">请填写一级的等级命名，可以为空</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;二级</label>
            <span class="field">
                下限：<input type="text" id="level2_min" name="level2_min" class="smallinput" style="background:#ccc;" readonly="readonly" value="<?php echo $lists[1]['min_money'];?>"/>&nbsp;&nbsp;
                上限：<input type="text" id="level2_max" name="level2_max" class="smallinput" value="<?php echo $lists[1]['max_money'];?>" onblur="dealLevel(2)"/>
            </span>
            <small class="error" style="margin-left:260px;"></small>
        </p>
        <p>
            <label></label>
            <span class="field">
                名称：<input type="text" name="level2_name" class="smallinput"  value="<?php echo $lists[1]['level_name'];?>" />&nbsp;&nbsp;
            </span>
            <small class="desc" style="margin-left:260px;">请填写二级的等级命名，可以为空</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;三级</label>
            <span class="field">
                下限：<input type="text" id="level3_min" name="level3_min" class="smallinput" style="background:#ccc;" readonly="readonly" value="<?php echo $lists[2]['min_money'];?>"/>&nbsp;&nbsp;
                上限：<input type="text" id="level3_max" name="level3_max" class="smallinput" value="<?php echo $lists[2]['max_money'];?>" onblur="dealLevel(3)"/>
            </span>
            <small class="error" style="margin-left:260px;"></small>
        </p>
        <p>
            <label></label>
            <span class="field">
                名称：<input type="text" name="level3_name" class="smallinput"  value="<?php echo $lists[2]['level_name'];?>" />&nbsp;&nbsp;
            </span>
            <small class="desc" style="margin-left:260px;">请填写三级的等级命名，可以为空</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;四级</label>
            <span class="field">
                下限：<input type="text" value="<?php echo $lists[3]['min_money'];?>" id="level4_min" name="level4_min" class="smallinput" style="background:#ccc;" readonly="readonly" />&nbsp;&nbsp;
                上限：<input type="text" value="<?php echo $lists[3]['max_money'];?>" id="level4_max" name="level4_max" class="smallinput" onblur="dealLevel(4)"/>
            </span>
            <small class="error" style="margin-left:260px;"></small>
        </p>
        <p>
            <label></label>
            <span class="field">
                名称：<input type="text" name="level4_name" class="smallinput"  value="<?php echo $lists[3]['level_name'];?>" />&nbsp;&nbsp;
            </span>
            <small class="desc" style="margin-left:260px;">请填写四级的等级命名，可以为空</small>
        </p>
        <p>
            <label><font color="red">*</font>&nbsp;五级</label>
            <span class="field">
                下限：<input type="text" value="<?php echo $lists[4]['min_money'];?>" id="level5_min" name="level5_min" style="background:#ccc;" readonly="readonly" class="smallinput" />&nbsp;&nbsp;
                上限：<input type="text" value="<?php echo $lists[4]['max_money'];?>" id="level5_max" name="level5_max" class="smallinput" onblur="dealLevel(5)"/>
            </span>
            <small class="error" style="margin-left:260px;"></small>
        </p>
        <p>
            <label></label>
            <span class="field">
                名称：<input type="text" name="level5_name" class="smallinput"  value="<?php echo $lists[4]['level_name'];?>" />&nbsp;&nbsp;
            </span>
            <small class="desc" style="margin-left:260px;">请填写五级的等级命名，可以为空</small>
        </p>
        <?php if(in_array($userinfo['role'],array(1,2))):?>
            <p class="stdformbutton" style="margin-left: 290px;">
                <button class="submit radius2" id="submitForm">提交</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
            </p>
        <?php endif;?>
    </form>
</div>
</div>
<script type="text/javascript">
function dealLevel(level)
{
    var obj_min = $("#level"+level+"_min");
    var obj_max = $("#level"+level+"_max");
    var obj_next_min = $("#level"+parseInt(level+1)+"_min");
    if(parseInt($(obj_max).val()) <= parseInt($(obj_min).val())){
        $(obj_max).parent().next().show().html('本级下限不能大于等于上限！');
        $(obj_max).val('') ;
    }else{
        $(obj_max).parent().next().hide();
        if(level != 5){
            $(obj_next_min).val($(obj_max).val());  
        }
    }
}

$(function(){
    $("#submitForm").click(function(){
        var i;
        for(i=1;i<=5;i++){
            $("#level"+i+"_min").parent().next().hide();
            if(parseInt($("#level"+i+"_min").val()) >= parseInt($("#level"+i+"_max").val())){
                $("#level"+i+"_min").parent().next().show().html('本级下限不能大于等于上限！');
                return false;
            }
            if($("#level"+i+"_min").val() == '' || $("#level"+i+"_max").val()==''){
                $("#level"+i+"_min").parent().next().show().html('第'+i+"级上下级均不能为空");
                return false;
            }
        }
        $("#thisForm").submit();
    })
})


</script>