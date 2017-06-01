<style type="text/css">
    #leftFrame, #rightFrame {
        float: left;
        min-height: 693px;
    }
    #rightFrame {
        position: relative;
        /*z-index: 1200;*/
    }
    .userMain {
        width: 100%;
        background-color: #F9F9F9;
		padding-bottom: 50px;
    }
    .userMain-section {
        width: 1200px;
    }
    .imsec2-ul-out {
        background-color: #000;
        height: 100%;
        left: 0;
        filter:alpha(opacity=30);       /* IE */
        -moz-opacity:0.3;              /* 老版Mozilla */
        -khtml-opacity:0.3;              /* 老版Safari */
        opacity: 0.3;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }
</style>

<div class="invest-madal display-none">
    <div class="invest-modal-sec1">
        <p class="float-left invest-modal-txt1 invest-modal-sec1-title">全部回款计划</p>
        <p class="float-right invest-modal-sec1-close" id ="investClose" onclick="$('.invest-madal').hide();$('.imsec2-ul-out').hide();"><img src="public/images/closeS.png"></p>
        <div class="clear"></div>
    </div>
    <div class="invest-madal-sec2">
        <table id="table_gray" class="table_gray">
            <thead>
            <tr>
                <th>到期回款日</th>
                <th>应收本金</th>
                <th>应收利息</th>
                <!-- <th>逾期违约金</th> -->
                <th>应缴服务费</th>
                <!-- <th>应收回款</th> -->
                <th>回款总额</th>
                <th>回款状态</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="aui_buttons"><button class="aui_state_highlight" type="button" onclick="$('.invest-madal').hide();$('.imsec2-ul-out').hide();">确定</button></div>
</div>
<div class="userMain">
    <div class="imsec2-ul-out display-none"></div>
    <div class="userMain-section show-center">
        <iframe src="/usercenter/userNav?navId=<?php echo $navId;?>" width="256" id="leftFrame" name="leftFrame" scrolling="no" frameborder="0"></iframe>
        <iframe src="/usercenter/userInformation?certific=<?php echo !empty($checkRes)?$checkRes:'';?>" width="944" id="rightFrame" name="rightFrame" scrolling="no" frameborder="0"></iframe>
        <div class="clear"></div>
    </div>
</div>
<!--footer start-->
<?php $this->load->helper('footer');?>
<!--footer end-->
