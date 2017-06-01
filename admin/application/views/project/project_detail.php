<div class="centercontent">
<div class="pageheader">
    <h1 class="pagetitle">项目管理</h1>
    <span class="pagedesc"></span>
</div><!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="basicform" class="subcontent">
    <div class="contenttitle2">
        <h3>项目详情</h3>
    </div>
    <form class="stdform" action="editProjectData" method="post" id="thisForm" enctype="multipart/form-data">
        <div class="lit_title"><h5>项目信息</h5></div>
        <p>
            <label>项目名称：</label>
            <span class="field">
                <?php echo $prod_info['pro_name'];?>
            </span>
        </p>
        <p>
            <label>融资金额：</label>
            <span class="field" >
                <span id="amount"><?php echo $prod_info['amount'];?></span>&nbsp;元
            </span>
        </p>
        <p>
            <label>项目周期</label>
            <span class="field">
                <?php echo $prod_info['cycle']*30 ." 天";?>
            </span>
        </p>
        <?php 
            $companyinfo   = unserialize($prod_info['companyinfo']);
            $projectinfo   = unserialize($prod_info['projectinfo']);
            $financierinfo = unserialize($prod_info['financierinfo']);
        ?>
        <p>
            <label>用途：</label>
            <span class="field">
                <?php echo $projectinfo['proj_use'];?>
            </span>
        </p>
        <p>
        <p>
            <label>融资照片</label>
            <span class="field">
                <?php if(!empty($projectinfo['proj_rzpic'])):?>
                    <!-- <div class="photo">
                        <a href="javascript:;" class="cboxElement"><img src="<?php echo $projectinfo['proj_rzpic'];?>" alt="" style="opacity: 1;"></a>
                    </div> -->
                    <div id="imgShow" style="border: 1px solid #ccc; margin-left: 220px; margin-top: 20px;margin-right: 250px;margin-bottom: 20px;"><img height="200" width="200" src="<?php echo $projectinfo['proj_rzpic'];?>" id="showImg"></div>
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
        </p>
        <p>
            <label>性别：</label>
            <span class="field"><?php echo $financierinfo['financier_sex']==1?'男':'女';?></span>
        </p>
        <p>
            <label>年龄：</label>
            <span class="field">
                <?php echo $financierinfo['financier_year'];?>
            </span>
        </p>
        <p>
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
        </p>
        <p>
            <label>企业行业：</label>
            <span class="field">
                <?php echo $companyinfo['comp_industry'];?>
            </span>
        </p>
        <p>
            <label>企业规模：</label>
            <span class="field">
                <?php echo $companyinfo['comp_scale'];?>
            </span>
        </p>
        <p>
            <label>抵押担保方式：</label>
            <span class="field">
                <?php echo $companyinfo['comp_guarantee'];?>
            </span>
        </p>
        <p>
            <label>借款项目详述：</label>
            <span class="field">
                <?php echo $projectinfo['proj_desc'];?>
            </span>
        </p>
        </div>
    </form>
    </div>
<script type="text/javascript">
window.onload=function ()
{
    //项目详情
    var amountvalue = $("#amount");
    var avalue = tran(amountvalue.html());
    amountvalue.html(avalue);

}
</script>