<?php

defined('SYSPATH') or die('No direct script access.');

/**
 *
 */
class Controller_Backend_Data extends Controller {


    public function action_admin() {
        $users = DB::select("username", "realname")->from("admin_user")->execute("mkmoney")->as_array();
        $admin = array();
        if ($users) {
            foreach ($users as $u) {
                $admin[] = array("id" => $u["username"], "text" => $u["realname"]);
            }
        }
        echo json_encode($admin);
    }
    public function action_lib_channel(){
        $key = $this->request->query('status');
        $channel = DB::select('real_id','name')->from('channel')->where('status','=',$key)->execute('hotgirl')->as_array();
        $channels = [];
        if($channel){
            foreach ($channel as $c){
                $channels[] = array('id'=>$c['real_id'],'text'=>$c['name']);
            }
        }
        echo json_encode($channels);
    }

    public function action_idm_cate(){
        $channel = DB::select('id','name')->from('category')->execute('indoorsman')->as_array();
        $channels = [];
        if($channel){
            foreach ($channel as $c){
                $channels[] = array('id'=>$c['id'],'text'=>$c['name']);
            }
        }
        echo json_encode($channels);
    }

    public function action_app() {
//        $apps = DB::select("id", "app_name")->where("app_name", "like", "%$key%")->from("app")->order_by("pub_type", "DESC")->limit(100)->execute("mkmoney")->as_array();
        $apps = DB::select("id","app_name")->from("app")->order_by("pub_type", "DESC")->limit(100)->execute("hotgirl")->as_array();
        $app = array();
        if ($apps) {
            foreach ($apps as $u) {
                $app[] = array("id" => $u["id"], "text" => $u["app_name"]);
            }
        }
        echo json_encode($app);
    }

    public function action_company() {
        $key = $this->request->post("key");
        $company = DB::select("id", "company", "contact")->from("fee_channel_company")->where("company", "like", "%$key%")->limit(1000)->execute("mkmoney")->as_array();
        $app = array();
        if ($company) {
            foreach ($company as $u) {
                $app[] = array("id" => $u["id"], "text" => $u["company"]);
            }
        }
        echo json_encode($app);
    }

    public function action_feeapp() {
        $company = $this->request->query("company");
        $apps = DB::select(DB::expr("app_id as id"), DB::expr("app_name as text"))->from("fee_app")->where("channel_company", "=", $company)->execute("mkmoney")->as_array();
        echo json_encode($apps);
    }

    public function action_pubchannel() {
        $key = $this->request->post("key");
        $company = DB::select("id", "channel_name", "contact")->from("pub_channel")->where("channel_name", "like", "%$key%")->limit(1000)->execute("mkmoney")->as_array();
        $app = array(array("id" => "unknow", "text" => "未知"));
        if ($company) {
            foreach ($company as $u) {
                $app[] = array("id" => $u["id"], "text" => $u["channel_name"]);
            }
        }
        echo json_encode($app);
    }

    public function action_adminroles() {
        $roles = DB::select()->from("admin_role")->execute("mkmoney")->as_array();
        $data = array();
        if ($roles) {
            foreach ($roles as $role) {
                $data[] = array("id" => $role["role_id"], "text" => $role["role_name"]);
            }
        }
        echo json_encode($data);
    }

    public function action_hours() {
        $data = array();
        for ($i = 0; $i < 24; $i++) {
            $data[] = array("id" => $i, "text" => str_pad($i, 2, "0", STR_PAD_LEFT) . ":00 - " . str_pad($i + 1, 2, "0", STR_PAD_LEFT) . ":00");
        }
        echo json_encode($data);
    }

    public function action_city() {
        $citys = array("北京", "上海", "天津", "重庆", "广东", "江苏", "浙江", "福建", "河北", "河南", "山东", "山西", "湖北", "湖南", "安徽", "江西", "四川", "贵州", "云南", "广西", "辽宁", "吉林", "黑龙江", "内蒙古", "陕西", "甘肃", "青海", "宁夏", "新疆", "西藏", "海南", "香港", "澳门", "台湾");

        $data = array();
        foreach ($citys as $city) {
            $data[] = array("id" => $city, "text" => $city);
        }
        echo json_encode($data);
    }

