<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Article
 *
 * @author jiwei
 */
class Model_Article extends ORM {

    protected $_table_name = "www_article";
    protected $_belongs_to = array('catalog' => array('model' => 'Catalog', 'foreign_key' => 'catalog_id'));

}
