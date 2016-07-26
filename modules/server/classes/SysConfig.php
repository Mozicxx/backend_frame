<?php

/**
 * Description of SysConfig
 *
 * @author jiwei
 */
class SysConfig {

    /**
     * 
     * @return \SysConfig
     */
    public static function Instance() {
        return new SysConfig();
    }

    public function __construct() {
        
    }

    public function save($config) {
        DB::delete("sys_config")->execute("core");
        $iq = DB::insert("sys_config")->columns(array("app_id", "group", "key", "value"));
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
        $cus = DB::select()->from("sys_config")->execute("core")->as_array();
        foreach ($cus as $c) {
            if (isset($config[$c["group"]][$c["key"]])) {
                $config[$c["group"]][$c["key"]]["value"] = $c["value"];
            }
        }
        return $config;
    }

}
