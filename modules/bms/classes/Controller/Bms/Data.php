<?php

defined('SYSPATH') or die('No direct script access.');

/**
 *
 */
class Controller_Bms_Data extends Controller {

    public function action_feetype() {
        $feetype = array(
            array("id" => "CPA", "text" => "CPA"),
            array("id" => "CPC", "text" => "CPC")
        );
        echo json_encode($feetype);
    }

    public function action_adtype() {
        $adtype = array(
            array("id" => "banner", "text" => "互动广告"),
            array("id" => "pop", "text" => "插屏广告"),
            array("id" => "offer", "text" => "积分墙"),
            array("id" => "push", "text" => "推送广告"),
            array("id" => "ext1", "text" => "橱窗广告")
        );
        echo json_encode($adtype);
    }

    public function action_tags() {
        $tags = Kohana::$config->load("tags")->as_array();
        $data = array();
        foreach ($tags as $k => $v) {
            //$data[] = array("id" => $k, "text" => $k);
            if (is_array($v)) {
                foreach ($v as $m => $n) {
                    //$data[] = array("id" => $m, "text" => "　$m");
                    if (is_array($n)) {
                        foreach ($n as $a) {
                            //$data[] = array("id" => $a, "text" => $a, "pid" => $m);
                            $data[] = array("id" => "$k|$m|$a", "text" => "$k|$m|$a");
                        }
                    }
                }
            }
        }
        echo json_encode($data);
    }
    
