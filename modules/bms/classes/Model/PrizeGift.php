<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrizeGift
 *
 * @author jiwei
 */
class Model_PrizeGift extends ORM{
    protected $_table_name = "draw_gift";
    protected $_db_group = "prize";
    protected $_primary_key = "gift_id";
}
