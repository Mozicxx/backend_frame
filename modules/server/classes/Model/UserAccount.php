<?php

/**
 * Description of UserBills
 *
 * @author jiwei
 */
class Model_UserAccount extends ORM {
    protected $_table_name = "account_log";
    protected $_primary_key = "id";
    protected $_db_group = "mkmoney";
    public static function get_balance($user_id,$event = 'all') {
//        $balance = DB::select(DB::expr("SUM(points)as balance,SUM(CASE WHEN points>0 THEN points END)as income,SUM(CASE WHEN points>0 AND add_time> DATE(NOW()) THEN points END)as today_income"))
//                        ->from("user_bills")->where("user_id", "=", $user_id)
//                        ->execute("core")->current();
        $record = DB::select(DB::expr("SUM(coin) as coin"))
                        ->from('account_log')->where('user_id','=',$user_id)->where('status','=','1');
        if($event != 'all') {$record->where('event', '=', $event);}
        $record = $record->execute('mkmoney')->current();
        $balance = $record['coin'] === null ? '0' : $record['coin'];
        return $balance;
    }
}