        public function action_tagsgroup() {
        $tags = Kohana::$config->load("tags")->as_array();
        $data = array();
        foreach ($tags as $k => $v) {
            //$data[] = array("id" => $k, "text" => $k);
            if (is_array($v)) {
                foreach ($v as $m => $n) {
                    //$data[] = array("id" => $m, "text" => "　$m");
                    if (is_array($n)) {
                        foreach ($n as $a) {
                            //$data[] = array("id" => $a, "text" => $a, "pid" => $m);
                            $data[] = array("id" => "$k|$m|$a", "text" => "$a");
                        }
                    }
                }
            }
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

    public function action_status() {
        $status = array(
            array("id" => "pendding", "text" => "未生效"),
            array("id" => "actived", "text" => "已生效")
        );
        echo json_encode($status);
    }

    public function action_copertype() {
        $copertype = array(
            array("id" => "proxy", "text" => "代理客户"),
            array("id" => "direct", "text" => "直接客户")
        );
        echo json_encode($copertype);
    }

    public function action_online() {
        $copertype = array(
            array("id" => "online", "text" => "已上线"),
            array("id" => "offline", "text" => "未上线")
        );
        echo json_encode($copertype);
    }

    public function action_admin() {
        $users = DB::select("username", "realname")->from("admin_user")->execute("core")->as_array();
        $admin = array();
        if ($users) {
            foreach ($users as $u) {
                $admin[] = array("id" => $u["username"], "text" => $u["realname"], "pinyin" => Pinyin::utf8_to($u["realname"]));
            }
        }
        echo json_encode($admin);
    }

    public function action_advertiser() {
        $key = $this->request->post("key");
        $users = DB::select("id", "s_name", "company")->where("name", "like", "%$key%")->from("advertiser")->execute("core")->as_array();
        $adv = array();
        if ($users) {
            foreach ($users as $u) {
                $adv[] = array("id" => $u["id"], "text" => $u["s_name"], "pinyin" => Pinyin::utf8_to($u["s_name"]));
            }
        }
        echo json_encode($adv);
    }

    public function action_developer() {
        $key = $this->request->post("key");
        $users = DB::select("id", "email", "name")->where("email", "like", "%$key%")->from("user")->execute("core")->as_array();
        $dev = array();
        if ($users) {
            foreach ($users as $u) {
                $dev[] = array("id" => $u["id"], "text" => $u["email"], "pinyin" => Pinyin::utf8_to($u["name"]));
            }
        }
        echo json_encode($dev);
    }

    public function action_ad() {
        $key = $this->request->post("key");
        $ads = DB::select("id", "name", "ad_type")->from("advertisement")->where("name", "like", "%$key%")->limit(10)->execute("core")->as_array();
        if ($ads) {
            foreach ($ads as $u) {
                $adv[] = array("id" => $u["id"], "text" => $u["name"], "pinyin" => Pinyin::utf8_to($u["name"]));
            }
        }
        echo json_encode($adv);
    }

    public function action_app() {
        $key = $this->request->post("key");
        $apps = DB::select("app_id", "app_name", "platform")->from("app")->where("app_name", "like", "%$key%")->limit(10)->execute("core")->as_array();
        $app = array();
        if ($apps) {
            foreach ($apps as $u) {
                $app[] = array("id" => $u["app_id"], "text" => $u["app_name"], "pinyin" => Pinyin::utf8_to($u["app_name"]));
            }
        }
        echo json_encode($app);
    }

    public function action_adminroles() {
        $roles = DB::select()->from("admin_role")->execute("core")->as_array();
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

    public function action_app_tag() {
        $tags = array(
            "生活服务" => "生活服务",
            "系统工具" => "系统工具",
            "虚拟定位" => "虚拟定位",
            "办公学习" => "办公学习",
            "积分兑换" => "积分兑换",
            "新闻阅读" => "新闻阅读",
            "图片漫画" => "图片漫画",
            "桌面美化" => "桌面美化",
            "影音媒体" => "影音媒体",
            "美女两性" => "美女两性",
            "角色扮演" => "角色扮演",
            "动作街机" => "动作街机",
            "飞行射击" => "飞行射击",
            "体育竞技" => "体育竞技",
            "棋牌竞猜" => "棋牌竞猜",
            "经营策略" => "经营策略",
            "休闲益智" => "休闲益智",
            "网络游戏" => "网络游戏",
        );
        $data = array();
        foreach ($tags as $tag) {
            $data[] = array("id" => $tag, "text" => $tag);
        }
        echo json_encode($data);
    }

    public function action_hour() {
        $data = array();
        for ($i = 0; $i < 24; $i++) {
            $data[] = array("id" => $i, "text" => str_pad($i, 2, 0, STR_PAD_LEFT) . ":00 - " . str_pad($i + 1, 2, 0, STR_PAD_LEFT) . ":00");
        }
        echo json_encode($data);
    }

    public function action_scwh() {
        $scwhs = array("320x240", "240x320", "480x320", "320x480", "800x480", "640x960", "540x960", "480x960", "480x854", "480x800", "854x480", "960x540", "960x640", "768x1280", "720x1280", "1024x768", "1024x600", "1080x1920", "800x1280", "1280x800");
        $data = array();
        foreach ($scwhs as $scwh) {
            $data[] = array("id" => $scwh, "text" => $scwh);
        }
        echo json_encode($data);
    }

    public function action_term_price() {
        $price = array("700-", "700-1000", "1000-1500", "1500-2000", "2000-2500", "2500-3000", "3000-3500", "3500-4000", "4000+");
        $data = array();
        foreach ($price as $p) {
            if ($p == "700-") {
                $text = "700元以下";
            } elseif ($p == "4000+") {
                $text = "4000元以上";
            } else {
                $text = $p . "元";
            }
            $data[] = array("id" => $p, "text" => $text);
        }
        echo json_encode($data);
    }

    public function action_android_ver() {
        $vers = array(
            "3" => "Android1.5",
            "4" => "Android1.6",
            "5" => "Android2.0",
            "6" => "Android2.0.1",
            "7" => "Android2.1",
            "8" => "Android2.2",
            "9" => "Android2.3",
            "10" => "Android2.3.3",
            "11" => "Android3.0",
            "12" => "Android3.1",
            "13" => "Android3.2",
            "14" => "Android4.0.1",
            "15" => "Android4.0.3",
            "16" => "Android4.1",
            "17" => "Android4.2",
            "18" => "Android4.3",
            "19" => "Android4.4",
        );
        $data = array();
        foreach ($vers as $id => $text) {
            $data[] = array("id" => $id, "text" => $text . "以上");
        }
        echo json_encode($data);
    }

    public function action_ios_ver() {
        $vers = array(
            "3" => "IOS3",
            "4" => "IOS4",
            "5" => "IOS5",
            "6" => "IOS6",
            "7" => "IOS7",
        );
        $data = array();
        foreach ($vers as $id => $text) {
            $data[] = array("id" => $id, "text" => $text . "以上");
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

}
