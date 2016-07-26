<?php

/**
 * 业务数据表拆分
  ----------------------------------------------

  app_adevents 广告日展示数据
  500活跃开发者，平均日活1W，平均pv 25W，每日pv 12500W 拆分16库 日单表800w

  app_customer 应用用户数据
  500活跃开发者，每开发者500W用户，共250000用户，拆分256库 单表1000W

  ad_click cpc广告
  每开发者月20W，500开发者，共10000W，每日1表 单表300W

  ad_install cpa广告
  每开发者月20W，500开发者，共10000W，每日1表 单表300W

  ad_installed cpa6月历史，用于统计效果
  60000W，拆分256表，单表250W


  customer 独立用户
  100000W，拆分256库，单表 400W


  库拆分
  ----------------------------
  core 广告业务数据

  customer 用户画像数据 customer

  customer_hot 用户热数据 customer_events

  ad 广告效果数据 ad_click,ad_install,ad_installed

  ad_hot 广告热数据 app_adevents

  app 应用数据 app_customer,app_customer_score

  report 报表数据
 * @author jiwei
 */
class DbHash {

    public static function customerTable($openuuid) {
        return "customer_" . substr($openuuid, 0, 2);
    }

    public static function customerDB($openuuid) {
        return "customer_db";
    }

    public static function customerTables() {
        $re = range(16, 255);
        $res = array();
        foreach ($re as $i) {
            $res["customer_" . str_pad(dechex($i), 2, 0, STR_PAD_LEFT)] = DbHash::customerDB(str_pad(dechex($i), 2, 0, STR_PAD_LEFT));
        }
        return $res;
    }

    public static function customerEventsTable($openuuid) {
        return "customer_events_" . substr($openuuid, 0, 2);
    }

    public static function customerEventsDB($openuuid) {
        return "log";
    }

    public static function adInstallTable($date) {
        return "ad_install_" . date("Ymd", $date);
    }

    public static function adInstallDB() {
        return "ad_result";
    }

    public static function adActivateTable($date) {
        return "ad_activate_" . date("Ymd", $date);
    }

    public static function adActiveDB() {
        return "ad_result";
    }

    public static function adClickTable($date) {
        return "ad_click_" . date("Ymd", $date);
    }

    public static function adClickDB() {
        return "ad_result";
    }

    public static function adInstalledTable($openuuid) {
        return "ad_installed_" . substr($openuuid, 0, 2);
    }

    public static function adInstalledDB($openuuid) {
        return "ad_result";
    }

    public static function appAdeventsTable($appid, $datetime) {
        return "app_adevents_" . date("Ymd", $datetime) . "_" . substr($appid, 0, 1);
    }

    public static function appAdeventsTables($datetime) {
        $day = date("Ymd", $datetime);
        $re = range(0, 15);
        $res = array();
        foreach ($re as $i) {
            $res["app_adevents_" . $day . "_" . str_pad(dechex($i), 1, 0, STR_PAD_LEFT)] = DbHash::appAdeventsDB(str_pad(dechex($i), 1, 0, STR_PAD_LEFT));
        }
        return $res;
    }

    public static function appAdeventsDB($appid) {
        return "adevent_db";
    }

    public static function appCustomerTable($appid) {
        return "app_customer_" . substr($appid, 0, 2);
    }

    public static function appCustomerTables() {
        $re = range(0, 255);
        $res = array();
        foreach ($re as $i) {
            $res["app_customer_" . str_pad(dechex($i), 2, 0, STR_PAD_LEFT)] = DbHash::appCustomerDB(str_pad(dechex($i), 2, 0, STR_PAD_LEFT));
        }
        return $res;
    }

    public static function appCustomerDB($appid) {
        return "app_db";
    }

    public static function appCustomerScoreTable($appid) {
        return "app_customer_score_" . substr($appid, 0, 2);
    }

    public static function appCustomerScoreDB($appid) {
        return "app_db";
    }

    public static function signinLogTable($appid) {
        return "signin_log_" . substr($appid, 0, 2);
    }

    public static function signinLogDB($appid) {
        return "prize";
    }

}
