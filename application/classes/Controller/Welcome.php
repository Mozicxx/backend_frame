<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

    public function action_index() {
        //Model_UserBills::get_balance(12);die;

        $tea = new CryptXXTEA();
        $tea->setKey("285B73BF6D78CDC1");
        $resp = str_pad(123099884, 10, 0, STR_PAD_LEFT) . rand(100, 999);
        echo $resp."<br />";
        $encode = base64_encode($tea->encrypt($resp));
        echo $encode . "<br/>";
        $e = $tea->decrypt(base64_decode($encode));
        echo substr($e, 1,-3)."<br />";
        echo intval(substr($e, 0, -3));
        die;
        //echo Request::factory("http://money.ztsdk.com/welcome/ab", array("options" => array(CURLOPT_TIMEOUT => 1, CURLOPT_CONNECTTIMEOUT => 1)))->execute()->body();
    }

    public function action_ab() {
        $tea = new CryptXXTEA();
        $tea->setKey("BAL890JHUWNSO90O");
        $msg = "hello";
        $encode = base64_encode($tea->encrypt($msg));
        echo $encode."<br />";
        $decode = $tea->decrypt(base64_decode($encode));
        echo $decode;
    }

}

// End Welcome
