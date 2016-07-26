<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Backend_Home extends Controller_Backend {

    public function action_index() {
        $this->template->content = new View("backend/index", array("username" => $this->manager_name));
    }

    public function action_upload() {
        if ($_POST) {
            new Uploader();
            die;
        }
        $this->template = new View("backend/upload");
    }

    public function action_menu() {
        $menu = Kohana::$config->load("backend")->as_array();
        $arr = array();
        foreach ($menu as $k => $g) {
            $flag = 0;
            foreach ($g as $i => $m) {
                if (is_array($m) && in_array($m["uri"], $this->permission) && $m["display"]) {
                    $arr[] = array("id" => $m["id"], "pid" => $g["id"], "text" => $m["name"], "iconCls" => $m["id"], "url" => URL::site($m["uri"], TRUE));
                    $flag++;
                }
            }
            if ($flag) {
                $arr[] = array("id" => $g["id"], "text" => $k, "iconCls" => $g["id"]);
            }
        }
        echo json_encode($arr);
        die;
    }

    public function action_dashboard() {
        
    }

    public function action_retpwd() {
        $adminuser = ORM::factory("AdminUser");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "save") {
                $data = json_decode($this->request->post("data"));
                $oldpwd = Auth_Backend::instance()->hash($data[0]->oldpassword);
                $pwd = Auth_Backend::instance()->hash($data[0]->password);
                $u = $adminuser->where("user_id", "=", $this->manager)->find();
                if ($u->password == $oldpwd) {
                    $u->password = $pwd;
                    $u->save();
                    $res = "密码修改成功";
                } else {
                    $res = "旧密码错误";
                }
            }
            echo json_encode($res);
            die();
        } else {
            $this->template->content = new View("backend/home/retpwd");
        }
    }

    public function action_my() {
        $adminuser = ORM::factory("AdminUser");
        $method = $this->request->query("method");
        if ($method) {
            $res = null;
            if ($method == "save") {
                $data = json_decode($this->request->post("data"));
                foreach ($data as $p) {
                    $adminuser->where("user_id", "=", $this->manager)->find()
                            ->values((array) $p)
                            ->save();
                }
            } elseif ($method == "get") {
                $res = $adminuser->where("user_id", "=", $this->manager)->find()->as_array();
            }
            echo json_encode($res);
            die();
        } else {

            $this->template->content = new View("backend/home/my");
        }
    }

}
