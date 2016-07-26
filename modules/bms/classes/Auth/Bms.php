<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth_Bms
 *
 * @author jiwei
 */
class Auth_Bms extends Auth {

    public static function instance() {
        if (!isset(self::$_instance)) {
            // Load the configuration for this type
            $config = Kohana::$config->load('auth');

            // Create a new session instance
            self::$_instance = new Auth_Bms($config);
        }

        return self::$_instance;
    }

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_config['session_key'] = "bms_token";
    }

    protected function _login($username, $password, $remember) {
        $exist = DB::select()->from("admin_user")->where("username", "=", $username)->execute("core")->current();
        if ($exist["status"] == "actived" && $this->hash($password) == $exist["password"]) {
            return $this->complete_login(array(
                        "user_id" => $exist["user_id"],
                        "username" => $exist["username"],
                        "realname" => $exist["realname"]
            ));
        } else {
            return false;
        }
    }

    public function get_permission() {
        $u = $this->get_user();
        if ($u) {
            $sql = "SELECT GROUP_CONCAT(DISTINCT permission)as permission FROM admin_role_permission a JOIN admin_user_role b ON a.role_id=b.role_id WHERE b.user_id={$u["user_id"]}";
            $p = DB::query(Database::SELECT, $sql)->execute("core")->current();
            return explode(",", $p["permission"]);
        } else {
            return array();
        }
    }

    public function check_password($password) {
        
    }

    public function password($username) {
        
    }

//put your code here
}
