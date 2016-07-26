<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 广告
  广告概况
  广告接入录入
  广告调配
  广告主维护
 */
class Controller_Bms_My extends Controller_Bms {

    public function action_index() {
        $this->template->content = new View("bms/ad/index");
    }

    public function action_adincome() {
        
    }

    public function action_receivable() {
        
    }

    public function action_developer() {
        $developer = ORM::factory("Developer");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $users = $developer->find_all()->as_array();
                $data = array();
                foreach ($users as $v) {
                    $v->add = date("Y-m-d", $v->add);
                    $data[] = $v->as_array();
                }
                unset($users);
                $res = array(
                    "total" => $developer->count_all(),
                    "data" => $data
                );
            } elseif ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $user) {
                    unset($user->add);
                    unset($user->last_login);
                    $developer->where("id", "=", $user->id)->find()
                            ->values((array) $user)->save();
                }
            } elseif ($method == "get") {
                $res = $developer->where("id", "=", $this->request->query("id"))->find()->as_array();
                $res["add"] = date("Y-m-d H:i", $res["add"]);
                $res["last_login"] = date("Y-m-d H:i", $res["last_login"]);
            } elseif ($method == "delete") {
                $developer->where("id", "=", $this->request->query("id"))->find()->delete();
            }
            echo json_encode($res);
            die();
        } else {
            if ($this->request->param("id")) {
                $this->template->content = new View("bms/developer/edit");
            } else {
                $this->template->content = new View("bms/developer/list");
            }
        }
    }

    public function action_advertiser() {
        $advertiser = ORM::factory("Advertiser");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "list") {
                $advs = $advertiser->find_all()->as_array();
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
                    $adver->update_time = time();
                    $ads = $advertiser->where("id", "=", $adver->id)->find();
                    if (!$ads->add_time) {
                        $ads->add_time = time();
                    }
                    $ads->values((array) $adver)->save();
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

}
