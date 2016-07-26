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

    public function get_roles() {
        $roles = DB::select("role_id")->from("admin_user_role")->where("user_id", "=", $this->user_id)->execute("core")->as_array("role_id");
        return array_keys($roles);
    }

    public function set_roles($roles) {
        DB::delete("admin_user_role")->where("user_id", "=", $this->user_id)->execute("core");
        if (is_array($roles) && $roles) {
            $query = DB::insert("admin_user_role")->columns(array("user_id", "role_id"));
            foreach ($roles as $role) {
                $query->values(array($this->user_id, $role));
            }
            $query->execute("core");
        }
    }

}
