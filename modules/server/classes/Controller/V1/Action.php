<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_V1_Action extends Controller_V1 {

    /**
     * 初始化广告信息
     */
    public function action_init() {
        $resp = new Resp();
        $req = Arr::extract($this->query, array("imsi", "imei", "os", "brand", "term", "carrier", "odin", "net", "appid", "channel", "os_ver", "app_ver"));
        $req["ip"] = Request::$client_ip;
        $city = IpUtil::convertip_all();
        if ($req["appid"] && $req["channel"]) {
            //try {
            $data = array(
                "channel" => $req["channel"],
                "appid" => $req["appid"],
                "imsi" => $req["imsi"],
                "odin" => $req["odin"],
                "imei" => $req["imei"],
                "os" => $req["os"],
                "brand" => $req["brand"],
                "term" => $req["term"],
                "carrier" => $req["carrier"],
                "os_ver" => $req["os_ver"],
                "app_ver" => $req["app_ver"],
                "ip" => $req["ip"],
                "province" => $city[0],
                "city" => $city[1]
            );
            $exist = DB::select("id", "channel")->from("visitor")
                            ->where("appid", "=", $data["appid"])
                            ->where("odin", "=", $data["odin"])->limit(1)->execute("core")->current();
            if ($exist["id"]) {
                $upq = DB::update("visitor")->value("app_ver", $data["app_ver"])
                        ->value("ip", $data["ip"])->value("city", $data["city"])
                        ->value("imsi", $data["imsi"])
                        ->value("brand", $data["brand"])
                        ->value("term", $data["term"])
                        ->value("carrier", $data["carrier"])
                        ->value("active_time", date("Y-m-d H:i:s"));
                if (!$exist["channel"]) {
                    $upq->value("channel", $data["channel"]);
                }

                $upq->where("id", "=", $exist["id"])->execute("core");
            } else {
                DB::insert("visitor")->columns(array_keys($data))->values(array_values($data))->execute("core");
            }
            /*

              } catch (Exception $ex) {
              Kohana::$log->add(Kohana_Log::ERROR, $ex->getMessage());
              } */
            $config = Kohana::$config->load("sys")->as_array();
            $resp->config = $config;
        } else {
            $resp->ret = 400;
            $resp->msg = "appid and channel can't be null";
        }


        echo $resp;
    }

    /**
     * 提交广告效果
     */
    public function action_event() {
        $resp = new Resp();
        $req = Arr::extract($this->query, array("appid", "channel", "action", "ad_id", "token", "ad_id", "task_id"));
        $user = UserToken::decode($req["token"]);
        if ($req["task_id"]) {
            $task = array(
                "ad_id" => $req["ad_id"],
                "appid" => $req["appid"],
                "channel" => $req["channel"],
                "task_id" => $req["task_id"],
                "vid" => $user[0],
                "uid" => $user[1],
                "time" => time(),
                "ip" => Request::$client_ip
            );
            DB::insert("user_adevents")->columns(array_keys($task))->values(array_values($task))->execute("core");
        } else {
            $event = array(
                "ad_id" => $req["ad_id"],
                "appid" => $req["appid"],
                "channel" => $req["channel"],
                "action" => $req["action"],
                "vid" => $user[0],
                "uid" => $user[1],
                "time" => time(),
                "ip" => Request::$client_ip
            );
            DB::insert("user_adevents")->columns(array_keys($event))->values(array_values($event))->execute("core");
        }
        echo $resp;
    }

    public function action_upload() {
        $resp = new Resp();
        $req = Arr::extract($this->query, array("token", "task_id", "date", "extra"));
        echo $resp;
    }

    /**
     * 记录App异常
     */
    public function action_err() {
        $resp = new Resp();
        echo $resp;
    }

}
