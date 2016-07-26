<?php

class Model_Users extends ORM {
    protected $_table_name = "user";
    protected $_primary_key = "id";
    protected $_db_group = "mkmoney";
    
    public function setLoginInfo($user)
    {
        $id = $user['user_id'];
        $isTodayLogin = $this->getLastLogin($id);
        if($isTodayLogin){
            DB::update('user_access_log')->set($user)->where('id','=',$isTodayLogin)->execute('mkmoney');
        }else{
            DB::insert('user_access_log')->columns(array_keys($user))->values(array_values($user))->execute('mkmoney');
        }
    }
    private function getLastLogin($userId)
    {
        $info = false;
//        $sql = "select * from user_access_log where user_id=$user_id order by add_time desc limit 1";
        $query = DB::select('id')->from( 'user_access_log')->where('user_id','=',$userId)
                ->where('add_time','BETWEEN',array(
                        DB::expr('CONCAT('.DB::expr('CURDATE()').'," 00:00:00")'),
                        DB::expr('CONCAT('.DB::expr('DATE_ADD(CURDATE(),interval 1 day)').'," 00:00:00")')
                    ))
                ->order_by('add_time','desc')->limit(1)->execute('mkmoney');
        if($query->count()) {$info = $query->as_array ();$info =$info[0]['id'];}
        return $info;
    }
    
    public function register($user,$old = false)
    {
        if(isset($user['inside'])){unset($user['inside']);}
        $resp['id'] = 0;
        $resp['ret'] =  0;
        $resp['msg'] = 'success';
            $length = mb_strlen($user['user_name']);
            $res = DB::select('id')->from('user')->where('user_name','=',$user['user_name'])->execute('mkmoney')->current();
        if($res !== NULL&&$length>1){
            $resp['ret'] = 409;
            $resp['msg'] = '用户名已存在';
        }else{
            try{
                if($old){
                    $result = DB::update('user')->set($user)->where('openuuid','=',$user['openuuid'])->execute('mkmoney');
                    $resp['id'] = $old;
                }else{
                    $user['userid'] = $this->get_unique_id();
                    $result = DB::insert('user',  array_keys($user))->values(array_values($user))->execute('mkmoney');
                    $resp['id'] = $result[0];
                }
            }  catch (Database_Exception $e){
                $resp['ret'] = 409;
                $resp['msg'] = '数据库错误'.$e->getMessage();
            }
        }
        return $resp;
    }


    public function get_question($user_id){
        $result = DB::select('id','question','answer')->from ('user_security')->where('user_id','=',$user_id)->execute('mkmoney');
        $data = $result->as_array();
        return $data;
    }
    
    private function get_unique_id(){
        $num = '587'. mt_rand('1111111','9999999');
            $exists = DB::select('id')->from("user") ->where('userid','=',$num)->execute('mkmoney')->current();
            if($exists !== NULL) {
                $this->get_unique_id ();
            } else{
                return $num;
            }
        }
}

