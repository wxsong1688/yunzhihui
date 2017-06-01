<?php
/*
 * 项目结束之前一天结算最后的利息和本金
 * 每天查询需要清算的项目，并清算
 */
require_once __DIR__.'/base.class.php';

class rateSettlement extends base_class
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        $this->changeCronLock(1);//开启锁定，不允许发布项目及投资
        $this->addCronTime();//更新cron执行次数
        try{
            $this->mysql_conn->beginTransaction();
            /* 执行主体程序 */
            $this->create_yzh_item_settlement();
            $this->create_yzh_user_flow();
            $this->create_yzh_tenderee_flow();
            $this->getAllItems();
            /* 执行主体程序 */
            $this->changeCronLock(0);//关闭锁定
            $this->addCronTime(0);//重置cron执行次数
            $this->addCronLastDate();//执行成功写入执行日期
            $this->mysql_conn->commit();
        }catch(PDOException $ex){
            $this->mysql_conn->rollBack();
            $this->checkCronTime();//执行失败检查失败次数，超过三次发短信
            throw new PDOException($ex);
        }
    }

    /*
     * 先计算每天有没有项目最后一天，再计算每月
     */
    public function getAllItems()
    {
        $sql_get_all_item = 'select *,P.id as pro_id,I.id as item_id,I.create_time as i_c_time,P.create_time as p_c_time,P.status as p_status,I.credit_status as credit_status,I.credit_to_uid as credit_to_uid ';
        $sql_get_all_item.= ' from yzh_project_user as I left join yzh_project as P on I.pro_id=P.id';
        $sql_get_all_item.= ' where P.status in (10) and I.status in (1)';
        $result_all_item = $this->mysql_conn->query($sql_get_all_item)->fetchAll(PDO::FETCH_ASSOC);
        //如果今天已经是项目的最后一天，清算所有本金及利息
        foreach($result_all_item as $k => $v){
            $pro_end_time = date("Y-m-d",strtotime("+".($v['cycle']*30-1)." days",strtotime($v['full_time'])));
            if(date("Y-m-d",strtotime($this->now)) == date("Y-m-d",strtotime($pro_end_time))) {
                $params = array(
                    'uid' => $v['uid'],
                    'tenderee_id' => $v['tenderee_id'],
                    'pro_id' => $v['pro_id'],
                    'item_id' => $v['item_id'],
                    'amount' => $v['invest_sum'],
                    'year_rate_in' => $v['year_rate_in'],
                    'year_rate_out' => $v['year_rate_out'],
                    'full_time' => $v['full_time'],
                    'cycle' => $v['cycle'],
                    );
                $this->checkCredit($params);
                //将项目状态置为 清算完成25
                $this->changeProInfo(array('status' => 25,'pro_id' => $v['pro_id']));
                //更新project_user表 状态项目结束5 上次结算时间last_settle_time
                $this->changeItemInfo(array('status' => 5, 'pro_id' => $v['pro_id'],'item_id' => $v['item_id']));
                //债权转让表置为项目结束15
                $this->changeCreditInfo(array('status' => 15,'pro_id' => $v['pro_id'],'item_id' => $v['item_id']));
            }
        }

        //如果今天是结算日
        if(date("d",strtotime($this->now)) == $this->settle_day) {
            $result_all_item = $this->mysql_conn->query($sql_get_all_item)->fetchAll(PDO::FETCH_ASSOC);
            foreach($result_all_item as $k => $v){
                $params = array(
                    'uid' => $v['uid'],
                    'tenderee_id' => $v['tenderee_id'],
                    'pro_id' => $v['pro_id'],
                    'item_id' => $v['item_id'],
                    'amount' => $v['invest_sum'],
                    'year_rate_in' => $v['year_rate_in'],
                    'year_rate_out' => $v['year_rate_out'],
                    'full_time' => $v['full_time'],
                    'cycle' => $v['cycle'],
                    );
                $this->checkCredit($params);
                //更新project_user表上次结算时间last_settle_time
                $this->changeItemInfo(array('status' => 1,'pro_id' => $v['pro_id'],'item_id' => $v['item_id']));
            }
        }
    }

    /*
     * 检查是不是有债权转让的信息
     * 没有：单独执行项目结息
     * 有：结算所有经手过的用户的利息
     * $data['uid']
     * $data['tenderee_id']
     * $data['pro_id']
     * $data['item_id']
     * $data['amount']
     * $data['year_rate_in']
     * $data['year_rate_out']
     * $data['full_time']
     * $data['cycle']
     */
    public function checkCredit($data)
    {
        $sql_all_buyer = sprintf("select * from yzh_credit_record where pro_id=%d and item_id=%d",$data['pro_id'],$data['item_id']);
        $res_all_buyer = $this->mysql_conn->query($sql_all_buyer)->fetchAll(PDO::FETCH_ASSOC);
        //项目结束日期
        $pro_end_time = date("Y-m-d",strtotime("+".($data['cycle']*30-1)." days",strtotime($data['full_time'])));
        if(!isset($res_all_buyer[0])){//该条目没有债权转让过
            $time = $this->between(array("start_time" => $data['full_time'], "end_time" => $pro_end_time));
            $params = array(
                'uid' => $data['uid'],
                'tenderee_id' => $data['tenderee_id'],
                'pro_id' => $data['pro_id'],
                'item_id' => $data['item_id'],
                'start_time' => $time['start_time'],
                'end_time' => $time['end_time'],
                'amount' => $data['amount'],
                'year_rate_in' => $data['year_rate_in'],
                'year_rate_out' => $data['year_rate_out'],
                'pro_end_time' => $pro_end_time,
                );
            $this->calcu_settlement($params);
        }else{//该条目经过债权转让（一次或多次）
            $time = $this->between(array("start_time" => $data['full_time'], "end_time" => $res_all_buyer[0]['deal_time']));
            $params = array(
                'uid' => $data['uid'],
                'tenderee_id' => $data['tenderee_id'],
                'pro_id' => $data['pro_id'],
                'item_id' => $data['item_id'],
                'start_time' => $time['start_time'],
                'end_time' => $time['end_time'],
                'amount' => $data['amount'],
                'year_rate_in' => $data['year_rate_in'],
                'year_rate_out' => $data['year_rate_out'],
                'pro_end_time' => $pro_end_time,
                );
            $this->calcu_settlement($params);
            foreach($res_all_buyer as $k => $v){
                $end_time = isset($res_all_buyer[$k+1]) ? $res_all_buyer[$k+1]['deal_time'] : $pro_end_time;
                $time = $this->between(array("start_time" => $v['deal_time'], "end_time" => $end_time));
                $params = array(
                    'uid' => $v['to_uid'],
                    'tenderee_id' => $data['tenderee_id'],
                    'pro_id' => $data['pro_id'],
                    'item_id' => $data['item_id'],
                    'start_time' => $time['start_time'],
                    'end_time' => $time['end_time'],
                    'amount' => $data['amount'],
                    'year_rate_in' => $data['year_rate_in'],
                    'year_rate_out' => $data['year_rate_out'],
                    'pro_end_time' => $pro_end_time,
                );
                $this->calcu_settlement($params);
            }
        }
    }

    /*
     * 以now为基准，确定最近的时间间隔
     * $data['start_time']
     * $data['end_time']
     */
    public function between($data)
    {
        //代码来源：admin/application/helper/date_helper.php
        $start_time = strtotime(date("Y-m-d",strtotime($data['start_time'])));
        $end_time = strtotime(date("Y-m-d",strtotime($data['end_time'])));
        $now = $this->now;
        $settle_day = $this->settle_day;

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
var_dump($res);echo "\r\n------------------------\r\n";
        //根据当前时间计算需要返回的区间
        if(strtotime($now)<$res[0]['calcu_end_timestamp']){
            $current_res = $res[0];
        }else if(strtotime($now)>=$res[sizeof($res)-1]['calcu_end_timestamp']){
            $current_res = $res[sizeof($res)-1];
        }else{
            foreach($res as $k => $v){
                if($v['calcu_start_timestamp']<=strtotime($now) && strtotime($now)<$v['calcu_end_timestamp']){
                    $current_res = $res[$k-1];
                }
            }
        }
        $result = array(
            'start_time' => date("Y-m-d H:i:s",$current_res['calcu_start_timestamp']),
            'end_time' => date("Y-m-d H:i:s",$current_res['calcu_end_timestamp']),
            );
var_dump($result);
        return $result;
    }

    /*
     * 按照时间间隔计算还款首款金额，写入settlement表中
     * $data:below
     */
    // $data = array();
    // "uid" => ,
    // "tenderee_id" => ,
    // "pro_id" => ,
    // "item_id" => ,
    // "start_time" => "",
    // "end_time" => "",
    // "amount" => "",
    // "year_rate_in" => "",
    // "year_rate_out" => "",
    public function calcu_settlement($data)
    {
        //计算间隔天数
        $length = (strtotime(date("Y-m-d",strtotime($data['end_time']))) - strtotime(date("Y-m-d",strtotime($data['start_time'])))) / 3600 / 24;
        if(strtotime($data['end_time']) == strtotime($data['pro_end_time'])){//如果是最后一天，计算利息天数加一
            $length = $length + 1;
        }
var_dump($length);
        $settlement_gain = $data['amount'] * $data['year_rate_out']/100 * ($length/360);
        $settlement_pay = $data['amount'] * $data['year_rate_in']/100 * ($length/360);
        if(strtotime($data['end_time']) == strtotime($data['pro_end_time'])){//如果是最后一天，切是最后一个购买的用户，加入本金
            $settlement_gain = $settlement_gain + $data['amount'];
            $settlement_pay = $settlement_pay + $data['amount'];
        }
        $params = array(
            'uid' => $data['uid'],
            'tenderee_id' => $data['tenderee_id'],
            'pro_id' => $data['pro_id'],
            'item_id' => $data['item_id'],
            'settlement_gain' => $settlement_gain,
            'settlement_pay' => $settlement_pay,
            'create_time' => $this->now,
            );
        $sql_add_settlement = sprintf(
                'insert into yzh_item_settlement_%s set
                 uid=%d, 
                 tenderee_id=%d, 
                 pro_id=%d, 
                 item_id=%d, 
                 settlement_gain=%f, 
                 settlement_pay=%f, 
                 create_time="%s"', 
                 date("Ym",strtotime($this->now)), 
                 $params['uid'], 
                 $params['tenderee_id'], 
                 $params['pro_id'], 
                 $params['item_id'], 
                 $params['settlement_gain'], 
                 $params['settlement_pay'], 
                 $this->now
                 );
        /**
        如果今天小于15日，结束日期晚于上月结算日的过滤掉
        如果今天大于15日，结束日期晚于当月结算日的过滤掉
        */
        if(date("d",strtotime($this->now)) <= $this->settle_day && strtotime($data['end_time']) > strtotime(date("Y-m-",strtotime("-1 month",strtotime($this->now))).$this->settle_day)
        ||
        date("d",strtotime($this->now)) > $this->settle_day && strtotime($data['end_time']) > strtotime(date("Y-m-",strtotime($this->now)).$this->settle_day)
        ){
            $res_add_settlement = $this->mysql_conn->exec($sql_add_settlement);
            var_dump($sql_add_settlement);echo "\r\n=====================================================================================\r\n";
        }
    }

    /*
     * 修改project表信息
     * $params['status']:
     * $params['pro_id']:
     */
    public function changeProInfo($params)
    {
        $sql = sprintf("update yzh_project set status = %d where id = %d",
            $params['status'],
            $params['pro_id']
            );
        $res = $this->mysql_conn->exec($sql);
        return $res;
    }

    /*
     * 修改project_user表信息
     */
    public function changeItemInfo($params)
    {
        $sql = sprintf("update yzh_project_user set status = %d, last_settle_time = '%s' where pro_id = %d and id = %d",
            $params['status'],
            $this->now,
            $params['pro_id'],
            $params['item_id']
            );
        $res = $this->mysql_conn->exec($sql);
        return $res;
    }

    /*
     * 修改credit表信息
     */
    public function changeCreditInfo($params)
    {
        $sql = sprintf("update yzh_credit set status = %s where pro_id = %d and item_id = %d",
            $params['status'],
            $params['pro_id'],
            $params['item_id']
            );
        $res = $this->mysql_conn->exec($sql);
        return $res;
    }

}

$obj = new rateSettlement();
$obj->main();

?>
