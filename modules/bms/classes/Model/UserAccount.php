<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserAccount
 *
 * @author jiwei
 */
class Model_UserAccount extends ORM{
    protected $_table_name="user_account";
    protected $_belongs_to = array('user' => array('model' => 'Developer', 'foreign_key' => 'user_id'));
}
