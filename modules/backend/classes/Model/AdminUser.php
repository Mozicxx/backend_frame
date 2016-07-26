<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminUser
 *
 * @author jiwei
 */
class Model_AdminUser extends ORM {

    protected $_table_name = "admin_user";
    protected $_primary_key = "user_id";
    protected $_db_group = "hotgirl";

    public function get_roles() {
        $roles = DB::select("role_id")->from("admin_user_role")->where("user_id", "=", $this->user_id)->execute("hotgirl")->as_array("role_id");
        return array_keys($roles);
    }

    public function set_roles($roles) {
        DB::delete("admin_user_role")->where("user_id", "=", $this->user_id)->execute("hotgirl");
        if (is_array($roles) && $roles) {
            $query = DB::insert("admin_user_role")->columns(array("user_id", "role_id"));
            foreach ($roles as $role) {
                $query->values(array($this->user_id, $role));
            }
            $query->execute("mkmoney");
        }
    }

    public function get_name($username) {
        $realname = DB::select("realname")->from("admin_user")->where("username", "=", $username)->limit(1)->execute("mkmoney")->current();
        return $realname["realname"];
    }

}
