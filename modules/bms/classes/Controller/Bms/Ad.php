<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 广告
  广告概况
  广告接入录入
  广告调配
  广告主维护
 */
class Controller_Bms_Ad extends Controller_Bms {

    public function action_index() {
        $this->template->content = new View("bms/ad/index");
    }

    public function action_add() {
        $id = $this->request->query("id");
        $advertisement = ORM::factory("Advertisement");
        if ($this->request->query("method")) {
            $method = $this->request->query("method");
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder", "key", "ad_type"));
                if ($query["key"]) {
                    $advertisement->where("name", "like", "%" . $query["key"] . "%");
                }
                if ($query["ad_type"]) {
                    $advertisement->where("ad_type", "=", $query["ad_type"]);
                }
                $advs = $advertisement->where("id", ">", 0)->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all()->as_array();
                $data = array();
                foreach ($advs as $ad) {
                    $adrule = $ad->as_array();
                    $adrule["add_time"] = date("Y-m-d", $adrule["add_time"]);
                    $adrule["passed_time"] = $adrule["passed_time"] ? date("Y-m-d", $adrule["passed_time"]) : "--";
                    $adrule["begin_date"] = date("Y-m-d", $adrule["begin_date"]);
                    $adrule["end_date"] = date("Y-m-d", $adrule["end_date"]);
                    unset($adrule["adrule"]);
                    $data[] = $adrule;
                }
                if ($query["key"]) {
                    $advertisement->where("name", "like", "%" . $query["key"] . "%");
                }
                if ($query["ad_type"]) {
                    $advertisement->where("ad_type", "=", $query["ad_type"]);
                }
                $advertisement->where("id", ">", 0);
                $res = array(
                    "total" => $advertisement->count_all(),
                    "data" => $data
                );
            } elseif ($method == "get") {
                $ad = $advertisement->where("id", "=", $id)->find();
                $res = $ad->adrule->as_array() + $ad->as_array();
                $res["begin_date"] = date("Y-m-d", $res["begin_date"]);
                $res["end_date"] = date("Y-m-d", $res["end_date"]);
                unset($res["adrule"]);
            } elseif ($method == "delete") {
                if ($id) {
                    $id = explode(",", $id);
                    DB::delete("advertisement")->where("add_by", "=", $this->manager)
                            ->where("status", "=", "pendding")
                            ->where("id", "in", $id)->execute("core");
                }
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $ad) {
                    $ad->begin_date = strtotime($ad->begin_date);
                    $ad->end_date = strtotime($ad->end_date);
                    if (!$ad->id) {
                        $ad->add_time = time();
                    }
                    $ad->add_by = $this->manager;
                    $advertisement->where("id", ">", 0)->where("id", "=", $ad->id)->find()
                            ->values((array) $ad)
                            ->save();
                    $advertisement->adrule->ad_id = $advertisement->id;
                    $advertisement->adrule->values((array) $ad)->save();
                }
                $res = new stdClass();
            }
            echo json_encode($res);
            die;
        } else {
            if ($this->request->param("id")) {
                if ($this->request->param("id") == "preview") {
                    $this->template->content = new View("bms/ad/preview");
                } elseif ($this->request->param("id") == "rule") {
                    $this->template->content = new View("bms/ad/rule");
                } else {
                    $this->template->content = new View("bms/ad/edit");
                }
            } else {
                $this->template->content = new View("bms/ad/add");
            }
        }
    }

    public function action_manage() {
        $id = $this->request->query("id");
        $advertisement = ORM::factory("Advertisement");
        if ($this->request->query("method")) {
            $method = $this->request->query("method");
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder", "key"));
                if ($query["key"]) {
                    $advertisement->where("name", "like", "%" . $query["key"] . "%");
                }
                $advs = $advertisement->where("id", ">", 0)->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all()->as_array();
                $data = array();
                foreach ($advs as $ad) {
                    $adrule = $ad->as_array();
                    $adrule["add_time"] = date("Y-m-d", $adrule["add_time"]);
                    $adrule["passed_time"] = $adrule["passed_time"] ? date("Y-m-d", $adrule["passed_time"]) : "--";
                    $adrule["begin_date"] = date("Y-m-d", $adrule["begin_date"]);
                    $adrule["end_date"] = date("Y-m-d", $adrule["end_date"]);
                    unset($adrule["adrule"]);
                    $data[] = $adrule;
                }
                $res = array(
                    "total" => $advertisement->count_all(),
                    "data" => $data
                );
            } elseif ($method == "get") {
                $ad = $advertisement->where("id", "=", $id)->find();
                $res = $ad->adrule->as_array() + $ad->as_array();
                $res["begin_date"] = date("Y-m-d", $res["begin_date"]);
                $res["end_date"] = date("Y-m-d", $res["end_date"]);
                unset($res["adrule"]);
            } elseif ($method == "delete") {
                if ($id) {
                    $id = explode(",", $id);
                    DB::delete("advertisement")->where("add_by", "=", $this->manager)
                            ->where("status", "=", "pendding")
                            ->where("id", "in", $id)->execute("core");
                }
            }elseif ($method == "op") {
                if ($id) {
                    $id = explode(",", $id);
                    $status = $this->request->query("status");
                    DB::update("advertisement")->value("status",$status)                            
                            ->where("id", "in", $id)->execute("core");
                }
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $ad) {
                    $ad->begin_date = strtotime($ad->begin_date);
                    $ad->end_date = strtotime($ad->end_date);
                    if (!$ad->id) {
                        $ad->add_time = time();
                        $ad->add_by = $this->manager;
                    }
                    $ad->passed_time = time();
                    $ad->pass_by = $this->manager;
                    $advertisement->where("id", ">", 0)->where("id", "=", $ad->id)->find()
                            ->values((array) $ad)
                            ->save();
                    $advertisement->adrule->ad_id = $advertisement->id;
                    $advertisement->adrule->values((array) $ad)->save();
                }
                $res = new stdClass();
            }
            echo json_encode($res);
            die;
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/ad/manageedit");
            } else {
                $this->template->content = new View("bms/ad/manage");
            }
        }
    }

    public function action_advertiser() {
        $advertiser = ORM::factory("Advertiser");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));

                $advs = $advertiser->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all()->as_array();
                $data = array();
                foreach ($advs as $v) {
                    $v->add_time = date("Y-m-d", $v->add_time);
                    $data[] = $v->as_array();
                }
                unset($advs);
                $res = array(
                    "total" => $advertiser->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $adver) {
                    $adver->total_ad = ORM::factory("Advertisement")->where("advertiser_id", "=", $adver->id)->count_all();
                    $advertiser->where("id", "=", $adver->id)->find()
                            ->values((array) $adver)
                            ->save();
                }
            } elseif ($method == "get") {
                $res = $advertiser->where("id", "=", $this->request->query("id"))->find()->as_array();
            } elseif ($method == "delete") {
                $advertiser->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/advertiser/edit");
            } else {
                $this->template->content = new View("bms/advertiser/list");
            }
        }
    }

    public function action_receivable() {

        $payable = ORM::factory("AdvertiserPayables");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));

                $payables = $payable->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all()->as_array();
                $data = array();
                foreach ($payables as $p) {
                    $p = $p->as_array();
                    $p["closing_date"] = date("Y-m", strtotime($p["closing_date"]));
                    $data[] = $p;
                }
                $res = array(
                    "total" => $payable->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $p) {
                    $payable->where("id", "=", $p->id)->find()
                            ->values((array) $p)
                            ->save();
                }
            } elseif ($method == "get") {
                $res = $payable->where("id", "=", $this->request->query("id"))->find()->as_array();
            } elseif ($method == "count") {
                $req = json_decode($this->request->post("data"));
                $ad_id = intval($req->ad_id);
                $ad_name = $req->ad_name;
                $begin_date = date("Y-m-d", strtotime($req->begin_date));
                $end_date = date("Y-m-d", strtotime($req->end_date));
                $sql = "SELECT advertiser_id,a.id as ad_id,a.`name` as ad_name,a.fee_type as fee_type,
                        a.price as price,a.add_by as ad_owner,a.pass_by as ad_master,
                        b.company as advertiser_company,b.type as advertiser_type
                        FROM advertisement a JOIN advertiser b ON a.advertiser_id=b.id WHERE a.id=$ad_id";
                $res = DB::query(Database::SELECT, $sql)->execute("core")->current();
                $sql = "SELECT ad_name,price,SUM(cpa_result)+SUM(cpc_result) as amount,SUM(advertiser_cpc)+SUM(advertiser_cpa) as confirmed_amount,
                        SUM(total_income) as projected_income, SUM(total_expense) as media_spending FROM advertisement_bills
                        WHERE ad_name='$ad_name' AND date>='$begin_date' AND date<='$end_date'";
                $adpy = DB::query(Database::SELECT, $sql)->execute("core")->current();
                if ($adpy["ad_name"]) {
                }else{
                    $adpy=array("amount"=>0,"confirmed_amount"=>0,"projected_income"=>0,"media_spending"=>0);
                            
                }
                    $res = $adpy + $res;
                
                $res["begin_date"] = $begin_date;
                $res["end_date"] = $end_date;
                $res["closing_date"] = date("Y-m-d");
                $req = $res + (array) $req;
            } elseif ($method == "delete") {
                $payable->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/ad/receivableedit");
            } else {
                $this->template->content = new View("bms/ad/receivable");
            }
        }
    }

    public function action_select() {
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $advertisement = ORM::factory("Advertisement");
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder", "key", "ad_type"));
                if ($query["key"]) {
                    $advertisement->where("name", "like", "%" . $query["key"] . "%");
                }
                $advs = $advertisement->select("id", "name", "platform", "fee_type", "ad_type", "status")->where("id", ">", 0)->group_by("name")
                                ->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all();
                $sql = "SELECT COUNT(DISTINCT `name`)as total FROM advertisement WHERE id>0";
                if ($query["key"]) {
                    $sql = $sql . " and name like ('" . $query["key"] . "%')";
                }
                $total = DB::query(Database::SELECT, $sql)->execute("core")->current();
                $data = array();
                foreach ($advs as $ad) {
                    $data[] = $ad->as_array();
                }
                $res = array(
                    "total" => $total["total"],
                    "data" => $data
                );
            }
            echo json_encode($res);
            die;
        } else {
            $view = $this->request->query("view");
            if ($view == "bills") {
                $this->template->content = new View("bms/ad/selectbills");
            } else {
                $this->template->content = new View("bms/ad/select");
            }
        }
    }

    public function action_push() {
        $pushtask = ORM::factory("PushTask");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));

                $task = $pushtask->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all()->as_array();
                $data = array();
                foreach ($task as $p) {
                    $p = $p->as_array() + $p->ad->as_array();
                    $p["add_time"] = $p["add_time"] ? date("Y-m-d H:i", $p["add_time"]) : "";
                    $p["push_time"] = $p["push_time"] ? date("Y-m-d H:i", $p["push_time"]) : "";
                    $data[] = $p;
                }
                $res = array(
                    "total" => $pushtask->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $p) {
                    $p->push_time = strtotime($p->push_time);
                    $p->first_visit = strtotime($p->first_visit);
                    $p->add_by = $this->manager_name;
                    if (!$p->id) {
                        $p->add_time = time();
                    }
                    $pushtask->where("id", "=", $p->id)->find()
                            ->values((array) $p)
                            ->save();
                }
            } elseif ($method == "get") {
                $res = $pushtask->where("id", "=", $this->request->query("id"))->find();
                $res = $res->as_array() + $res->ad->as_array();
                $res["add_time"] = $res["add_time"] ? date("Y-m-d H:i:s", $res["add_time"]) : "";
                $res["push_time"] = $res["push_time"] ? date("Y-m-d H:i:s", $res["push_time"]) : "";
                $res["first_visit"] = $res["first_visit"] ? date("Y-m-d H:i:s", $res["first_visit"]) : "";
            } elseif ($method == "delete") {
                $pushtask->where("id", "=", $this->request->query("id"))->find()->delete();
            } elseif ($method == "count") {
                $task = $pushtask->where("id", "=", $this->request->query("id"))->find()->as_array();
                $res = Task_Pushtask::computeusers($task);
            } elseif ($method == "push") {
                $task = $pushtask->where("id", "=", $this->request->query("id"))->find()->as_array();
                $res = Task_Pushtask::process_pushtask($task);
            } elseif ($method == "test") {
                $task = $pushtask->where("id", "=", $this->request->query("id"))->find()->as_array();
                if ($task["testid"]) {
                    Task_Pushtask::push_to($task, $task["testid"]);
                    $res = $task["testid"];
                }
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/ad/pushedit");
            } else {
                $this->template->content = new View("bms/ad/push");
            }
        }
    }

    public function action_bills() {
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "save") {
                $data = $this->request->post("data");
                $data = json_decode($data);
                if (is_array($data)) {
                    foreach ($data as $item) {
                        DB::update("advertisement_bills")
                                ->value("advertiser_cpc", $item->advertiser_cpc)
                                ->value("advertiser_cpa", $item->advertiser_cpa)
                                ->value("check_time", time())
                                ->where("id", "=", $item->id)
                                ->execute("core");
                    }
                }
            } else {
                $filed = array(
                    "spush",
                    "epush",
                    "pv",
                    "epv",
                    "click",
                    "eclick",
                    "download",
                    "edownload",
                    "cpc_result",
                    "cpa_result",
                    "advertiser_cpc",
                    "advertiser_cpa",
                    "add_by",
                    "check_time",
                );
                $limit = Arr::get($_POST, "pageSize", 10);
                $offset = Arr::get($_POST, "pageIndex", 0) * $limit;
                $sortField = $this->request->post("sortField");
                $sortOrder = $this->request->post("sortOrder");
                $order = $sortField ? " order by $sortField $sortOrder " : "";
                $qdate = $this->query_date();
                $filed_str = "id,date,advertiser_id,ad_name,fee_type,price";
                $count_filed = "*";
                foreach ($filed as $k) {
                    $filed_str = $filed_str . ",$k";
                }

                $ad_name = $this->request->post("ad_name");
                $begin = $qdate["begin_date"];
                $end = $qdate["end_date"];

                $where = "where `date` >='$begin' and `date`<='$end' ";
                if ($ad_name) {
                    $where = $where . " and ad_name like('$ad_name%')";
                }
                $table = "advertisement_bills";
                $sql = "select count($count_filed)as total from $table $where";
                $total = DB::query(Database::SELECT, $sql)->execute("core")->current();
                $sql = "select $filed_str from $table $where $order limit $offset,$limit";
                $data = DB::query(Database::SELECT, $sql)->execute("core")->as_array();

                if ($data) {
                    $ads = array();
                    foreach ($data as $ad) {
                        $ads[] = $ad["advertiser_id"];
                    }
                    $ads = DB::select("id", "name")->from("advertiser")->where("id", "in", $ads)->execute("core")->as_array("id");
                    foreach ($data as $i => $v) {
                        $data[$i]["check_time"]=  $v["check_time"]?date("Y-m-d H:i",$v["check_time"]):"-";
                        $data[$i]["advertiser_id"] = isset($ads[$v["advertiser_id"]]) ? $ads[$v["advertiser_id"]]["name"] : $v["advertiser_id"];
                    }
                }

                $res = array(
                    "total" => $total["total"],
                    "data" => $data,
                );
            }
            echo json_encode($res);
            die;
        } else {
            $this->template->content = new View("bms/ad/bills");
        }
    }

    private function query_date() {
        $query = Arr::extract($this->request->post(), array("begin_date", "end_date"));

        $query["begin_date"] = strtotime($query["begin_date"]) > 0 ? date("Y-m-d", strtotime($query["begin_date"])) : date("Y-m-d");
        $query["end_date"] = strtotime($query["end_date"]) > 0 ? date("Y-m-d", strtotime($query["end_date"])) : date("Y-m-d");
        return $query;
    }

}
