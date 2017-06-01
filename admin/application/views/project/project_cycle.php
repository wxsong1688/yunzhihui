<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">项目管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>项目周期管理</h3>
    </div>
    <form class="stdform" action="/Project/editCycle" method="get" id="thisForm" style="margin-left: 260px;">
    <div class="lit_title" style="margin-left: -260px;"><h5>请选择发布项目的周期选项：</h5></div>
    <p>        
        <span class="formwrapper">
            <div class="checker">
                <span <?php if($lists[0]['ifshow']==1):?>class="checked"<?php endif;?>><input class="checkbox" type="checkbox" name="checker1" ></span>
                <input type="hidden" name="1" >
            </div>一个月（30天）<br>
            <div class="checker">
                <span <?php if($lists[1]['ifshow']==1):?>class="checked"<?php endif;?>><input class="checkbox" type="checkbox" name="checker2" ></span>
                <input type="hidden" name="3" >
            </div>三个月（90天）<br>
            <div class="checker">
                <span <?php if($lists[2]['ifshow']==1):?>class="checked"<?php endif;?>><input type="checkbox" class="checkbox" name="checker3" ></span>
                <input type="hidden" name="6" >
            </div>六个月（180天）<br>
            <div class="checker">
                <span <?php if($lists[3]['ifshow']==1):?>class="checked"<?php endif;?>><input type="checkbox" class="checkbox" name="checker4" ></span>
                <input type="hidden" name="9" >
            </div>九个月（270天）<br>
            <div class="checker">
                <span <?php if($lists[4]['ifshow']==1):?>class="checked"<?php endif;?>><input type="checkbox" class="checkbox" name="checker5" ></span>
                <input type="hidden" name="12" >
            </div>十二个月（360天）<br>
        </span>
    </p>
    <small class="error" style="margin-left: 10px;">请至少选择一个周期选项</small>
    <?php if(in_array($userinfo['role'],array(1,2))):?>
        <p class="stdformbutton" style="margin-left: 1px;">
            <button class="submit radius2" id="submitForm">提交</button>
            <input type="reset" class="reset radius2" value="取消" onclick="location.reload()" />
        </p>
    <?php endif;?>
    </form>
</div>
</div>
<script type="text/javascript">
$("#submitForm").click(function(){
    $(".error").hide();
    var flag = false;
    $(".checkbox").each(function(){
        if($(this).parent().attr('class') == 'checked'){
            $(this).parent().next().val(1);
            flag = true;
        }
    })
    if(flag == false){
        $(".error").show();
        return false;
    }
    $("#thisForm").submit();
})

$(".checkbox").click(function(){
    var checked = $(this).parent().attr('class');
    if(checked == "checked"){
        $(this).parent().removeClass('checked');
    }else{
        $(this).parent().addClass('checked');
    }
})

</script>