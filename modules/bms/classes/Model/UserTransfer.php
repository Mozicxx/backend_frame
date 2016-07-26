<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserTransfer
 *
 * @author jiwei
 */
class Model_UserTransfer extends ORM{
    protected $_table_name="user_transfer";
    protected $_belongs_to = array('user' => array('model' => 'Developer', 'foreign_key' => 'user_id'));
}
