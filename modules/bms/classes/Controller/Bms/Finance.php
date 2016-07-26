<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 财务
  财务概况
  应收汇总
  应收明细
  回款管理
  发票管理
  提现申请
  应付账款
 */
class Controller_Bms_Finance extends Controller_Bms {

    public function action_index() {
        $this->template->content = new View("bms/finance/index");
    }

    public function action_receivables() {

        if ($this->request->query("method")) {
            $method = $this->request->query("method");
            $res = null;
            if ($method == "list") {
                $sql = "SELECT DATE_FORMAT(closing_date,'%Y-%m')as `month`,ad_owner,advertiser_company,SUM(media_spending)as media_spending,
                SUM(amount)as amount,SUM(projected_income) as projected_income,SUM(confirmed_income)as confirmed_income,
                SUM(CASE WHEN payback_status='Y' THEN confirmed_income END)as payback_money
                FROM advertiser_payables
                WHERE 1=1
                GROUP BY advertiser_id,DATE_FORMAT(closing_date,'%Y-%m')";
                $data = DB::query(Database::SELECT, $sql)->execute("core")->as_array();
                foreach ($data as $i => $p) {
                    $data[$i]["confirmed_rate"] = $p["projected_income"] > 0 ? (round($p["confirmed_income"] * 100 / $p["projected_income"], 2) . "%") : "--";
                    $data[$i]["back_rate"] = $p["confirmed_income"] > 0 ? (round($p["payback_money"] * 100 / $p["confirmed_income"], 2) . "%") : "--";
                }
                $res = array(
                    "total" => count($data),
                    "data" => $data
                );
            }
            echo json_encode($res);
            die;
        } else {
            $this->template->content = new View("bms/finance/receivables");
        }
    }

    public function action_receivable() {
        $payable = ORM::factory("AdvertiserPayables");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $payables = $payable->find_all()->as_array();
                $data = array();
                foreach ($payables as $p) {
                    $p = $p->as_array();
                    $p["month"] = date("Y-m", strtotime($p["closing_date"]));
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
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/finance/receivableedit");
            } else {
                $this->template->content = new View("bms/finance/receivable");
            }
        }
    }

    public function action_payback() {
        $payback = ORM::factory("AdvertiserPayback");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $paybacks = $payback->find_all()->as_array();
                $data = array();
                foreach ($paybacks as $p) {
                    $p = $p->as_array();
                    $p["add_time"] = date("Y-m-d", $p["add_time"]);
                    $p["pay_month"] = date("Y-m", strtotime($p["pay_month"]));
                    $data[] = $p;
                }
                $res = array(
                    "total" => $payback->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $p) {
                    $p->add_time = time();
                    $payback->where("id", "=", $p->id)->find()
                            ->values((array) $p)
                            ->save();
                }
            } elseif ($method == "get") {
                $res = $payback->where("id", "=", $this->request->query("id"))->find()->as_array();
            } elseif ($method == "delete") {
                $payback->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/finance/paybackedit");
            } else {
                $this->template->content = new View("bms/finance/payback");
            }
        }
    }

    public function action_bill() {
        $bill = ORM::factory("AdvertiserBill");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $bills = $bill->find_all()->as_array();
                $data = array();
                foreach ($bills as $p) {
                    $p = $p->as_array();
                    $p["add_time"] = date("Y-m-d", $p["add_time"]);
                    $p["pay_month"] = date("Y-m", strtotime($p["pay_month"]));
                    $data[] = $p;
                }
                $res = array(
                    "total" => $bill->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $p) {
                    $bill->where("id", "=", $p->id)->find();
                    $p->add_by = $this->manager_name;
                    if (!$bill->add_time) {
                        $p->add_time = time();
                    }
                    $bill->values((array) $p)->save();
                }
            } elseif ($method == "get") {
                $res = $bill->where("id", "=", $this->request->query("id"))->find()->as_array();
            } elseif ($method == "delete") {
                $bill->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/finance/billedit");
            } else {
                $this->template->content = new View("bms/finance/bill");
            }
        }
    }

    public function action_transfer() {
        $transfer = ORM::factory("UserTransfer");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $bills = $transfer->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                foreach ($bills as $p) {
                    $p->user_id = $p->user->email;
                    unset($p->user);
                    $p = $p->as_array();
                    $p["update_time"] = $p["update_time"] > 0 ? date("Y-m-d", $p["update_time"]) : "--";
                    $p["apply_time"] = date("Y-m-d", $p["apply_time"]);
                    $p["transfer_time"] = $p["transfer_time"] > 0 ? date("Y-m-d", $p["transfer_time"]) : "--";
                    $data[] = $p;
                }
                $res = array(
                    "total" => $transfer->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $p) {
                    unset($p->apply_time);
                    $p->transfer_time = strtotime($p->transfer_time);
                    $transfer->where("id", "=", $p->id)->find();
                    $transfer->update_by = $this->manager_name;
                    $transfer->update_time = time();
                    $transfer->values((array) $p)->save();
                }
            } elseif ($method == "get") {
                $res = $transfer->where("id", "=", $this->request->query("id"))->find()->as_array();
                $res["transfer_time"] = $res["transfer_time"] > 0 ? date("Y-m-d", $res["transfer_time"]) : "";
                $res["apply_time"] = date("Y-m-d", $res["apply_time"]);
            } elseif ($method == "delete") {
                $transfer->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/finance/transferedit");
            } else {
                $this->template->content = new View("bms/finance/transfer");
            }
        }
    }

    public function action_payable() {
        $payable = ORM::factory("MonthPayable");
        $method = $this->request->query("method");
        if ($method) {
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $payables = $payable->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                foreach ($payables as $p) {
                    $p = $p->as_array();
                    $p["month"] = date("Y-m", strtotime($p["month"]));
                    $p["gross_margin"] = $p["gross_margin"] ? $p["gross_margin"] . "%" : "";
                    $data[] = $p;
                }
                $res = array(
                    "total" => $payable->count_all(),
                    "data" => $data
                );
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/finance/payableedit");
            } else {
                $this->template->content = new View("bms/finance/payable");
            }
        }
    }

}
