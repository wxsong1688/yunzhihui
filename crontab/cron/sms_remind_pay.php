<?php
/*
 * 10点，14点，16：30结束周期和中间周期都要做，检查融资人余额，是否自动还款，自动还款切余额足够则直接还款，否则发短信提示
 * 每天定时执行
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
            
            /* 执行主体程序 */
            $this->changeCronLock(0);//关闭锁定
            $this->addCronTime(0);//重置cron执行次数
            $this->mysql_conn->commit();
        }catch(PDOException $ex){
            $this->mysql_conn->rollBack();
            throw new PDOException($ex);
        }
    }

	public function sendSms()
    {
        
    }

}
?>
