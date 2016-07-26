<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_V1_User321 extends Controller_V1 {

    public function action_register() {
        echo Debug::vars(IpUtil::convertip_all());
    }

    /**
     * 用户登录
     */
    public function action_login() {
        $resp = $this->buildResp();
        $qeury = Arr::extract($this->query, array("odin", "username", "password"));
        $visitor = DB::select("id", "user_id")->from("visitor")->where("odin", "=", $qeury["odin"])->execute("mkmoney")->current();
        $user = null;
        if (!$visitor["id"]) {
            $resp->ret = 403;
            $resp->msg = "authrized faild!";
        } else {
            if ($qeury["username"]) {
                $exist = DB::select()->from("user")->where("username", "=", $qeury["username"])->execute("mkmoney")->current();
                if ($exist["id"] && $exist["password"] == Auth::instance()->hash($qeury["password"])) {
                    $user = $exist;
                } else {
                    $resp->ret = 403;
                    $resp->msg = "authrized faild!";
                }
            } elseif ($visitor["id"]) {
                $exist = DB::select()->from("user")->where("id", "=", $visitor["user_id"])->execute("mkmoney")->current();
                if (!$exist["id"]) {
                    $city = IpUtil::convertip_all();
                    $user = array(
                        "add_time" => time(),
                        "province" => $city[0],
                        "city" => $city[1]
                    );
                    $ret = DB::insert("user_access_log")->columns(array_keys($user))->values(array_values($user))->execute("mkmoney");
                    $user["id"] = $ret[0];
                    DB::update("visitor")->value("user_id", $user["id"])->where("id", "=", $visitor["id"])->execute("mkmoney");
                } else {
                    $user = $exist;
                }
            }
            $token = null;
            if ($user) {
                $balance = Model_UserBills::get_balance($user["id"]);
                $user = Arr::extract($user, array("id", "username", "nickname", "avatar", "sex", "birthday"));
                $user["balance"] = $balance;
                $token = UserToken::encode($visitor["id"], $user["id"]);
            }
            $resp->token = $token;
            $resp->user = $user;
        }
        echo $resp;
    }

    /**
     * 获取/更新用户信息
     */
    public function action_update() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("token", "odin", "username", "password", "nickname", "sex", "birthday"));
        Kohana::$log->add(Kohana_Log::DEBUG, json_encode($this->request->query(),JSON_UNESCAPED_UNICODE));
        $user = UserToken::decode($req["token"]);
        if (count($user) == 2) {
            $update = DB::update("user");
            if ($req["username"] && $req["password"]) {
                $update->value("username", $req["username"])->value("password", Auth::instance()->hash($req["password"]));
            }
            if ($req["nickname"]) {
                $update->value("nickname", $req["nickname"]);
            }
            if ($req["sex"]) {
                $update->value("sex", $req["sex"]);
            }
            if ($req["birthday"]) {
                $update->value("birthday", $req["birthday"]);
            }
            $update->value("last_update", date("Y-m-d H:i:s"));
            $update->where("id", "=", $user[1])->execute("mkmoney");
        }
        echo $resp;
    }

    /**
     * 查询用户余额
     */
    public function action_balance() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("token", "odin"));
        $user = UserToken::decode($req["token"]);
        if (count($user) == 2) {
            $resp->balance = Model_UserBills::get_balance($user[1]);
        }
        echo $resp;
    }

    /**
     * 获取用户账单详情
     */
    public function action_bills() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("token", "odin", "page_size", "page"));
        $user = UserToken::decode($req["token"]);
        if (count($user) == 2) {
            $total = DB::select(DB::expr("count(*)as total"))->from("user_bills")->where("user_id", "=", $user[1])->execute("mkmoney")->current();
            $bills = DB::select()->from("user_bills")->where("user_id", "=", $user[1])->order_by("id", "DESC")->execute("mkmoney")->as_array();
            $resp->data = array(
                "total" => $total["total"],
                "bills" => $bills
            );
        }

        echo $resp;
    }

    /**
     * 获取用户消息
     */
    public function action_msg() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("token", "odin"));
        $user = UserToken::decode($req["token"]);
        if (count($user) == 2) {
            
        }

        echo $resp;
    }

}
