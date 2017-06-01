<?php
/*
 * 更新每日利息
 * 每天执行
 */
require_once __DIR__.'/base.class.php';

class rateCalcuDay extends base_class
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
            $this->calcuDayGain();//计算每天的利息
            $this->updateUserAccount();//更新每个用户的当天收益
            /* 执行主体程序 */
            $this->changeCronLock(0);//关闭锁定
            $this->addCronTime(0);//重置cron执行次数
            $this->mysql_conn->commit();
        }catch(PDOException $ex){
            $this->mysql_conn->rollBack();
            throw new PDOException($ex);
        }
    }

    public function calcuDayGain()
    {
        $sql_get_all_item = 'select *,I.id as item_id,I.create_time as i_c_time,P.create_time as p_c_time,P.status as p_status,I.credit_status as credit_status,I.credit_to_uid as credit_to_uid ';
        $sql_get_all_item.= ' from yzh_project_user as I left join yzh_project as P on I.pro_id=P.id';
        $sql_get_all_item.= ' where P.status in (10) and I.status in (1)';
        $result_all_item = $this->mysql_conn->query($sql_get_all_item)->fetchAll(PDO::FETCH_ASSOC);
        foreach($result_all_item as $k => $v){
            $day_rate_gain = $v['year_rate_out']/360/100;//每日收益率为年化收益（对投资人）除以360天
            $curr_day_gain = $v['invest_sum']*$day_rate_gain;//投资人每日收益
            $day_rate_pay = $v['year_rate_in']/360/100;//每日收益率为年化收益（对融资人）除以360天
            $curr_day_pay = $v['invest_sum']*$day_rate_pay;//融资人每日应付金额
            $data = array(
                    'uid' => $v['uid'],
                    'tenderee_id' => $v['tenderee_id'],
                    'pro_id' => $v['pro_id'],
                    'item_id' => $v['item_id'],
                    'gain_curr_day' => $curr_day_gain,
                    'pay_curr_day' => $curr_day_pay,
                    'p_status' => $v['p_status'],
                    'credit_status' => $v['credit_status'],
                    'credit_to_uid' => $v['credit_to_uid'],
                );
            $this->updateProUser($data);
            $this->insertRecord($data);
        }
    }

    /*
     * 更新project_user表中的每日收益和总收益
     */
    public function updateProUser($data)
    {
        $sql_upd_item = sprintf(
            'update yzh_project_user set
             gain_curr_day=%f,
             pay_curr_day=%f 
             where id=%d', 
             $data['gain_curr_day'], 
             $data['pay_curr_day'], 
             $data['item_id']
            );
        $this->mysql_conn->exec($sql_upd_item);
    }

    /*
     * 新增结息记录
     */
    public function insertRecord($data)
    {
        if($data['credit_status'] == 10 && $data['credit_to_uid'] != 0){//已经债权转让成交
            $sql_ins_record = sprintf(
                'insert into yzh_item_record_%s set 
                 uid=%d, 
                 tenderee_id=%d, 
                 pro_id=%d, 
                 item_id=%d, 
                 gain_money=%f, 
                 pay_money=%f, 
                 platform_pay=0,
                 pay_man=2, 
                 calc_time="%s"',
                 date("Ym"), 
                 $data['credit_to_uid'], 
                 $data['tenderee_id'], 
                 $data['pro_id'], 
                 $data['item_id'], 
                 $data['gain_curr_day'], 
                 $data['pay_curr_day'], 
                 date("Y-m-d H:i:s")
                 );
        }else{//没有债权转让，利息付给购买人
            $sql_ins_record = sprintf(
                'insert into yzh_item_record_%s set 
                 uid=%d, 
                 tenderee_id=%d, 
                 pro_id=%d, 
                 item_id=%d, 
                 gain_money=%f, 
                 pay_money=%f, 
                 platform_pay=0,
                 pay_man=2, 
                 calc_time="%s"',
                 date("Ym"), 
                 $data['uid'], 
                 $data['tenderee_id'], 
                 $data['pro_id'], 
                 $data['item_id'], 
                 $data['gain_curr_day'], 
                 $data['pay_curr_day'], 
                 date("Y-m-d H:i:s")
                 );
        }
        $this->mysql_conn->exec($sql_ins_record);
    }

    /*
     * 更新投资人账户表中当天应获收益
     */
    public function updateUserAccount()
    {
        $sql_get_sum = sprintf('select sum(gain_money),uid from yzh_item_record_%s group by uid', date("Ym"));
        $result_user_sum = $this->mysql_conn->query($sql_get_sum)->fetchAll(PDO::FETCH_ASSOC);
        foreach($result_user_sum as $k => $v){
            $sql_upd_user_account = sprintf('update yzh_user_account set gain_curr_day=%f where uid=%d', $v['sum(gain_money)'], $v['uid']);
            $result_upd = $this->mysql_conn->exec($sql_upd_user_account);
        }
    }

}

$obj = new rateCalcuDay();
$obj->main();

?>
