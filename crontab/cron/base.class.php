<?php 

ini_set('display_errors', true);
error_reporting(E_ALL);

date_default_timezone_set('PRC');

class base_class
{
    
    public $mysql_conn;
    public $now;
    public $settle_day;

    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
        //定义当前时间
        $cli_get_time = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : array() ;
        $this->now = isset($_GET['now']) ? date("Y-m-d H:i:s",strtotime($_GET['now'])) : date("Y-m-d H:i:s");
        $this->now = !empty($cli_get_time) ? date("Y-m-d H:i:s",strtotime($cli_get_time)) : $this->now;//echo $this->now = "2016-02-08 00:10:00";
        //定义结算日期
        $this->settle_day = isset($_GET['settle_day']) ? $_GET['settle_day'] : 15;
        $this->mysql_conn();
    }

    public function mysql_conn()
    {
        // print_R($_SERVER);
        // if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '182.92.224.45'){
            $dbConfig_json = file_get_contents(__DIR__."/config.db.devel.json");
        // }else{
            // $dbConfig_json = file_get_contents(__DIR__."/config.db.online.json");
        // }
        $dbConfig = json_decode($dbConfig_json);print_R($dbConfig);
        $this->mysql_conn = new PDO($dbConfig->dsn,$dbConfig->username,$dbConfig->password);
        $this->mysql_conn->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);//关闭自动提交
        $this->mysql_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//开启异常处理
        $this->mysql_conn->exec('set names utf8');
    }

    public function changeCronLock($status)
    {
        $sql = sprintf('update yzh_sys_config set value = %s where name = "cron_lock"', $status);
        $this->mysql_conn->exec($sql);
    }

    public function addCronTime($time = "value + 1")
    {
        $sql = sprintf('update yzh_sys_config set value = %s where name = "cron_time"', $time);
        $this->mysql_conn->exec($sql);
    }

    public function addCronLastDate()
    {
        $sql = sprintf('update yzh_sys_config set value = "%s" where name = "cron_last_date"',$this->now);
        $this->mysql_conn->exec($sql);
    }

    public function checkCronTime()
    {
        $times = 3;
        $sql = sprintf('select * from yzh_sys_config where name = "cron_time"');
        $res = $this->mysql_conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        if($res[0]['value'] >= $times){
            //发短信
        }
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
        $res = $this->mysql_conn->exec($sql);var_dump($res);
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
        $res = $this->mysql_conn->exec($sql);var_dump($res);
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
        $res = $this->mysql_conn->exec($sql);var_dump($res);
    }

}

?>
