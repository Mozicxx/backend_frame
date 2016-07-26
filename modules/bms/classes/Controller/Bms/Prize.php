<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Bms_Prize extends Controller_Bms {

    public function action_send() {
        $gift = ORM::factory("PrizeLog");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $gifts = $gift->where("send_by", "=", "0")->where("gift_type", "=", "gift")
                        ->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                $apps = array();
                foreach ($gifts as $v) {
                    $v->add_time = $v->add_time ? date("Y-m-d H:i", $v->add_time) : "--";
                    $v->send_time = $v->send_time ? date("Y-m-d H:i", $v->send_time) : "==";
                    $data[] = $v->as_array();
                    $apps[] = $v->app_id;
                }
                if ($apps) {
                    $apps = DB::select("app_id", "app_name")->from("app")->where("app_id", "in", $apps)->execute("core")->as_array("app_id");
                }
                foreach ($data as $i => $v) {
                    $data[$i]["app_id"] = isset($apps[$v["app_id"]]) ? $apps[$v["app_id"]]["app_name"] : $v["app_id"];
                }
                unset($gifts);
                $gift->where("send_by", "=", "0")->where("gift_type", "=", "gift");
                $res = array(
                    "total" => $gift->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $a) {
                    $a->send_user = $this->manager_name;
                    $a->send_time = time();
                    $gift->where("prize_id", "=", $a->prize_id)->find()
                            ->values((array) $a)->save();
                }
            } elseif ($method == "get") {
                $res = $gift->where("prize_id", "=", $this->request->query("id"))->find()->as_array();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/prize/prize_send");
            } else {
                $this->template->content = new View("bms/prize/prize");
            }
        }
    }

    public function action_drawgift() {
        $gift = ORM::factory("PrizeGift");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $gifts = $gift->where("app_id", "=", "0")
                        ->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                foreach ($gifts as $v) {
                    $v->add_time = date("Y-m-d", $v->add_time);
                    $data[] = $v->as_array();
                }
                unset($gifts);
                $res = array(
                    "total" => 5,
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $a) {
                    $a->add_by = $this->manager_name;
                    $a->add_time = time();
                    $gift->where("gift_id", "=", $a->gift_id)->find()
                            ->values((array) $a)->save();
                }
            } elseif ($method == "get") {
                $res = $gift->where("gift_id", "=", $this->request->query("id"))->find()->as_array();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/prize/prize_edit");
            } else {
                $this->template->content = new View("bms/prize/prize_list");
            }
        }
    }

    public function action_signgift() {
        $gift = ORM::factory("SigninGift");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder"));
                $gifts = $gift->where("app_id", "=", "0")
                        ->order_by($query["sortField"], $query["sortOrder"])
                        ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])
                        ->find_all();
                $data = array();
                foreach ($gifts as $v) {
                    $v->add_time = date("Y-m-d", $v->add_time);
                    $data[] = $v->as_array();
                }
                unset($gifts);
                $res = array(
                    "total" => 5,
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $a) {
                    $a->add_by = $this->manager_name;
                    $a->add_time = time();
                    $gift->where("gift_id", "=", $a->gift_id)->find()
                            ->values((array) $a)->save();
                }
            } elseif ($method == "get") {
                $res = $gift->where("gift_id", "=", $this->request->query("id"))->find()->as_array();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/prize/signin_edit");
            } else {
                $this->template->content = new View("bms/prize/signin_list");
            }
        }
    }

    public function action_gamecode() {
        $id = $this->request->param("id");
        $method = $this->request->query("method");
        $gamepack = ORM::factory("GamePack");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $query = Arr::extract($this->request->post(), array("pageSize", "pageIndex", "sortField", "sortOrder", "key", "ad_type"));
                if ($query["key"]) {
                    $gamepack->where("pack_name", "like", "%" . $query["key"] . "%");
                }
                $packs = $gamepack->where("status", "!=", "deleted")->order_by($query["sortField"], $query["sortOrder"])
                                ->limit($query["pageSize"])->offset($query["pageSize"] * $query["pageIndex"])->find_all()->as_array();
                $data = array();
                $adid = array();
                $packid = array();
                foreach ($packs as $pack) {
                    $pack = $pack->as_array();
                    $data[] = $pack;
                    $adid[] = $pack["ad_id"];
                    $packid[] = $pack["id"];
                }
                if ($adid) {
                    $ads = DB::select("id", "name")->from("advertisement")->where("id", "in", $adid)->execute("core")->as_array("id");
                    $codes = DB::select("pack_id", array(DB::expr("COUNT(*)"), "total"), array(DB::expr("COUNT(CASE WHEN `status`='new' THEN 1 END)"), "new"))
                                    ->from("game_code")->group_by("pack_id")->where("pack_id", "in", $packid)->execute("prize")->as_array("pack_id");
                }
                foreach ($data as $k => $v) {
                    $data[$k]["ad_name"] = isset($ads[$v["ad_id"]]) ? $ads[$v["ad_id"]]["name"] : $v["ad_id"];
                    $data[$k]["total"] = isset($codes[$v["id"]]) ? $codes[$v["id"]]["total"] : 0;
                    $data[$k]["new"] = isset($codes[$v["id"]]) ? $codes[$v["id"]]["new"] : 0;
                }
                if ($query["key"]) {
                    $gamepack->where("pack_name", "like", "%" . $query["key"] . "%");
                }
                $gamepack->where("status", "!=", "deleted");
                $res = array(
                    "total" => $gamepack->count_all(),
                    "data" => $data
                );
            } elseif ($method == "get") {
                $pack = $gamepack->where("id", "=", $this->request->query("id"))->find()->as_array();
                if ($pack["ad_id"]) {
                    $ad = DB::select("name")->from("advertisement")->where("id", "=", $pack["ad_id"])->execute("core")->current();
                    $pack["ad_name"] = $ad["name"];
                }
                $res = $pack;
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $pack) {
                    $pack->add_by = $this->manager_name;
                    $gamepack->where("id", "=", $pack->id)->find()
                            ->values((array) $pack)->save();
                }
            } elseif ($method == "load") {
                $pack_id = $this->request->post("pack_id");
                $file = fopen($_FILES["filedata"]["tmp_name"], "r");
                $total = 0;
                while (!feof($file)) {
                    $code = fgetcsv($file);
                    if ($code) {
                        try {
                            DB::insert("game_code")->columns(array("pack_id", "code"))->values(array($pack_id, $code))->execute("prize");
                            $total++;
                        } catch (Exception $ex) {
                            
                        }
                    }
                }
                fclose($file);
                $res = $total;
            } elseif ($method == "status") {
                $ids = $this->request->query("id");
                $ids = $ids ? explode(",", $ids) : array();
                $status = $this->request->query("status");
                foreach ($ids as $id) {
                    ORM::factory("GamePack")->where("id", "=", $id)->find()->values(array("status" => $status))->save();
                }
            }
            echo json_encode($res);
            die;
        } else {
            if ($id == "edit") {
                $this->template->content = new View("bms/prize/gamecode_edit");
            } else {
                $this->template->content = new View("bms/prize/gamecode");
            }
        }
    }

}
