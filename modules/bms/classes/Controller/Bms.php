<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Bms extends Controller_Template {

    public $template = "bms/template";
    public $manager = 0;
    public $manager_name = "";
    protected $permission = array();

    public function before() {
        $this->auth();
        parent::before();
    }

    public function after() {
        parent::after();
    }

    private function auth() {
        $u = Auth_Bms::instance()->get_user();
        if ($u) {
            $this->manager = $u["user_id"];
            $this->manager_name = $u["username"];
            $this->permission = Auth_Bms::instance()->get_permission();
            $uri = strtolower($this->request->directory() . "/" . $this->request->controller() . "/" . $this->request->action());
            if (!in_array($uri, $this->permission)) {
                echo $uri;
                die("access die");
            }
        } else {
            $this->redirect(URL::site("bms/auth/login", TRUE)."?ref=".$this->request->url(TRUE));
        }
    }

}
