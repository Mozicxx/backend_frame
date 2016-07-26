<?php

/**
 * Description of UserBills
 *
 * @author jiwei
 */
class Model_UserBills extends ORM {

    public static function get_balance($user_id) {
        $balance = DB::select(DB::expr("SUM(points)as balance,SUM(CASE WHEN points>0 THEN points END)as income,SUM(CASE WHEN points>0 AND add_time> DATE(NOW()) THEN points END)as today_income"))
                        ->from("user_bills")->where("user_id", "=", $user_id)
                        ->execute("core")->current();
        foreach ($balance as $k => $v) {
            $balance[$k] = $v + 0;
        }
        return $balance;
    }

}
