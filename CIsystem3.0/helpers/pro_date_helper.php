<?php 
/**
 * 以now为基准，确定最近的时间间隔
 * $data['start_time']项目起始日期（Y-m-d H:i:s）
 * $data['cycle']项目周期：单位月（以30天为单位）
 * $data['settle_day']结算日期（d）
 */
function repayListDate($data)
{
    $data['end_time'] = date("Y-m-d H:i:s",strtotime($data['start_time'])+$data['cycle']*30*24*3600);
    $start_time = strtotime(date("Y-m-d",strtotime($data['start_time'])));
    $end_time = strtotime(date("Y-m-d",strtotime($data['end_time'])));
    $settle_day = $data['settle_day'];

    if(date("d",$start_time)<$settle_day)
    {
        $res[] = array(
            "calcu_start" => date("Y-m-d",$start_time),
            "calcu_end" => date("Y-m-",$start_time).$settle_day,
            );
        $date_cursor = $start_time;
        while($date_cursor < $end_time){
            $calcu_start = date("Y-m-",$date_cursor).$settle_day;
            $date_cursor = strtotime("+1 month",strtotime(date("Y-m-",$date_cursor).$settle_day));
            $calcu_end = date("Y-m-",$date_cursor).$settle_day;
            $res[] = array(
                "calcu_start" => $calcu_start,
                "calcu_end" => $calcu_end,
                );
        }
        $res[(sizeof($res)-1)]['calcu_end'] = date("Y-m-d",$end_time);
    }
    else
    {
        $res = array();
        $date_cursor = $start_time;
        while($date_cursor < $end_time){
            $calcu_start = date("Y-m-",$date_cursor).$settle_day;
            $date_cursor = strtotime("+1 month",strtotime(date("Y-m-",$date_cursor).$settle_day));
            $calcu_end = date("Y-m-",$date_cursor).$settle_day;
            $res[] = array(
                "calcu_start" => $calcu_start,
                "calcu_end" => $calcu_end,
                );
        }
        $res[0]['calcu_start'] = date("Y-m-d",$start_time);
        $res[(sizeof($res)-1)]['calcu_end'] = date("Y-m-d",$end_time);
    }
    
    foreach($res as $k => $v){
        $res[$k]['calcu_start_timestamp'] = strtotime($v['calcu_start']);
        $res[$k]['calcu_end_timestamp'] = strtotime($v['calcu_end']);
    }
    return $res;
}

?>
