<?php

/**
 * Description of AppConfig
 *
 * @author jiwei
 */
class AppConfig {

    protected $_app_id;

    /**
     *
     * @param type $app_id
     * @return \AppConfig
     */
    public static function Instance($app_id) {
        return new AppConfig($app_id);
    }

    public function __construct($app_id) {
        $this->_app_id = $app_id;
    }

    public function save($config) {
        DB::delete("app_config")->where("app_id", "=", $this->_app_id)->execute("core");
        $iq = DB::insert("app_config")->columns(array("app_id", "group", "key", "value"));
        foreach ($config as $g => $k) {
            foreach ($k as $i => $v) {
                if ($i !== '_name') {
                    $iq->values(array($this->_app_id, $g, $i, $v["value"]));
                }
            }
        }
        $iq->execute("core");
    }

    public function load() {
        $config = Kohana::$config->load("app")->as_array();
        $cus = DB::select()->from("app_config")->where("app_id", "=", $this->_app_id)->execute("core")->as_array();
        foreach ($cus as $c) {
            if (isset($config[$c["group"]][$c["key"]])) {
                $config[$c["group"]][$c["key"]]["value"] = $c["value"];
            }
        }
        return $config;
    }

    public static function cache($app_id) {
        $app = Cache::instance()->get("appconfig_" . $app_id);
        if (!$app) {
            $app = DB::select("id","platform","package_name","tags","sdk_ver","grade")->from("app")->where("app_id", "=", $app_id)->execute("core")->current();
            if (!$app["id"]) {
                $app = array();
            }
            $config = Kohana::$config->load("app")->as_array();
            $cus = DB::select()->from("app_config")->where("app_id", "=", $app_id)->execute("core")->as_array();
            foreach ($cus as $c) {
                if (isset($config[$c["group"]][$c["key"]])) {
                    $config[$c["group"]][$c["key"]]["value"] = $c["value"];
                }
            }
            $app = $app + $config;
            Cache::instance()->set("appconfig_" . $app_id, $app, 5);
            $config = null;
            $cus = null;
        }
        return $app;
    }

}
