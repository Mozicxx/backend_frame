<?php

defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */

class Controller_Bms_Setting extends Controller_Bms {

    public function action_index() {
        $this->template->content = new View("bms/ad/index");
    }
    

}
