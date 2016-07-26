<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author jiwei
 */
class Controller_Bms_Auth extends Controller_Template {

    public $template = "bms/template";

    public function action_login() {
        if ($_POST) {
            $username = $this->request->post("username");
            $password = $this->request->post("password");
            $ref = $this->request->query("ref");
            if (!$ref) {
                $ref = URL::site("bms", TRUE);
            }
            if (Auth_Bms::instance()->login($username, $password)) {
                $ret = array("ret" => 0, "msg" => "登录成功", "url" => $ref);
            } else {
                $ret = array("ret" => 1, "msg" => "登陆失败，用户名或密码错误!");
            }
            if ($this->request->is_ajax()) {
                echo json_encode($ret);
                die;
            } else {
                $this->redirect($ref);
            }
        }
        $this->template = new View("bms/login");
    }

    public function action_logout() {
        Auth_Bms::instance()->logout();
        $this->redirect(URL::site("bms/auth/login", TRUE));
    }

}
