<?php

/**
 * Description of Monitoring
 * 监控
  图表
  数据
 * @author jiwei
 */
class Controller_Bms_Monitoring extends Controller_Bms {

    /**
     * 图表趋势
     */
    public function action_index() {
        $ctype = $this->request->param("id");
        if (!in_array($ctype, array("trend", "addist", "appdist"))) {
            $ctype = "trend";
        }
        $chart = array();
        $chart["credits"]["enabled"] = false;
        $qdate = $this->query_date();
        if ($ctype == "trend") {
            $chart = $this->trend($chart, $qdate);
        } elseif ($ctype == "addist") {
            $chart = $this->addist($chart, $qdate);
        } elseif (($ctype == "appdist")) {
            $chart = $this->appdist($chart, $qdate);
        }
        if ($this->request->post("method")) {
            echo json_encode($chart);
            die;
        } else {
            $this->template->content = new View("bms/monitoring/" . $ctype, array("chart" => $chart));
        }
    }

    private function trend($chart, $qdate) {
        $chart["title"]["text"] = "近日广告效果趋势";
        $chart["yAxis"] = array(array("title" => array("text" => "用户数")), array("title" => array("text" => "效果量")), array("title" => array("text" => "收入"), "opposite" => true));
        $chart["tooltip"]["shared"] = true;

        $query = Arr::extract($_POST, array("ad_type", "ad_id", "app_id"));
        $step = $this->request->query("step");
        $step = in_array($step, array("hour", "day", "month")) ? $step : (($qdate["begin_date"] == $qdate["end_date"]) ? "hour" : "day");
        if ($step == "hour" && strtotime($qdate["begin_date"]) + 864000 < strtotime($qdate["end_date"])) {
            $step = "day";
        }
        $x = array();
        $begin = strtotime($qdate["begin_date"]);
        $end = strtotime("+1day", strtotime($qdate["end_date"]));
        while ($begin < $end) {
            $x[] = date("Y-m-d H:00:00", $begin);
            $begin = strtotime("+1$step", $begin);
        }
        $chart["xAxis"]["categories"] = $x;
        $chart["xAxis"]["labels"]["step"] = intval(count($x) / 12);

        $series = array(
            "pv" => array(
                "type" => "spline",
                "name" => "展示次数",
                "yAxis" => 0,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 次")
            ),
            "epv" => array(
                "type" => "spline",
                "name" => "有效展示",
                "yAxis" => 0,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 次")
            ),
            "click" => array(
                "type" => "spline",
                "name" => "点击次数",
                "yAxis" => 0,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 次")
            ),
            "eclick" => array(
                "type" => "spline",
                "name" => "有效点击",
                "yAxis" => 0,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 次")
            ),
            "real_cpc_result" => array(
                "type" => "spline",
                "name" => "CPC成果数",
                "yAxis" => 0,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 个")
            ),
            "real_cpa_result" => array(
                "type" => "spline",
                "name" => "CPA成果数",
                "yAxis" => 0,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 个")
            ),
            "cpc_income" => array(
                "type" => "column",
                "name" => "预计CPC收入",
                "yAxis" => 1,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 元")
            ),
            "cpa_income" => array(
                "type" => "column",
                "name" => "预计CPA收入",
                "yAxis" => 1,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 元")
            ),
            "total_income" => array(
                "type" => "column",
                "name" => "预计广告收入",
                "yAxis" => 1,
                "data" => array(),
                "tooltip" => array("valueSuffix" => " 元")
            )
        );

        $where = " where 1=1 ";
        foreach ($query as $k => $v) {
            if ($v) {
                $where = $where . " and $k='$v'";
            }
        }
        $begin = strtotime($qdate["begin_date"]);


        $group = ($step == "hour") ? "GROUP BY `hour`" : "";
        $key = ($step == "hour") ? "CONCAT(date,' ',RIGHT(CONCAT('0',`hour`),2),':00:00')as `key`" : "CONCAT(date,' 00:00:00') as `key`";
        $prex = ($step == "hour") ? "ad_hour_" : "ad_day_";

        $d = array();
        while ($begin < $end) {
            if ($begin == strtotime(date("Y-m-d")) && $prex == "ad_day_") {
                $prex = "ad_hour_";
            }
            $table = $prex . date("Ymd", $begin);
            $sql = "SELECT
                    $key,
                    SUM(pv)as pv,
                    SUM(epv)as epv,
                    SUM(click)as click,
                    SUM(eclick)as eclick,
                    SUM(cpc_result)as cpc_result,
                    SUM(real_cpc_result)as real_cpc_result,
                    SUM(cpa_result)as cpa_result,
                    SUM(real_cpa_result)as real_cpa_result,
                    SUM(activate_result)as activate_result,
                    SUM(real_activate_result)as real_activate_result,
                    SUM(cpc_expense)as cpc_expense,
                    SUM(cpc_income)as cpc_income,
                    SUM(cpa_expense)as cpa_expense,
                    SUM(cpa_income)as cpa_income,
                    SUM(total_expense)as total_expense,
                    SUM(total_income)as total_income
                    FROM $table
                    $where
                    $group";
            $d = $d + DB::query(Database::SELECT, $sql)->execute("report")->as_array("key");
            $begin = strtotime("+1day", $begin);
        }
        $c = array_keys($series);
        foreach ($x as $i) {
            foreach ($c as $k) {
                $series[$k]["data"][] = floatval(isset($d[$i]) ? $d[$i][$k] : 0);
            }
        }
        if ($step == "hour") {
            foreach ($chart["xAxis"]["categories"] as $i => $h) {
                $chart["xAxis"]["categories"][$i] = date("m.d H:00", strtotime($h));
            }
        } else {
            foreach ($chart["xAxis"]["categories"] as $i => $h) {
                $chart["xAxis"]["categories"][$i] = date("m.d", strtotime($h));
            }
        }

        $chart["series"] = array_values($series);
        return $chart;
    }

