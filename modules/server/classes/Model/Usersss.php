<?php

class Model_Userssss extends ORM
{
    protected $_table_name = "usesssr";
    protected $_primary_key = "id";
    protected $_db_group = "mkmoney";
    
    public static function setLoginInfo($user)
    {
        $id = $user['id'];
        unset($user['id']);
        $isTodayLogin = Model_User::getLastLogin($user['id']);
        if($isTodayLogin){
            DB::update('user_access_log')->set($user)->where('id','=',$isTodayLogin->id)->execute('mkmoney');
        }else{
            DB::insert('user_access_log')->values($user)->execute('mkmoney');
        }
    }
    private function getLastLogin($userId)
    {
        $info = false;
//        $sql = "select * from user_access_log where user_id=$user_id order by add_time desc limit 1";
        $query = DB::select('id')->from( 'user_access_log')->where('user_id','=',$userId)
                ->where('add_time','BETWEEN',array(DB::expr('CURDATE()')." 00:00:00",DB::expr('DATE_ADD(CURDATE(),interval 1 day)')." 00:00:00"))
                ->order_by('add_time','desc')->limit(1)->execute('mkmoney');
        if($query->count()) $info = $query->as_object ();
        
        return $info;
    }
    public function register($user)
    {
        $resp['ret'] =  0;
        $resp['msg'] = 'success';
        $user['password'] = Auth::instance()->hash($user["password"]);
        $result = DB::insert('user')->values($user)->execute('mkmoney');
        $resp['id'] = $result[1];
        return $resp;
    }
}

