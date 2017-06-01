<?php
/*
 * 计算每个结息周期的利息
 * 每个结息周期执行
 */
require_once __DIR__.'/base.class.php';

class rateSettleMonth extends base_class
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
            $this->calcu_month_gain();
            /* 执行主体程序 */
            $this->changeCronLock(0);//关闭锁定
            $this->addCronTime(0);//重置cron执行次数
            $this->mysql_conn->commit();
        }catch(PDOException $ex){
            $this->mysql_conn->rollBack();
            throw new PDOException($ex);
        }
    }

    public function calcu_month_gain()
    {
        $sql_get_all_item = 'select *,I.id as item_id,I.create_time as i_c_time,P.create_time as p_c_time,P.status as p_status,I.credit_status as credit_status,I.credit_to_uid as credit_to_uid ';
        $sql_get_all_item.= ' from yzh_project_user as I left join yzh_project as P on I.pro_id=P.id';
        $sql_get_all_item.= ' where P.status in (10) and I.status in (1)';
        $result_all_item = $this->mysql_conn->query($sql_get_all_item)->fetchAll(PDO::FETCH_ASSOC);
        $sql_table = "show tables like 'yzh_item_record_".date("Ym",strtotime("-1 month"))."'";
        $result_table = $this->mysql_conn->query($sql_table)->fetchAll(PDO::FETCH_ASSOC);
        $this->last_exist = !empty($result_table) ? 1 : 0 ;//判断上个月的日计息表（item_record）是否存在
        foreach($result_all_item as $k => $v){
            if($v['p_status'] == 5){//平台计息，payman参数为1
                $this->sumAllMonth($v,1);
            }elseif($v['p_status'] == 10){//融资方计息，payman参数为2
                $this->sumAllMonth($v,2);
            }
        }
    }

    /*
     * 把record中的每天的记录加总计入settlement表
     * $item:project_user表中的单挑记录
     * $pay_man:付款方
     */
    public function sumAllMonth($item,$pay_man)
    {
        $gain_last_month = 0;
        $pay_last_month = 0;
        if($this->last_exist == 1){//获取上个月的所有利息
            $sql_get_record_by_uid_proid_last = sprintf(
                'select sum(gain_money),sum(pay_money),sum(platform_pay),uid,tenderee_id,pro_id,item_id 
                 from yzh_item_record_%s 
                 where 1 
                 and uid=%d 
                 and tenderee_id=%d 
                 and pro_id=%d 
                 and item_id=%d 
                 and pay_man=%d', 
                 date("Ym",strtotime("-1 month")), 
                 $item['uid'], 
                 $item['tenderee_id'], 
                 $item['pro_id'], 
                 $item['item_id'],
                 $pay_man
                );
            $result_record_last = $this->mysql_conn->query($sql_get_record_by_uid_proid_last)->fetchAll(PDO::FETCH_ASSOC);
            $gain_last_month = $result_record_last[0]['sum(gain_money)'];
            $pay_last_month = $result_record_last[0]['sum(pay_money)'];
        }

        $sql_get_record_by_uid_proid_curr = sprintf(
            'select sum(gain_money),sum(pay_money),uid,tenderee_id,pro_id,item_id 
             from yzh_item_record_%s 
             where 1 
             and uid=%d 
             and tenderee_id=%d 
             and pro_id=%d 
             and item_id=%d
             and pay_man=%d', 
             date("Ym"), 
             $item['uid'], 
             $item['tenderee_id'], 
             $item['pro_id'], 
             $item['item_id'],
             $pay_man
            );
        $result_record_curr = $this->mysql_conn->query($sql_get_record_by_uid_proid_curr)->fetchAll(PDO::FETCH_ASSOC);
        $gain_curr_month = $result_record_curr[0]['sum(gain_money)'];
        $pay_curr_month = $result_record_curr[0]['sum(pay_money)'];
        $gain_cycle = $gain_last_month+$gain_curr_month;
        $pay_cycle = $pay_last_month+$pay_curr_month;
        $data = array(
                'gain_cycle' => $gain_cycle,
                'pay_cycle' => $pay_cycle,
                'uid' => $item['uid'],
                'tenderee_id' => $item['tenderee_id'],
                'pro_id' => $item['pro_id'],
                'item_id' => $item['item_id'],
                'pay_man' => $pay_man,
            );
        $this->addSettlement($data);
    }

    /*
     * 将每月数据添加到item_settlement表
     */
    public function addSettlement($data)
    {
        if($data['pay_man'] == 1){//平台计息
            $sql = sprintf(
                'insert into yzh_item_settlement_%s set
                 uid=%d, 
                 tenderee_id=%d, 
                 pro_id=%d, 
                 item_id=%d, 
                 settlement_gain=%f, 
                 platform_pay=%f, 
                 pay_man=%d,
                 create_time="%s"', 
                 date("Ym"), 
                 $data['uid'], 
                 $data['tenderee_id'], 
                 $data['pro_id'], 
                 $data['item_id'], 
                 $data['gain_cycle'], 
                 $data['pay_cycle'], 
                 $data['pay_man'],
                 date("Y-m-d H:i:s")
            );
        }elseif($data['pay_man'] == 2){//融资方计息
            $sql = sprintf(
                'insert into yzh_item_settlement_%s set
                 uid=%d, 
                 tenderee_id=%d, 
                 pro_id=%d, 
                 item_id=%d, 
                 settlement_gain=%f, 
                 settlement_pay=%f, 
                 pay_man=%d,
                 create_time="%s"', 
                 date("Ym"), 
                 $data['uid'], 
                 $data['tenderee_id'], 
                 $data['pro_id'], 
                 $data['item_id'], 
                 $data['gain_cycle'], 
                 $data['pay_cycle'], 
                 $data['pay_man'],
                 date("Y-m-d H:i:s")
            );
        }
        $this->mysql_conn->exec($sql);
    }

    /*
     * 将每月数据添加到user_flow表
     */
    public function addUserFlow($data)
    {
        $sql = sprintf('insert into yzh_user_flow_%s set uid=%d, pro_id=%d, pro_name=%s, type=7, amount=%f, status=1, create_time="%s"', date("Y"), $data['uid'], $data['pro_id'], $data['pro_name'], $data['gain_cycle'], date("Y-m-d H:i:s"));
        $this->mysql_conn->exec($sql);
    }

    /*
     * 将每月数据添加到tenderee_flow表
     */
    public function addtendereeFlow($data)
    {
        $sql = sprintf('insert into yzh_tenderee_flow_%s set uid=%d, pro_id=%d, pro_name=%s, type=7, amount=%f, status=1, create_time="%s"', date("Y"), $data['uid'], $data['pro_id'], $data['pro_name'], $data['gain_cycle'], date("Y-m-d H:i:s"));
        $this->mysql_conn->exec($sql);
    }

    /*
     * 将每月数据更新到user_account表
     */
    public function updUserAccount($data)
    {
        $sql = sprintf('update yzh_user_account set money=money+%f, income=income+%f, withdrawal_cash=withdrawal_cash+%f, gain_total=gain_total+%f where uid=%d', $data['gain_cycle'], $data['gain_cycle'], $data['gain_cycle'], $data['gain_cycle'], $data['uid']);
        $this->mysql_conn->exec($sql);
    }

    /*
     * 将每月数据更新到tenderee_account表
     */
    public function updTendereeAccount()
    {
        $sql = sprintf('update yzh_tenderee_account set money=money+%f, income=income+%f, withdrawal_cash=withdrawal_cash+%f, gain_total=gain_total+%f where uid=%d', $data['gain_cycle'], $data['gain_cycle'], $data['gain_cycle'], $data['gain_cycle'], $data['uid']);
        $this->mysql_conn->exec($sql);
    }

    /*
     * 将每月数据更新到project_user表
     * gain_total,last_settle_time字段
     */
    public function updProjectUser($data)
    {
        $sql = sprintf('update yzh_project_user set last_settle_time');
    }

}

$obj = new rateSettleMonth();
$obj->main();

?>
