<?php
/**
 * Description of Advertisement
 *
 * @author jiwei
 */
class Model_Advertisement extends ORM{
    protected $_table_name="advertisement";
    protected $_has_one = array('adrule' => array('model' => 'AdvertisementRule', 'foreign_key' => 'ad_id'));
}
