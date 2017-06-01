<?php
/*
 * 创建每个月的新表
 * 每月执行
 */
require_once __DIR__.'/base.class.php';

class createTableMonth extends base_class
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
            // $this->create_yzh_item_record();
            $this->create_yzh_item_settlement();
            /* 执行主体程序 */
            $this->changeCronLock(0);//关闭锁定
            $this->addCronTime(0);//重置cron执行次数
            $this->mysql_conn->commit();
        }catch(PDOException $ex){
            $this->mysql_conn->rollBack();
            throw new PDOException($ex);
        }
    }
    
    public function create_yzh_item_record()
    {exit;
        $sql = sprintf("CREATE TABLE IF NOT EXISTS `yzh_item_record_%s` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `uid` int(11) unsigned NOT NULL COMMENT '用户id',
            `tenderee_id` int(11) unsigned NOT NULL COMMENT '融资人id',
            `pro_id` int(11) unsigned NOT NULL COMMENT '项目id',
            `item_id` int(11) unsigned NOT NULL COMMENT '项目-用户表中的主键id',
            `gain_money` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '投资人获得的收益',
            `pay_money` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '融资人应付的金额',
            `platform_pay` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '平台应付的金额',
            `pay_man` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '付款方（1：平台，2：融资方）',
            `calc_time` datetime NOT NULL DEFAULT '0000-01-01 00:00:00' COMMENT '计息时间',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE='utf8_general_ci' COMMENT='项目-用户-每日计息记录表'"
            ,
            date("Ym")
        );
        $res = $this->mysql_conn->exec($sql);var_dump($sql);var_dump($res);
    }

    public function create_yzh_item_settlement()
    {
        $sql = sprintf("CREATE TABLE IF NOT EXISTS `yzh_item_settlement_%s` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
            `tenderee_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '融资人id',
            `pro_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '项目id',
            `item_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '项目-用户表中的主键id',
            `settlement_gain` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '投资人入账金额',
            `settlement_pay` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '融资人付账金额',
            `platform_pay` decimal(16,5) unsigned NOT NULL DEFAULT '0.00000' COMMENT '平台付账金额',
            `pay_man` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '付款人（1：平台，2：融资人）',
            `is_finish` tinyint(2) unsigned NOT NULL DEFAULT '2' COMMENT '融资人是否已付款（1：全部返款完成，2：否）',
            `if_auto` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否是自动还款（1：是，2：否）',
            `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
            `settlement_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结算时间',
            `pay_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '还款时间',
            `hf_order_id` varchar(50) NOT NULL DEFAULT '' COMMENT '汇付订单id',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目-用户-每结账周期结帐表'"
            ,
            date("Ym")
        );
        $res = $this->mysql_conn->exec($sql);var_dump($sql);var_dump($res);
    }

}

$obj = new createTableMonth();
$obj->main();

?>