    public function action_carrier() {
        $data = array(
            array("id" => "CMCC", "text" => "中国移动"),
            array("id" => "CTCC", "text" => "中国电信"),
            array("id" => "CUCC", "text" => "中国联通"),
            array("id" => "unknown", "text" => "未知/无卡")
        );
        echo json_encode($data);
    }

    public function action_feetype() {
        $data = array(
            array("id" => "sms", "text" => "短信"),
        );
        echo json_encode($data);
    }

    public function action_feesdk() {
        $data = array(
            /*             * array("id" => "mm", "text" => "移动MM市场"),
              array("id" => "cmgc", "text" => "移动和游戏"),
              array("id" => "wostore", "text" => "联通沃商店"),
              array("id" => "189store", "text" => "天翼空间"),
              array("id" => "play", "text" => "电信爱游戏"),
              array("id" => "cmgcd", "text" => "Android游戏道具平台"),* */
            array("id" => "sms", "text" => "短信计费代码"),
            array("id" => "common", "text" => "通用破解服务"),
            array("id" => "extra", "text" => "外部破解服务"),
            array("id" => "qpay", "text" => "服务器破解"),
        );
        echo json_encode($data);
    }

    public function action_hour() {
        $data = array();
        for ($i = 0; $i < 24; $i++) {
            $data[] = array("id" => $i, "text" => str_pad($i, 2, 0, STR_PAD_LEFT) . ":00 - " . str_pad($i + 1, 2, 0, STR_PAD_LEFT) . ":00");
        }
        echo json_encode($data);
    }

    public function action_net() {
        $net = array(
            "wifi" => "WIFI",
            "3G" => "3G",
            "2G" => "2G",
            "WAP" => "WAP",
            "unknow" => "未知",
        );
        $data = array();
        foreach ($net as $id => $text) {
            $data[] = array("id" => $id, "text" => $text);
        }
        echo json_encode($data);
    }

    public function action_platform() {
        $platform = array(
            array("id" => "Android", "text" => "Android"),
            array("id" => "IOS", "text" => "IOS")
        );
        echo json_encode($platform);
    }

    public function action_developer() {
        $key = $this->request->post("key");
        $users = DB::select("cpid", "username", "contact")->where("username", "like", "%$key%")->from("cp_manager")->execute("mkmoney")->as_array();
        $dev = array();
        if ($users) {
            foreach ($users as $u) {
                $dev[] = array("id" => $u["cpid"], "text" => $u["username"]);
            }
        }
        echo json_encode($dev);
    }

    public function action_firm() {
        $key = $this->request->post("key");
        $firms = DB::select("id", "name", "fee")->where("name", "like", "%$key%")->from("firm")->execute("hotgirl")->as_array();
        $dev = array();
        if ($firms) {
            foreach ($firms as $u) {
                $dev[] = array("id" => $u["id"], "text" => $u["name"]);
            }
        }
        echo json_encode($dev);
    }

    public function action_cpgroup() {
        $users = DB::select("group_id", "group_name")->from("cp_group")->execute("mkmoney")->as_array();
        $dev = array();
        if ($users) {
            foreach ($users as $u) {
                $dev[] = array("id" => $u["group_id"], "text" => $u["group_name"]);
            }
        }
        echo json_encode($dev);
    }

    public function action_basecompany() {
        $users = DB::select("id", "company_name")->from("finance_base_company")->execute("mkmoney")->as_array();
        $dev = array();
        if ($users) {
            foreach ($users as $u) {
                $dev[] = array("id" => $u["id"], "text" => $u["company_name"]);
            }
        }
        echo json_encode($dev);
    }

    public function action_province() {
        foreach (City::$province as $province => $citys) {
            $provinces[] = array("id" => $province, "text" => $province);
        }
        echo json_encode($provinces, JSON_UNESCAPED_UNICODE);
    }

    public function action_feegroup() {
        $query = DB::select("group_id", "group_name")->from("fee_channel_group");
        $key = $this->request->post("key");
        $value = $this->request->post("value");
        if ($value) {
            $query->where("group_id", "not in", explode(",", $value));
        }
        if ($key) {
            $query->where("group_name", "like", "%$key%");
        }
        $users = $query->limit(1000)->execute("mkmoney")->as_array();
        $dev = array();
        if ($users) {
            foreach ($users as $u) {
                $dev[] = array("id" => $u["group_id"], "text" => $u["group_name"]);
            }
        }
        echo json_encode($dev);
    }


}
