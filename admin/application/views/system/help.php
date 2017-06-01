<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">系统管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>常见问题管理</h3>
    </div>
    <form class="stdform" action="editHelpInfo" method="get" id="thisForm" >
        <div id="formdiv">
        <?php foreach($lists as $k=>$v):?>
            <p class="sys_p">
                <span class="field" style="margin-left:80px;"><span></span>
                    问：<input type="text" name="asks[]" class="input asks_an"  value="<?php echo $v['ask'];?>" />&nbsp;&nbsp;
                    <?php if(in_array($userinfo['role'],array(1,2))):?>
                        <a href="javascript:;" class="table_edit del_syshelp" onclick="delSysHelp(this)">删除组</a>
                    <?php endif;?>
                </span>
                <span class="field" style="margin-left:80px;"><span></span>
                    答：<input type="text" name="answers[]" class="input asks_an"  value="<?php echo $v['answer'];?>" />
                </span>
                
            </p>
        <?php endforeach;?>
        </div>

        
        <?php if(in_array($userinfo['role'],array(1,2))):?>
            <p class="stdformbutton" style="margin-left: 290px;">
                <button class="submit radius2" id="submitForm">提交</button>
                <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
                <input type="reset" class="reset radius2 addnewSys" value="新增" onclick="javascript:;" />
            </p>
        <?php endif;?>
    </form>

</div>
</div>
<script type="text/javascript">
$(function(){
    var serrorinfo = '<small class="error" style="margin-left:102px;display:block;">问题或答案均不能为空</small>';
    $("#submitForm").click(function(){
        $(".error").hide();
        var flag = false;
        $(".asks_an").each(function(){
            var obj = $(this);
            if(obj.val() == ''){
                flag = true;
                obj.parent().parent().append(serrorinfo);
                return false;
            }
        })
        if(flag){
            return false;
        }
        $("#thisForm").submit();
    })

    
    $(".addnewSys").click(function(){
        $(".error").hide();
        var p = '<p class="sys_p">';
            p+= '<span class="field" style="margin-left:80px;">';
            p+= '问：<input type="text" name="asks[]" class="input asks_an" />&nbsp;&nbsp;';
            p+= '<a href="javascript:;" class="table_edit del_syshelp" onclick="delSysHelp(this)">删除组</a>';
            p+= '</span>';
            p+= '<span class="field" style="margin-left:80px;"><span></span>';
            p+= '答：<input type="text" name="answers[]" class="input asks_an"/>';
            p+= '</span>';
            p+= '</p>';
        
        $("#formdiv").append(p);
    })
})

function delSysHelp(obj)
{
    //剩下最后一个的时候，不能删除
    var leftp = document.getElementById("formdiv").childNodes.length;
    if(leftp <= 3 ){
        var s = '<small class="error" style="margin-left:102px;display:block;">问答信息不能为空</small>';
        obj.parentNode.parentNode.innerHTML += s;
        return false;
    }

    obj.parentNode.parentNode.remove();
}


</script>