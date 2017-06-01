<div class="dataTables_info" >总数：<?php echo isset($total_count)?"共 ".$total_count." 条记录":0;?></div>
<div class="dataTables_paginate paging_full_numbers" id="dyntable2_paginate">
    <?php if($pageinfo['page'] > 1):?>
        <span class="first paginate_button" id="dyntable2_first" gotopage="1">第一页</span>
        <span class="previous paginate_button" id="dyntable2_previous" gotopage="<?php echo $pageinfo['page']-1;?>">上一页</span>
    <?php endif;?>
    &nbsp;&nbsp;&nbsp;
    <?php if(intval($pageinfo['total_page']) > intval($pageinfo['page']) ):?> 
        <span class="next paginate_button" id="dyntable2_next" gotopage="<?php echo $pageinfo['page']+1;?>">下一页</span>
        <span class="last paginate_button" id="dyntable2_last" gotopage="<?php echo $pageinfo['total_page'];?>">最后一页</span>
    <?php endif;?>
</div>