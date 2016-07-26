<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 报表
  数据概览
  广告数据
  应用数据
  用户数据
 */
class Controller_Bms_Report extends Controller_Bms {

    public function action_index() {
        $this->template->content = new View("bms/report/index");
    }

    public function action_ad() {
        $method = $this->request->query("method");
        if ($method) {
            $filed = array(
                "push",
                "epush",
                "pv",
                "epv",
                "click",
                "eclick",
                "cpc_result",
                "real_cpc_result",
                "cpa_result",
                "real_cpa_result",
                "activate_result",
                "real_activate_result",
                "cpc_expense",
                "cpc_income",
                "cpa_expense",
                "cpa_income",
                "total_expense",
                "total_income"
            );

            $res = null;
            $limit = Arr::get($_POST, "pageSize", 10);
            $offset = Arr::get($_POST, "pageIndex", 0) * $limit;
            $groupFiled = $this->request->post("groupFiled");            
            $sortField = $this->request->post("sortField");
            $sortOrder = $this->request->post("sortOrder");
            $order = $sortField ? " order by $sortField $sortOrder " : "";            
            $qdate = $this->query_date();
            $filed_str = "date,ad_id,ad_type,ad_mprice,ad_price";
            $count_filed = "*";
            $group="";
            if ($groupFiled) {
                $group = " group by $groupFiled";
                foreach ($filed as $k) {
                    $filed_str = $filed_str . ",SUM($k)as $k";
                }
                $count_filed = "DISTINCT $groupFiled";
            } else {
                foreach ($filed as $k) {
                    $filed_str = $filed_str . ",$k";
                }
            }

            $q = Arr::extract($_POST, array("ad_type", "ad_id"));
            $begin = $qdate["begin_date"];
            $end = $qdate["end_date"];

            $where = "where `date` >='$begin' and `date`<='$end' ";
            foreach ($q as $k => $v) {
                if ($v != "" && $v != null) {
                    $where = $where . " and $k='$v'";
                }
            }

            $table = "ad_day_report";
            $sql = "select count($count_filed)as total from $table $where";
            $total = DB::query(Database::SELECT, $sql)->execute("report")->current();
            $sql = "select $filed_str from $table $where $group $order limit $offset,$limit";
            $data = DB::query(Database::SELECT, $sql)->execute("report")->as_array();

            $item = "AVG(ad_mprice)as ad_mprice_avg,MAX(ad_mprice)as ad_mprice_max,MIN(ad_mprice)as ad_mprice_min";
            foreach ($filed as $k) {
                $item = $item . ",SUM($k)as {$k}_sum,round(AVG($k),2)as {$k}_avg,MAX($k)as {$k}_max,MIN($k)as {$k}_min";
            }
            $sql = "select $item from $table $where";
            $sum = DB::query(Database::SELECT, $sql)->execute("report")->current();

            if ($data) {
                $ads = array();
                foreach ($data as $ad) {
                    $ads[] = $ad["ad_id"];
                }
                $ads = DB::select("id", "name")->from("advertisement")->where("id", "in", $ads)->execute("core")->as_array("id");
                foreach ($data as $i => $v) {
                    $data[$i]["ad_name"] = isset($ads[$v["ad_id"]]) ? $ads[$v["ad_id"]]["name"] : $v["ad_id"];
                }
            }

            $res = array(
                "total" => $total["total"],
                "data" => $data,
                "sum" => $sum,
            );
            echo json_encode($res);
            die;
        } else {
            $this->template->content = new View("bms/report/ad");
        }
    }

