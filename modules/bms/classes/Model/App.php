<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of App
 *
 * @author jiwei
 */
class Model_App extends ORM{
    protected $_table_name="app";
    protected $_belongs_to = array('developer' => array('model' => 'Developer', 'foreign_key' => 'developer_id'));
}
