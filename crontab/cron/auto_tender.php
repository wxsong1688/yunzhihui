<?php
/*
 * 自动投标
 * 每天执行
 */
require_once __DIR__.'/base.class.php';
require_once dirname(dirname(__DIR__))."/CIsystem3.0/libraries/Public/ChinapnrClass.php";

class rateCalcuDay extends base_class
{
    public $chinaPnr;
    
    public function __construct()
    {
        parent::__construct();
        $this->chinaPnr = new CI_ChinapnrClass();
    }

    public function main()
    {
        $this->changeCronLock(1);//开启锁定，不允许发布项目及投资
        $this->addCronTime();//更新cron执行次数
        try{
            $this->mysql_conn->beginTransaction();

            /* 执行主体程序 */
            $this->getAutoUser();
            /* 执行主体程序 */

            $this->changeCronLock(0);//关闭锁定
            $this->addCronTime(0);//重置cron执行次数
            $this->mysql_conn->commit();
        }catch(PDOException $ex){
            $this->mysql_conn->rollBack();
            throw new PDOException($ex);
        }
    }

    public function getAutoUser()
    {
        //获取开启自动投标的用户
        $sql_get = "select * from yzh_user_account as A left join yzh_user as U on A.uid=U.uid where auto_tender=1";
        $autoUser = $this->mysql_conn->query($sql_get)->fetchAll(PDO::FETCH_ASSOC);
        foreach($autoUser as $k => $v){
            if($v['auto_type'] == 0){//自动投标 类型选项为 全部
                $type = "1,2,3,4";
            }else{
                $type = $v['auto_type'];
            }

            if($v['auto_circle'] == 0){//自动投标 周期选项为 全部
                $cycle = "1,3,6,9,12";
            }else{
                $cycle = $v['auto_circle'];
            }
            $sql_pro = "select * from yzh_project where status=5 and type in ('".$type."') and cycle in ('".$cycle."')";
            $autoPro = $this->mysql_conn->query($sql_pro)->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($autoPro)){//存在符合条件的项目
                foreach($autoPro as $kp => $vp){
                    if($v['auto_amount'] >= $vp['remain_amount']){//用户自动投标金额大于项目剩余金额
                        $invest_amount = $vp['remain_amount'];
                    }else{
                        $invest_amount = $v['auto_amount'];
                    }
                }
                //$autoTender = $this->chinaPnr->autoTenderPlan($this->chinaPnr->yzhCustId,$v['hf_userCustId'],"P",1000);
            }
        }
        
        // print_R($autoUser);exit;
    }



}

$obj = new rateCalcuDay();
$obj->main();

?>
