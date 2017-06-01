<?php
/*
 * 创建每个年的新表
 * 每年执行
 */
require_once __DIR__.'/base.class.php';

class createTableYear extends base_class
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
            $this->create_yzh_user_flow();
            $this->create_yzh_tenderee_flow();
            /* 执行主体程序 */
            $this->changeCronLock(0);//关闭锁定
            $this->addCronTime(0);//重置cron执行次数
            $this->mysql_conn->commit();
        }catch(PDOException $ex){
            $this->mysql_conn->rollBack();
            throw new PDOException($ex);
        }
    }
    
    public function create_yzh_user_flow()
    {
        $sql = sprintf("CREATE TABLE IF NOT EXISTS `yzh_user_flow_%s` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
            `pro_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '项目id',
            `pro_name` varchar(255) NOT NULL DEFAULT '' COMMENT '项目名称',
            `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '类型 1：充值，2：提现，3：充值手续费，4：提现手续费，5：投标冻结，6：投标成功，7：回款利息，8：回款本息，9：债权转让，10：购买债权',
            `amount` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '金额',
            `remaining_amount` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '账户余额',
            `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（1：资金未到账，5，资金已到账）（待定）',
            `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
            `comment` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
            `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '汇付交易号',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户（投资人）流水表'"
            ,
            date("Y")
        );
        $res = $this->mysql_conn->exec($sql);var_dump($sql);var_dump($res);
    }

    public function create_yzh_tenderee_flow()
    {
        $sql = sprintf("CREATE TABLE IF NOT EXISTS `yzh_tenderee_flow_%s` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
            `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '类型 1：充值，2：提现，5：投标，6：回款本息，7：回款利息，49：其他',
            `amount` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '金额',
            `status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态（1：资金未成功转账，5，资金已成功转账）',
            `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
            `comment` varchar(1000) NOT NULL COMMENT '备注',
            `order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '订单ID',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户（融资人）流水表'"
            ,
            date("Y")
        );
        $res = $this->mysql_conn->exec($sql);var_dump($sql);var_dump($res);
    }

}

$obj = new createTableYear();
$obj->main();

?>
