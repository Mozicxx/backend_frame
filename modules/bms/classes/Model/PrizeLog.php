<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrizeLog
 *
 * @author jiwei
 */
class Model_PrizeLog extends ORM{
    protected $_table_name = "prize_log";
    protected $_db_group = "prize";
    protected $_primary_key = "prize_id";
}
