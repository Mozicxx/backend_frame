<?php

/**
 * Description of Backend
 *
 * @author jiwei
 */
class Controller_Backend extends Controller_Template {

    public $template = "backend/template";
    public $manager = 0;
    public $manager_name = "";
    public $roles = array();
    protected $permission = array();

    public function before() {
        $this->auth();
        parent::before();
    }

    public function after() {
        parent::after();
    }

    protected function is_admin() {
        return in_array(1, $this->roles) || in_array(7, $this->roles);
    }

    protected function _cashUnit($cash){
        $output = "";
        $cashUnit = array('元','万','亿');
        if($cash<100){
            $output = $cash.'财宝';
        }else{
            $jindu = 100;
            $i = 0;
            do{
                $cash /= $jindu;
                $output = (string)$cash.$cashUnit[$i];
                $i++;
                $jindu  = pow(100,2);
            }while($i<3&&$cash >= 10000);
        }
        return $output;
    }
    protected function _coinUnit($coin){
//        100铜币=1银币，100银币=1金币，1000金币=1水晶币，1w水晶币=1钻石币，10w钻石币=1紫金币
        $output = "";
        $coinUnit = array('银币','金币','水晶币','钻石币','紫金币');
        if($coin<100){
            $output = $coin.'铜币';
        }else{
            $jindu = 100;
            $i = 0;
            do{
                $coin /= $jindu;
                $output = (string)$coin.$coinUnit[$i];
                $jindu  = 100 * pow(10, $i++);
            }while($i<5&&$coin >= $jindu);
        }
        return $output;
    }
        public function coinUnit($coin = 0){
//        100铜币=1银币，100银币=1金币，1000金币=1水晶币，1w水晶币=1钻石币，10w钻石币=1紫金币
        $output = "";
        $coinUnit = array('万','亿');
        $jindu = 10000;
        if($coin<$jindu){
            $output .= $coin;
        }else{
            $i = 0;
            do{
                $left = $coin % $jindu;
                $coin = floor($coin/$jindu);
                $output = $coinUnit[$i].$left.$output;
                $i++;
            }while($coin >$jindu);
            $output = $coin.$output;
        }
        return $output;
    }

    private function auth() {
        $u = Auth_Backend::instance()->get_user();
        if ($u) {
            $this->manager = $u["user_id"];
            $this->manager_name = $u["username"];
            $this->roles = Auth_Backend::instance()->get_role();
            $this->permission = Auth_Backend::instance()->get_permission();
            $uri = strtolower($this->request->directory() . "/" . $this->request->controller() . "/" . $this->request->action());
            if (!in_array($uri, $this->permission)) {
                echo $uri;
                die("access die");
            }
        } else {
            $this->redirect(URL::site("backend/auth/login", TRUE) . "?ref=" . $this->request->url(TRUE));
        }
    }

}
