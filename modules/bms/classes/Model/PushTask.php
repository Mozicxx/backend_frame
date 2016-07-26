<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PushTask
 *
 * @author jiwei
 */
class Model_PushTask extends ORM {

    protected $_table_name = "push_task";
    protected $_belongs_to = array('ad' => array('model' => 'Advertisement', 'foreign_key' => 'ad_id'));

}