    private function addist($chart, $qdata) {
        $chart["title"]["text"] = "";
        $chart["xAxis"]["categories"] = "";
        $chart["yAxis"] = array(array("title" => array("text" => "用户数")), array("title" => array("text" => "效果量")), array(array("title" => array("text" => "收入"), "opposite" => true)));
        $chart["tooltip"]["shared"] = true;
        $chart["series"] = array();
        return $chart;
    }

    private function appdist($chart, $qdata) {
        $chart["title"]["text"] = "";
        $chart["xAxis"]["categories"] = "";
        $chart["yAxis"] = array(array("title" => array("text" => "用户数")), array("title" => array("text" => "效果量")), array(array("title" => array("text" => "收入"), "opposite" => true)));
        $chart["tooltip"]["shared"] = true;
        $chart["series"] = array();
        return $chart;
    }

    public function action_adtype() {
        $method = $this->request->query("method");
        if ($method) {
            $filed = array("pv",
                "push",
                "pv",
                "epv",
                "click",
                "eclick",
                "download",
                "edownload",
                "cpc_result",
                "cpc_income",
                "cpa_result",
                "cpa_income",
                "activate_result",
                "activate_income",
                "total_income"
            );

            $res = null;
            $limit = Arr::get($_POST, "pageSize", 10);
            $offset = Arr::get($_POST, "pageIndex", 0) * $limit;
            $qdate = $this->query_date();
            $groupFiled = $this->request->post("groupFiled");
            $sortField = $this->request->post("sortField");
            $sortOrder = $this->request->post("sortOrder");
            $order = $sortField ? " order by $sortField $sortOrder " : "";
            $group = $groupFiled ? " group by $groupFiled" : "";
            $filed_str = "date,app_id,ad_type,au";
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
            $q = Arr::extract($_POST, array("ad_type","app_id"));
            $where = "where date >='{$qdate["begin_date"]}' and date <='{$qdate["end_date"]}' ";
            foreach ($q as $k => $v) {
                if ($v != "" && $v != null) {
                    $where = $where . " and $k='$v'";
                }
            }

            $table = "app_adtype_report";
            $sql = "select count($count_filed)as total from $table $where";
            $total = DB::query(Database::SELECT, $sql)->execute("report")->current();
            $sql = "select $filed_str from $table $where $group $order limit $offset,$limit";
            $data = DB::query(Database::SELECT, $sql)->execute("report")->as_array();


            if ($data) {
                $ads = array();
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
            );
            echo json_encode($res);
            die;
        } else {
            $this->template->content = new View("bms/monitoring/adtype");
        }
    }

    /**
     * 数据监控
     */
    public function action_data() {
        $method = $this->request->query("method");
        if ($method) {
            $filed = array("pv",
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
            $date = json_decode($this->request->post("date"));
            $groupFiled = $this->request->post("groupFiled");
            $sortField = $this->request->post("sortField");
            $sortOrder = $this->request->post("sortOrder");
            $order = $sortField ? " order by $sortField $sortOrder " : "";
            $group = $groupFiled ? " group by $groupFiled" : "";
            $filed_str = "date,`hour`,app_id,ad_id,ad_type,ad_mprice,ad_price";
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

            $q = Arr::extract($_POST, array("hour", "ad_type", "ad_id", "app_id"));
            $where = "where 1=1 ";
            foreach ($q as $k => $v) {
                if ($v != "" && $v != null) {
                    $where = $where . " and $k='$v'";
                }
            }

            $table = "ad_hour_" . date("Ymd", strtotime($date) > 0 ? strtotime($date) : time());
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
                $apps = array();
                foreach ($data as $ad) {
                    $ads[] = $ad["ad_id"];
                    $apps[] = $ad["app_id"];
                }
                $ads = DB::select("id", "name")->from("advertisement")->where("id", "in", $ads)->execute("core")->as_array("id");
                foreach ($data as $i => $v) {
                    $data[$i]["ad_name"] = isset($ads[$v["ad_id"]]) ? $ads[$v["ad_id"]]["name"] : $v["ad_id"];
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
            $this->template->content = new View("bms/monitoring/data");
        }
    }

    private function query_date() {
        $query = Arr::extract($this->request->post(), array("date", "begin_date", "end_date"));
        if (!$this->request->query()) {
            $query["date"] = date("Y-m-d");
        }
        if (strtotime($query["date"]) > 0) {
            $query["begin_date"] = $query["date"];
            $query["end_date"] = $query["date"];
        }
        if (strtotime($query["end_date"]) < 100000) {
            $query["end_date"] = date("Y-m-d");
        }

        $query["begin_date"] = strtotime($query["begin_date"]) > 0 ? date("Y-m-d", strtotime($query["begin_date"])) : date("Y-m-d");
        $query["end_date"] = strtotime($query["end_date"]) > 0 ? date("Y-m-d", strtotime($query["end_date"])) : date("Y-m-d");
        return $query;
    }

}
