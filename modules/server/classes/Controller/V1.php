<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Controller_V1 extends Kohana_Controller {

    public $query;
    private $is_squery = false;

    public function before() {
        $sreq = $this->request->query("_s");
        if ($sreq) {
            $tea = new CryptXXTEA();
            $tea->setKey("30DACFDEDAF4A31C");
            $creq = $tea->decrypt(base64_decode($sreq));
            $oreq = array();
            parse_str($creq, $oreq);
            $this->query = $oreq;
            unset($sreq);
            $this->is_squery = true;
        } else {
            $this->query = $this->request->body();
        }
        if($this->query){
            $this->query = urldecode($this->query);
            $this->query = json_decode($this->query,true);
        }
    }

    public function after() {
        parent::after();
        if ($this->is_squery) {
            $resp = ob_get_clean();
            $tea = new CryptXXTEA();
            $tea->setKey("285B73BF6D78CDC1");
            echo base64_encode($tea->encrypt($resp));
        }
        
    }

    /**
     * 构造默认返回
     * @return \Resp
     */
    public function buildResp() {
        return new Resp();
    }
    
    public function extraInfo(&$task){
        switch ($task['type']){
                case 'app':
                    $extra = DB::select('app.package_name','app.download','app.icon','app.screenshot')->from('app')->join('task')->on('task.extra_id', '=', 'app.id')->where('task.id', '=', $task['id'])->execute('mkmoney')->current();
                    $task['package_name'] = $extra['package_name'];
                    $task['download'] = $extra['download'];
                    $task['icon'] = $extra['icon'];
                    $task['screenshot'] = $extra['screenshot'];
                    break;
            }
        $this->null2String($task);
    }


    public function extraInfoList(&$tasks){
         foreach ($tasks as &$t){
             //每日推荐任务的id改为任务id
             if(isset($t['task_id'])){
                 $t['id'] = $t['task_id'];
                 unset($t['task_id']);
             }
            switch ($t['type']){
                case 'app':
                    $extra = DB::select('app.package_name','app.download','app.icon','app.screenshot')->from('app')->join('task')->on('task.extra_id', '=', 'app.id')->where('task.id', '=', $t['id'])->execute('mkmoney')->current();
                    $t['package_name'] = $extra['package_name'];
                    $t['download'] = $extra['download'];
                    $t['icon'] = $extra['download'];
                    $t['screenshot'] = $extra['screenshot'];
                    break;
            }
        }
        $this->null2String($tasks);
    }

    public function null2String(&$data) {
        array_walk_recursive($data,function(&$v){
            if($v==null) $v="";
        });
    }
    /**获取用户的ID
     * 
     */
    function get_id($userid){
        $user = DB::select('id')->from('user')->where('userid','=',$userid)->execute('mkmoney')->get('id');
        return $user;
    }
    /**
     * 虚拟币单位换算
     * @param type $coin
     * @return string
     */
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
    /**
     * 提升等级
     * @param type $exp
     * @return string
     */
    public function upgrade($exp){
//        switch ($exp){
//            case '100': $level = 'G'; break;
//            case '300': $level = 'G+'; break;
//            case '600': $level = 'F-'; break;
//            case '1100': $level = 'F'; break;
//            case '1900': $level = 'F+'; break;
//        }
        if($exp <= 100){
            $level = 'G-';
        }elseif($exp>=100&&$exp<=300){
            $level = 'G';
        }elseif($exp>=300&&$exp<=600){
            $level = 'G+';
        }elseif($exp>=600&&$exp<=1100){
            $level = 'F-';
        }elseif($exp>=1100&&$exp<=1900){
            $level = 'F';
        }elseif($exp>=1900&&$exp<=3000){
            $level = 'F+';
        }
        return $level;
    }
}

/**
 * 返回数据
 */
class Resp {

    public $ret = 0; //	必须	integer	返回状态，0成功，非0失败代码
    public $msg = "success"; //	必须	string	返回结果说明

    public function __toString() {
        return json_encode($this);
    }

    public function data($data) {
        $this->data = $data;
    }

}
