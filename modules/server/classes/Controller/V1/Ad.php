<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_V1_Ad extends Controller_V1 {

    /**
     * 获取广告列表
     */
    public function action_list() {
        $js = file_get_contents("ads.json");
        echo $js;
    }

    /**
     * 获取广告详情
     */
    public function action_get() {
        $js = file_get_contents("ad.json");
        echo $js;
    }

    /**
     * 获取banner广告
     */
    public function action_banner() {
        $js = file_get_contents("banner.json");
        echo $js;
    }

}