    public function action_app() {
        $method = $this->request->query("method");
        if ($method) {
            $filed = array("nu",
                "push",
                "banner",
                "pop",
                "offer",
                "ext1",
                "ext2",
                "ext3",
                "ext4",
                "total",
            );

            $res = null;
            $limit = Arr::get($_POST, "pageSize", 10);
            $offset = Arr::get($_POST, "pageIndex", 0) * $limit;
            $groupFiled = $this->request->post("groupFiled");
            $groupFiled = $groupFiled ? $groupFiled : "app_id";
            $sortField = $this->request->post("sortField");
            $sortOrder = $this->request->post("sortOrder");
            $qdate = $this->query_date();
            $order = $sortField ? " order by $sortField $sortOrder " : "";
            $group = " group by $groupFiled";
            $filed_str = "date,app_id,nu,tu";
            $count_filed = "*";
            if ($group) {
                foreach ($filed as $k) {
                    $filed_str = $filed_str . ",SUM($k)as $k";
                }
                $count_filed = "DISTINCT $groupFiled";
            } else {
                foreach ($filed as $k) {
                    $filed_str = $filed_str . ",$k";
                }
            }

            $q = Arr::extract($_POST, array("app_id"));
            $begin = $qdate["begin_date"];
            $end = $qdate["end_date"];

            $where = "where `date` >='$begin' and `date`<='$end' ";
            foreach ($q as $k => $v) {
                if ($v != "" && $v != null) {
                    $where = $where . " and $k='$v'";
                }
            }

            $table = "app_day_report";
            $sql = "select count($count_filed)as total from $table $where";
            $total = DB::query(Database::SELECT, $sql)->execute("report")->current();
            $sql = "select $filed_str from $table $where $group $order limit $offset,$limit";
            $data = DB::query(Database::SELECT, $sql)->execute("report")->as_array();

            $item = "SUM(nu)as nu,MAX(tu)as tu";
            foreach ($filed as $k) {
                $item = $item . ",SUM($k)as {$k}_sum,round(AVG($k),2)as {$k}_avg,MAX($k)as {$k}_max,MIN($k)as {$k}_min";
            }
            $sql = "select $item from $table $where";
            $sum = DB::query(Database::SELECT, $sql)->execute("report")->current();

            if ($data) {
                $apps = array();
                foreach ($data as $ad) {
                    $apps[] = $ad["app_id"];
                }
                $apps = DB::select("app_id", "app_name")->from("app")->where("app_id", "in", $apps)->execute("core")->as_array("app_id");
                foreach ($data as $i => $v) {
                    $data[$i]["app_name"] = isset($apps[$v["app_id"]]) ? $apps[$v["app_id"]]["app_name"] : $v["app_id"];
                }
            }
            
            $res = array(
                "total" => $total["total"],
                "data" => $data,
                "sum" => $sum
            );
            echo json_encode($res);
            die;
        } else {
            $this->template->content = new View("bms/report/app");
        }
    }

    public function action_developer() {
        $this->template->content = new View("bms/report/developer");
    }

    public function action_user() {
        $method = $this->request->query("method");
        if ($method) {
            $limit = Arr::get($_POST, "pageSize", 10);
            $offset = Arr::get($_POST, "pageIndex", 0) * $limit;
            $sortField = $this->request->post("sortField");
            $sortOrder = $this->request->post("sortOrder");
            $qdate = $this->query_date();
            $order = $sortField ? " order by $sortField $sortOrder " : "";
            $group = " group by date";
            $filed_str = "date,sum(nu)as nu,sum(tu)as tu,sum(dau)as dau,sum(wau)as wau,sum(mau)as mau";

            $q = Arr::extract($_POST, array("app_id"));
            $begin = $qdate["begin_date"];
            $end = $qdate["end_date"];

            $where = "where `date` >='$begin' and `date`<='$end' ";
            foreach ($q as $k => $v) {
                if ($v != "" && $v != null) {
                    $where = $where . " and $k='$v'";
                }
            }

            $table = "user_report";
            $sql = "select count(DISTINCT `date`)as total from $table $where";
            $total = DB::query(Database::SELECT, $sql)->execute("report")->current();
            $sql = "select $filed_str from $table $where $group $order limit $offset,$limit";
            $data = DB::query(Database::SELECT, $sql)->execute("report")->as_array();

            $res = array(
                "total" => $total["total"],
                "data" => $data
            );
            echo json_encode($res);
            die;
        } else {
            $this->template->content = new View("bms/report/user");
        }
    }

    private function query_date() {
        $query = Arr::extract($this->request->post(), array("begin_date", "end_date"));

        $query["begin_date"] = strtotime($query["begin_date"]) > 0 ? date("Y-m-d", strtotime($query["begin_date"])) : date("Y-m-d");
        $query["end_date"] = strtotime($query["end_date"]) > 0 ? date("Y-m-d", strtotime($query["end_date"])) : date("Y-m-d");
        return $query;
    }

}
