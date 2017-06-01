<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">项目管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>指派初审</h3>
    </div>
    <form class="stdform" action="doAssigned" method="post" id="thisForm" enctype="multipart/form-data">
        <div class="lit_title"><h5>项目信息</h5></div>
        <p>
            <label>项目名称：</label>
            <span class="field">
                <?php echo $prod_info['pro_name'];?>
            </span>

            <label>融资金额：</label>
            <span class="field" >
                <span id="amount"><?php echo $prod_info['amount'];?></span>&nbsp;元
            </span>

            <label>项目周期：</label>
            <span class="field">
                <?php echo $prod_info['cycle']*30 ." 天";?>
            </span>

        <?php 
            $companyinfo   = unserialize($prod_info['companyinfo']);
            $projectinfo   = unserialize($prod_info['projectinfo']);
            $financierinfo = unserialize($prod_info['financierinfo']);
        ?>
            <label>用途：</label>
            <span class="field">
                <?php echo $projectinfo['proj_use'];?>
            </span>

            <label>融资照片：</label>
            <span class="field">
                <?php if(!empty($projectinfo['proj_rzpic'])):?>
                    <div id="imgShow" style="border: 1px solid #ccc; margin-left: 220px; margin-top: 20px;margin-right: 850px;margin-bottom: 20px;"><img height="60" width="50" src="<?php echo $projectinfo['proj_rzpic'];?>" id="showImg"></div>
                <?php else:?>
                    暂无
                <?php endif;?>
            </span>
        </p>

        <div class="lit_title"><h5>借款方信息</h5></div>
        <p>
            <label>姓名：</label>
            <span class="field">
                <?php echo !empty($financierinfo['financier_username']) ? $financierinfo['financier_username'] : '暂无';?>
            </span>

            <label>性别：</label>
            <span class="field"><?php echo $financierinfo['financier_sex']==1?'男':'女';?></span>

            <label>年龄：</label>
            <span class="field">
                <?php echo $financierinfo['financier_year'];?>
            </span>

            <label>婚姻状况：</label>
            <span class="field">
                <?php 
                    switch ($financierinfo['financier_mar']) {
                        case 1:
                            echo "已婚";
                            break;
                        case 2:
                            echo "未婚";
                            break;
                        default:
                            echo "其他";
                            break;
                    }
                ?>
            </span>

            <label>企业行业：</label>
            <span class="field">
                <?php echo $companyinfo['comp_industry'];?>
            </span>

            <label>企业规模：</label>
            <span class="field">
                <?php echo $companyinfo['comp_scale'];?>
            </span>

            <label>抵押担保方式：</label>
            <span class="field">
                <?php echo $companyinfo['comp_guarantee'];?>
            </span>

            <label>借款项目详述：</label>
            <span class="field">
                <?php echo $projectinfo['proj_desc'];?>
            </span>
        </p>
        指派初审：
        <select class="radius3" type="cycle" name="uid" id="uid">
            <option value="0">请选择</option>
            <?php foreach($chushen as $k=>$v):?>
                <option value="<?php echo $v['uid'];?>"><?php echo $v['username'];?></option>
            <?php endforeach;?>
        </select>
        <input type="hidden" value="<?php echo $prod_info['id'];?>" name="pid" id="pid">
        <button class="radius3" id="submitForm">确定指派</button>
        </div>
    </form>
    </div>
<script type="text/javascript">
/*$("#submitForm").click(function(){
    alert($("#pid").val()+"==="+$("#uid").val());
    $.ajax({
        type: "GET",
        url: "/Project/doAssigned",
        data: {pid:$("#pid").val(),uid:$("#uid").val()},
        dataType: "json",
        success: function(data){
                alert(data);return;
        }
    });
});*/
window.onload=function ()
{
    //项目详情
    var amountvalue = $("#amount");
    var avalue = tran(amountvalue.html());
    amountvalue.html(avalue);
}
</script>