<?php
defined('SYSPATH') or die('No direct script access.');
//header("Content-type: text/html; charset=utf-8");

class Controller_V1_User extends Controller_V1 {
    /**
     * 用户注册
     */

    public function action_test(){
        function monkey($m,$n) {
            if(!is_integer($m) || $m<=0) return 'm must be integer and great then zero';
            $monkeys = array();
            for($i=1;$i<=$m;$i++) {
                $monkeys[$i] = $i;
            }
            //初始化
            $num = 1;
            $now = 1;
            reset($monkeys);
//        do {
            for($i=0;$i<11;$i++) {
                if ($num == $n) {
                    $num = 1;
                } else {
                    $num++;
                }
                if (max($monkeys) == current($monkeys)) {
                    reset($monkeys);
                } else {
                    next($monkeys);
                }
                $now = current($monkeys);
                if ($num == $n) {
                    $hasPrev = prev($monkeys);
                    if(!$hasPrev) end($monkeys);
                    unset($monkeys[$now]);
                }
                var_dump($monkeys);
                var_dump(count($monkeys));
                var_dump($num);
            }

//        } while(count($monkeys) > 1);
            return $monkeys;
        }

        echo '<pre>';
        var_export( monkey(7,2));
    }

    public function action_rename(){
        $count = DB::select()->from('links')->execute('indoorsman')->count();
        $iconArr = array(
            'http://oss.aliyuncs.com/zt-mkmoney/1607/151631_512.png',
            'http://oss.aliyuncs.com/zt-mkmoney/1607/151637_icon%281%29.png',
            'http://oss.aliyuncs.com/zt-mkmoney/1607/151636_icon.png'
        );
        $iconCount = count($iconArr);
        $siteArr = array(
            'http://www.baidu.com',
            'http://www.csdn.net',
            'http://www.demopu.com',
            'http://www.bing.com',
            'http://nginx.org'
        );
        $siteCount = count($siteArr);
        for($i=0;$i<$count;$i++){
            $charCHN = "神器".(string)($i+1);
//            echo 'icon=>',$iconArr[mt_rand(0,$iconCount-1)],'name=>',$charCHN,'url=>',$siteArr[mt_rand(0,$siteCount-1)],"<br />";
            DB::update('links')
                ->set(array('icon'=>$iconArr[mt_rand(0,$iconCount-1)],
                    'name'=>$charCHN,
                    'url'=>$siteArr[mt_rand(0,$siteCount-1)]
                ))->where('id','=',$i+1)->execute('indoorsman');
        }
    }

    static function unicodeDecode($data)
    {

        $rs = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', function($match){return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');}, $data);

        return $rs;
    }

    public function action_fuck(){
        $str = "";
////        for($i=1423,$counter=0;$i<1500;$i++){
//            $url = "http://www.wanbu.com.cn/NewWanbu/App/NewVote/index.php/Index/Index/wechatvote/vid/215/cid/1414";
////            $url = 'http://www.jb51.net';
//            $curl = curl_init();
//            curl_setopt($curl, CURLOPT_URL, $url);
//            curl_setopt($curl, CURLOPT_HEADER, 1);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($curl, CURLOPT_COOKIEJAR, $str);
//            $data = curl_exec($curl);
//            if($data) {
//                var_dump($data);
//                $info = json_decode($data);
//                if($info->status == 1) {
//                    $counter++;
//                    $str .= "====$counter====<br/>";
//                }
//            }
//            curl_close($curl);
////        }
        $ip_long = array(
            array('607649792', '608174079'), //36.56.0.0-36.63.255.255
            array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
            array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
            array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
            array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
            array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
            array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
            array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
            array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
            array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
        );
        $rand_key = mt_rand(0, 9);
        $ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));//随机生成国内某个ip
        $url='http://www.wanbu.com.cn/NewWanbu/App/NewVote/index.php/Index/Index/wechatvote/vid/215/cid/1414';//这里请填写投票的请求地址
        $ch = curl_init();
        $header = array(
            "CLIENT-IP:{$ip}",
            "X-FORWARDED-FOR:{$ip}",
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.58.com/");   //来路
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (iPhone; CPU iPhone OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Mobile/12A365 MicroMessenger/6.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        $str = curl_exec($ch);
        curl_close($ch);

        echo $str;
    }
    public function action_register() {
        $User = ORM::factory('Users');
        $resp = $this->buildResp();
        CodeLog::add('register',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("register", json_encode($this->query,JSON_UNESCAPED_UNICODE));
//        $this->query = array('openuuid'=>'dd291cd2ea916c48','user_name'=>"mc2",'password'=>'mozic312');//test data
        $query = Arr::extract($this->query, array('openuuid','user_name','password','master_id','inside'),'');
        $exist = DB::select()->from("user")->where("openuuid", "=", $query["openuuid"])->order_by('login_time','desc')->limit(1)->execute("mkmoney")->current();
        if(!$exist){
            $result = $User->register($query);
        }else{
            $result = $User->register($query,$exist['id']);
        }
        list($resp->ret,$resp->msg,$id) = array($result['ret'],$result['msg'],$result['id']);
        if($resp->ret != 0) exit($resp);
        if($query['inside'] === ''){
            exit($resp);
        }
        $account = array('user_id'=>$id,'event'=>'register');
        //建立账户
        $acnt = DB::insert('account_log', array_keys($account))->values(array_values($account))->execute('mkmoney');
        //登录步骤
        if($acnt[0] > 0){
            $this->query = array('openuuid'=>$query['openuuid'],'user_name'=>$query['user_name'],'password'=>$query['password']);
            $this->action_login();
        }
        
    }

    /**
     * 用户登录
     */
    public function action_login() {
//        $ry = '{"os_ver":"23","imei":"358239058303456","brand":"google","app_ver":"1.0","carrier":"CMCC","odin":"4838658be75bded5a653f59b956ea7a00364f4da","ac":"01","net":"wifi","channel":null,"imsi":"460002570556093","os":"Android","openuuid":"asdfcc","user_name":"aaaaaa","password":"0B4E7A0E5FE84AD35FB5F95B9CEEAC79","term":"Nexus 5"}
//';
//        $this->query = json_decode($ry,true);
//        $y = array('openuuid'=>'aqweqweczcccdc','user_name'=>'','password'=>'');
//        $this->query = array_merge($y,$ry);
        CodeLog::add('login',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("login", json_encode($this->query,JSON_UNESCAPED_UNICODE));
        $resp = $this->buildResp();
        $User = ORM::factory('Users');
        $query = Arr::extract($this->query, array("openuuid", "user_name", "password","master_id"),'');
        $access = Arr::extract($this->query, array("os_ver","imei","imsi","brand","app_ver","term","carrier","os","term"));
//        $query = array('user_name'=>"mozisc",'password'=>'mozic');//test data
        $user = null;
        $token = null;
        $info = null;
        if ($query["user_name"]) {
            $exist = DB::select()->from("user")->where("user_name", "=", $query["user_name"])->execute("mkmoney")->current();
            if ($exist["id"] && $exist["password"] == $query["password"]) {
                DB::update("user")->set(array('openuuid'=>$query['openuuid']))->where("id", "=", $exist['id'])->execute("mkmoney");
                $access['user_id'] = $exist['id'];
                $city = IpUtil::convertip_all();
                $login = array(
                    'user_id' => $user['id'],
                    "province" => $city[0],
                    "city" => $city[1],
                    'add_time'=>  DB::expr('NOW()')
                );
                $access = array_merge($login,$access);
                $User->setLoginInfo($access);
                list($token,$info) = $this->get_info($exist,$query);
            } else {
                $resp->ret = 403;   
                $resp->msg = "密码错误或用户名不存在!";
            }
        }elseif($query['openuuid']){
            $exist = DB::select()->from("user")->where("openuuid", "=", $query["openuuid"])->order_by('login_time','desc')->limit(1)->execute("mkmoney")->current();
            if(!$exist){
                if($query['master_id']!=NULL){
                    $this->query['master_id'] = $query['master_id'];
                }
                $this->query = array('openuuid'=>$query['openuuid'],'user_name'=>$query['user_name'],'password'=>$query['password'],'inside' =>true);
                $this->action_register();
                exit;
            }else{
                $access['user_id'] = $exist['id'];
                $city = IpUtil::convertip_all();
                $login = array(
                    'user_id' => $user['id'],
                    "province" => $city[0],
                    "city" => $city[1],
                    'add_time'=>  DB::expr('NOW()')
                );
                $access = array_merge($login,$access);
                $User->setLoginInfo($access);
                
                list($token,$info) = $this->get_info($exist,$query);  
            }
        }else{
            $resp->ret = 405;
            $resp->msg = 'data error!';
        }
        if($info !== NULL && $token !== NULL){
        $resp->token = $token;
        $resp->info = $info;
        }
        echo $resp;
    }
    /**
     * 获取用户信息
     */
    function action_get_info(){
        CodeLog::add('info',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("info", json_encode($this->query,JSON_UNESCAPED_UNICODE));
        $resp = $this->buildResp();
//        $this->query = array('userid'=>"",'openuuid'=>'dd291cd2ea916c48');//test data
        $query = Arr::extract($this->query, array('openuuid','userid'));
//        $query = array('userid'=>"5876817446",'openuuid'=>'dd');//test data
        if($query['openuuid']===NULL && $query['userid']===NULL){
            $resp->ret = 403;
            $resp->msg = 'authrized faild!';
        }else{
            if($query['userid'] !== NULL){
                $user = DB::select()->from('user')->where('userid','=',$query['userid'])->execute('mkmoney')->current();
             }else{
                 $user = DB::select()->from('user')->where('openuuid','=',$query['openuuid'])->execute('mkmoney')->current();
             }
            if($user){
                list($token,$info) = $this->get_info($user,$query);
                $resp->token = $token;
                $resp->info = $info;
            }else{
                $resp->ret = 403;
                $resp->msg = 'authrized faild!';
            }
         }
        echo $resp;
    }
    
    /**
     * 设置用户头像
     */
    public function action_portrait(){
        CodeLog::add('login',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("login", json_encode($_FILES,JSON_UNESCAPED_UNICODE));
        CodeLog::add("login", json_encode($this->request->post()));
        $path = "data/portrait/".date("Y/m/",time());
        $upload = new Upload($path);
        $resp = $this->buildResp();
        $query = Arr::extract($this->request->post(), array('userid','openuuid'));
        if($query['userid'] === NULL) {
            $resp->ret = 441;
            $resp->msg = '数据错误';
            exit($resp);
        }
//        $query = array('userid'=>'58741991057');
        $file = $_FILES;
        $userId = $this->get_id($query['userid']);
        if($file['file']['error'] == 0){
            $msg = $upload->upload($file);
            if($msg){
                $cut = $this->fit_portrait($msg[1]);
                if(!$cut) $msg = 'photo cut failure!';
            }
        }else{
            $msg = 'err';
        }
        if($msg[0]){
            ORM::factory('User',$userId)->values(array('portrait'=>$msg[1]))->save();
            $resp->portrait = $msg[1];
        }else{
            $resp->ret = 440;
            $resp->msg = '头像上传失败';
        }
        echo $resp;
    }

    /**
     *更新用户信息
     */
    public function action_update() {
        $resp = $this->buildResp();
//        $this->query = '{"app_ver":"1.0","carrier":"CMCC","net":"wifi","userid":"5875389355","os":"Android","openuuid":"dd291cd2ea916c48","os_ver":"23","imei":"358239058303456","brand":"google","odin":"4838658be75bded5a653f59b956ea7a00364f4da","ac":"01","channel":null,"imsi":"460002570556093","nick_name":"www","term":"Nexus 5"}';
//        $this->query = json_decode($this->query,true);
        CodeLog::add('update',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("update", json_encode($this->query,JSON_UNESCAPED_UNICODE));
//        $this->query = array('userid'=>'5875389355','nick_name'=>'dicky');//test data
        if(!$this->query){ exit('error');}
        $req = Arr::extract($this->query, array("userid", "openuuid"),'');
        $field =array('nick_name','user_name','real_name','email','mobile','alipay_account','password','trade_password');
        foreach ($this->query as $k=>$v){
            if(in_array($k, $field)){
                $field = array($k=>$v);break;
            }
        }
        Kohana::$log->add(Kohana_Log::DEBUG, json_encode($this->request->query(),JSON_UNESCAPED_UNICODE));
        $userId = $req['userid'];
        if ($userId) {
//            $update->value("last_update", date("Y-m-d H:i:s"));
            $res = DB::update("user")->set($field)->where("userid", "=", $userId)->execute("mkmoney");
        }else{
                $resp->msg = '用户不存在！';
                $resp->ret = 401;
        }
        echo $resp;
    }
    public function action_update_password(){
            $resp = $this->buildResp();
//        $this->query = '{"app_ver":"1.0","carrier":"CMCC","net":"wifi","userid":"5875389355","os":"Android","openuuid":"dd291cd2ea916c48","os_ver":"23","imei":"358239058303456","brand":"google","odin":"4838658be75bded5a653f59b956ea7a00364f4da","ac":"01","channel":null,"imsi":"460002570556093","nick_name":"www","term":"Nexus 5"}';
//        $this->query = json_decode($this->query,true);
        CodeLog::add('update',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("update", json_encode($this->query,JSON_UNESCAPED_UNICODE));
        if(!$this->query){ exit('error');}
        $req = Arr::extract($this->query, array("userid", "openuuid","password","npwd"),'');
        $userId = $req['userid'];
        $result = DB::select('id')->from('user')->where('password','=',$req['password'])->execute('mkmoney')->current();
        if ($result) {
            $res = DB::update("user")->set(array('password'=>$req['npwd']))->where("userid", "=", $userId)->execute("mkmoney");
            if($res !== 1){
                $resp->msg = '数据库错误！';
                $resp->ret = 402;
            }
        }else{
                $resp->msg = '原始密码输入错误！';
                $resp->ret = 401;
        }
        echo $resp;
    }

        /**
     * 设置安全问题
     */
    public function action_set_security_question(){
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array('userid','openuuid','q1','a1','q2','a2','q3','a3'),'');
//        $req = array('userid'=>'5878336258','q1'=>'','a1'=>'aaa','q2'=>'','a2'=>'asa','q3'=>'','a3'=>'');
        if($req['q1']===''&&$req['q2']===''&&$req['q3']===''){
                $resp->msg = '数据异常';
                $resp->ret = 409;
                exit($resp);
        }
        $userId = $this->get_id($req['userid']);
        $question = DB::select()->from('user_security')->where('user_id','=',$userId)->execute('mkmoney');
        if($question[0] !== NULL){
            $db = Database::instance();
            $db->begin();
            try {
                    if($req['q1']) DB::update('user_security')->set(array('user_id'=>$userId,'question'=>$req['q1'],'answer'=>$req['a1']))->where('id','=',$question[0]['id'])->execute('mkmoney');
                    if($req['q2']) DB::update('user_security')->set(array('user_id'=>$userId,'question'=>$req['q2'],'answer'=>$req['a2']))->where('id','=',$question[1]['id'])->execute('mkmoney');
                    if($req['q3']) DB::update('user_security')->set(array('user_id'=>$userId,'question'=>$req['q3'],'answer'=>$req['a3']))->where('id','=',$question[2]['id'])->execute('mkmoney');
                 $db->commit();
            }
            catch (Database_Exception $e){
                $resp->msg = '数据库异常';
                $resp->ret = 402;
                 $db->rollback();
             }
        }else{
            $db = Database::instance();
            $db->begin();
            try {
                    DB::insert('user_security',array('user_id','question','answer'))->values(array($userId , $req['q1'] , $req['a1'] ) )->execute('mkmoney');
                    DB::insert('user_security',array('user_id','question','answer'))->values(array($userId , $req['q2'] , $req['a2'] ) )->execute('mkmoney');
                    DB::insert('user_security',array('user_id','question','answer'))->values(array($userId , $req['q3'] , $req['a3'] ) )->execute('mkmoney');
                 $db->commit();
            }
            catch (Database_Exception $e){
                $resp->msg = '数据库异常';
                $resp->ret = 402;
                 $db->rollback();
             }
        }
        echo $resp;
    }
    
/**
 * 检查密保问题
 */
    public function action_check_security_question(){
        $check = false;
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("userid", "openuuid","q","a"));
        $userId = $this->get_id($req['userid']);
        $qaset = DB::select('question','answer')->from('user_security')->where('user_id','=',$userId)->execute('mkmoney');
        $qaset = $qaset->as_array();
        foreach ($qaset as $qa){
            if($qa['question'] == $req['q'] && $qa['answer'] == $req['a']){$check = true;}
        }
        if(!$check){
            $resp->ret = 403;
            $resp->msg = '回答有误！';
        }
        echo $resp;
    }
/**
 * 检查交易密码
 */
    public function action_check_trade_password(){
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("userid", "openuuid","trade_password"));
        $check = DB::select('id')->from('user')->where('userid','=',$req['userid'])->where('trade_password','=',$req['trade_password'])->execute('mkmoney')->current();
        if($check == NULL){
            $resp->ret = 403;
            $resp->msg = '交易密码错误';
        }
        echo $resp;
    }
    /**
     * 查询用户余额
     */
    public function action_balance() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("userid", "openuuid"),'');
//        $req = array('userid'=>"1234",'openuuid'=>'');
        $user = $this->get_id($req['userid']);
        if($user){
            $balance = $this->coinUnit(Model_UserAccount::get_balance($user));
            $resp->balance = $this->coinUnit($balance);
        }else{
            $resp->ret = 403;
            $resp->msg = "authrized faild!";
        }
        echo $resp;
    }
    
    /**
     * 获取账单列表
     */
    public function action_bill() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("userid", "openuuid",'date'),'');
//        $req = array('userid'=>"5872450038",'openuuid'=>'','date'=>'2016-06');
        $user = $this->get_id($req['userid']);
        $date = $req['date'] === "" ? date('Y-m',  time()) : $req['date'];
        if($user){
            $account = DB::select()->from('account_log')->where('user_id', '=', $user)->where('event', '!=', 'register')
                    ->where('add_time','BETWEEN',array("$date-01 00:00:00","$date-31 23:59:59"))
                    ->order_by('add_time','desc')->execute('mkmoney')->as_array();
        foreach ($account as &$deal){
            $deal['add_time'] = date('Y-m-d',strtotime($deal['add_time']));
        }
            $resp->data($account);
        }else{
            $resp->ret = 403;
            $resp->msg = "authrized faild!";
        }
        echo $resp;
    }

    /**
     * 获取用户消息列表
     */
    public function action_get_msg_list() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array("userid", "openuuid","type"));
        $userId = $this->get_id($req['userid']);
        if ($userId) {
            //select a.*,b.* from user_message a left join rel_user_message b on a.id=b.message_id where (a.user_id=61 or a.user_id=-1) and b.status!=3;
            $query = DB::select('a.id','a.title','a.content','a.coin','a.type','b.status','a.send_time','a.add_time')
                    ->from(array('user_message','a'))
                    ->join(array('rel_user_message','b'),'LEFT')
                    ->on('a.id','=','b.message_id')
                    ->where( 'a.user_id', 'IN', array($userId,'-1') )
                    ->where('b.user_id', '=', $userId)
                    ->and_where_open()
                        ->where('b.status','!=','3')
                        ->or_where('b.status', 'IS', NULL)
                    ->and_where_close();
            if($req['type'] != '0'){
                $query->and_where('type','=',$req['type']);
            }
            $query = $query->order_by('status','asc')->order_by('a.add_time','desc')->execute('mkmoney');
            $resp->data = $query->as_array();
            $msgCount = $query->count();
            $receive = DB::select('rel_user_message.message_id')->from('rel_user_message')
                    ->join('user')->on('user.id', '=', 'rel_user_message.user_id')
                    ->join('user_message')->on('user_message.id', '=', 'rel_user_message.message_id');
            if($req['type'] != '0'){
                $receive->and_where('user_message.type','=',$req['type']);
            }
            $receiveList = $receive->where('user.userid','=',$req['userid'])->where('rel_user_message.status','!=','3')->execute('mkmoney');
            $receiveCount = $receiveList->count();
            $lock = $msgCount === $receiveCount;
            $msgIdSet = array();
            $receiveMsgIdSet = array();
            foreach ($resp->data as &$v){
                $msgIdSet[] = $v['id'];
                $v['add_time'] = date('Y-m-d H:i',strtotime($v['add_time']));
            }

            if(!$lock){
                foreach ($receiveList as $v){
                    $receiveMsgIdSet[] = $v['message_id'];
                }
                $newMessageSet = array_diff($msgIdSet,$receiveMsgIdSet);
                sort($newMessageSet);
                $values = array();
                $count = count($newMessageSet);
                $insert = DB::insert('rel_user_message',array('message_id','user_id'));
                for($i = 0;$i<$count;$i++){
//                    $values[] = array($newMessageSet[$i],$userId);
                    $insert->values(array($newMessageSet[$i],$userId));
                }
                 $insert->execute('mkmoney');
            }
            $this->null2String($resp->data);
        }else{
            $resp->ret = 403;
            $resp->msg = "authrized faild!";
        }
        echo $resp;
    }
    /**
     * 获取单条信息,并将其状态设为已读
     */
    function action_get_msg(){
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array( "openuuid","message_id","userid"));
//        $req = array('userid'=>'5872450038','message_id'=>'91');//test data  
        $userId = $this->get_id($req['userid']);
        if($userId){
            $resp->data = DB::select('title','content','type','coin')->from('user_message') ->where( 'id', '=',$req['message_id']  )->execute('mkmoney')->current();
            $curStatus = DB::select()->from('rel_user_message')->where('message_id','=',$req['message_id'])->execute('mkmoney')->get('status');
            if($curStatus == '0'){
            DB::update('rel_user_message')->set(array('status'=>'1'))->where('user_id','=',$userId)->where('message_id','=',$req['message_id'])->execute('mkmoney');}
            $resp->data['status'] = $curStatus;
            $resp->data['message_id'] = $req['message_id'];
            $this->null2String($resp->data);
        }else{
            $resp->ret = 403;
            $resp->msg = '用户不存在';
        }
        echo $resp;
    }
    /**
     * 用户操作消息
     */
    public function action_operate_msg() {
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array( "openuuid","message_id","operate","userid","content","coin"));
//        $req = array('userid'=>'5872450038','message_id'=>'91','operate'=>'delete');//test data
        $userId = $this->get_id($req['userid']);
        if(!$userId){
            $resp->ret = 403;
            $resp->msg = "authrized faild!";
            exit($resp);
        }
        try{
            switch ($req['operate']){
                case 'read':
//                    DB::insert('rel_user_message',array('message_id','user_id'))->values(array($req['message_id'],$userId))->execute('mkmoney');
                     DB::update('rel_user_message')->set(array('status'=>'1'))->where('user_id','=',$userId)->where('message_id','=',$req['message_id'])->execute('mkmoney');
                    break;
                case 'reply':
                    DB::insert('re_message',array('user_id','message_id','content'))->values(array($userId,$req['message_id'],$req['content']))->execute('mkmoney');
                case 'prize':
                    $db = Database::instance();
                    $db->begin();
                    try {
                        DB::update('rel_user_message')->set(array('status'=>'2'))->where('user_id','=',$userId)->where('message_id','=',$req['message_id'])->execute('mkmoney');
                        DB::insert('account_log',array('user_id','coin'))->values(array($userId,$req['coin']))->execute('mkmoney');//算钱
                        $db->commit();
                    }
                    catch (Database_Exception $e){
                        $resp->msg = '数据库异常';
                        $resp->ret = 402;
                         $db->rollback();
                     }
                    break;
                case 'delete':
                    $req['message_id'] = explode(',',$req['message_id']);
                    $unpeize = DB::select('rel_user_message.id')->from('rel_user_message')->join('user_message')->on('rel_user_message.message_id', '=', 'user_message.id')
                                        ->where('rel_user_message.user_id','=',$userId)->where('user_message.coin', '!=', '0')->where('rel_user_message.status', '<', '2')
                                        ->where('rel_user_message.message_id','IN',$req['message_id'])->execute('mkmoney')->current();
                    if($unpeize !== NULL){
                        $resp->ret = 888;
                        $resp->msg = '您有未领取的奖励,请领取后删除';
                        exit($resp);
                    }
                    DB::update('rel_user_message')->set(array('status'=>'3'))->where('user_id','=',$userId)->where('message_id','IN',$req['message_id'])->execute('mkmoney');
                    break;
            }
        }  catch (Database_Exception  $e){
            $resp->ret = 409;
            $resp->msg = '数据库异常'.$e->getMessage();
        }
        echo $resp;
    }
    /**
     * 用户签到
     */
    public function action_sign(){
        $resp = $this->buildResp();
        $trade = new Trade();
        $req = Arr::extract($this->query, array("userid", "openuuid"),'');
        
        $combo = 1;
        
//        $req = array('userid'=>'5873032718');//test data
        $userId = $this->get_id($req['userid']);
        if($userId){
            //看看用户是否签到过
            $signInfo = DB::select('id')->from('sign_in')->where('user_id','=',$userId)->execute('mkmoney')->current();
            if(is_array($signInfo)){
                //看看用户今天签到过没
                $todaySign = DB::select('id')->from('sign_in')->where('user_id','=',$userId)
                        ->where('last_sign_time','BETWEEN',array(
                            DB::expr('CONCAT('.DB::expr('CURDATE()').'," 00:00:00")'),
                            DB::expr('CONCAT('.DB::expr('CURDATE()').'," 23:59:59")')
                        ))->execute('mkmoney')->current();
                if(!is_array($todaySign)){
                    DB::update('sign_in')->set(array('combo'=>'combo+1'))->where('id','=',$sign)->execute('mkmoney');
                }else{
                    $resp->ret = 609;
                    $resp->msg = '该用户今日已签到';
                    exit($resp);
                }
            }else{
                DB::insert('sign_in',array('user_id'))->values(array($userId))->execute('mkmoney');
            }
            $trade->userId = $userId;
            $trade->money = '10';
            $trade->event = 'sign';
            $trade->income();
            $resp->msg = '签到成功，铜币+10';
        }else{
            $resp->msg = '用户不存在！';
            $resp->ret = 401;
        }
        echo $resp;
    }

    /**
     * 用户发送反馈
     */
    public function action_feedback(){
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array('userid','openuuid','content'));
        $userId = $this->get_id($req['userid']);
        if($userId){
            try{
                DB::insert('feedback',array('user_id','content'))->values(array($userId,$req['content']))->execute('mkmoney');
            }  catch (Database_Exception $e){
                $resp->ret = 409;
                $resp->msg = '数据库异常';
            }
        }else{
            $resp->msg = '用户不存在！';
            $resp->ret = 401;
        }
        echo $resp;
    }
    /**
     * 获取用户反馈列表
     */
    public function action_get_feedback(){
        $resp = $this->buildResp();
        $query = Arr::extract($this->query, array('userid','openuuid','page','page_rows'));
//        $query = array('token'=>'UHKt9Gxe6b5zq4KSgcHclwFt2ow=','page'=>'1','page_rows'=>'2');//test data
        $userId = $this->get_id($req['userid']);
        if($userId){
            $result = DB::select('id','content','reply','add_time')->from('feedback')->where('user_id', '=', $userId)
                    ->limit($query['page_rows'])->offset( ($query['page']-1) * $query['page_rows'])->execute('mkmoney');
            $resp->data = $result->as_array();
        }else{
            $resp->msg = '用户不存在！';
            $resp->ret = 401;
        }
        echo $resp;
    }
    /**
     * 以邮件形式发送验证码
     */
    public function action_send_email(){
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array('userid','openuuid','email'));
//        $req = array('userid'=>'5875389355','openuuid'=>'');
        $user = DB::select('id','email','nick_name')->from('user')->where('userid','=',$req['userid'])->execute('mkmoney')->current();
        if($user){
            $captcha = $this->get_captcha();
            $transport = Swift_SmtpTransport::newInstance('smtp.exmail.qq.com',465,'ssl')
                ->setUsername('service@gosing.com.cn')
                ->setPassword('Wz123465');

            $mailer = Swift_Mailer::newInstance($transport);

            $message = Swift_Message::newInstance()
                ->setSubject("来自惠赚360的邮箱验证邮件")
                ->setFrom(array('service@gosing.com.cn' => '惠赚360'))
                ->setTo($req['email'])
                ->setBody('亲爱的 <i>'.$req['email'].'</i><br />您的验证码为： <strong style="color:red;">'.$captcha.'</strong> 请输入验证码，完成验证。30分钟后验证码作废。', 'text/html', 'utf-8');
            try{
                $num = $mailer->send($message);
                DB::insert('captcha_log',array('captcha','user_id'))->values(array($captcha,$user['id']))->execute('mkmoney');
                $resp->captcha = $captcha;
            }
            catch (Swift_ConnectionException $e){
                $resp->msg =  'There was a problem communicating with SMTP: ' . $e->getMessage();
                $resp->ret = 402;
            }
        }else{
            $resp->msg = '用户不存在！';
            $resp->ret = 401;
        }
        echo $resp;
    }
    /**
     * 检查验证码
     */
    public function action_check_captcha(){
        $resp = $this->buildResp();
//        $this->query = array('userid'=>'5878919270','openuuid'=>'','captcha'=>'3052493','email'=>'liuqiunan@vip.qq.com');
        CodeLog::add('captcha',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("captcha", json_encode($this->query,JSON_UNESCAPED_UNICODE));
        $req = Arr::extract($this->query, array('userid','openuuid','captcha'));
        $userId = $this->get_id($req['userid']);
        if($userId){
            $qry = DB::select('captcha')->from('captcha_log')->where('user_id','=',$userId)
                    ->where('add_time','BETWEEN',array(
                            DB::expr('DATE_SUB('.DB::expr('NOW()').',INTERVAL 30 MINUTE)'),
                            DB::expr('NOW()')
                        ))->order_by('add_time','desc')->limit(1)->execute('mkmoney')->current();
            if($qry != NULL){
                if($qry['captcha'] != $req['captcha']){
                    $resp->msg = '验证码有误！';
                    $resp->ret = 406;
                }
            }else{
                $resp->msg = '验证码有误！';
                $resp->ret = 406;
            }
        }else{
            $resp->msg = '用户不存在！';
            $resp->ret = 401;
        }
        echo $resp;
    }
    /**
     * 绑定支付宝或者邮箱
     */
    public function action_bind(){
        $resp = $this->buildResp();
//        $this->query = array('userid'=>'5878919270','openuuid'=>'','captcha'=>'3052493','email'=>'liuqiunan@vip.qq.com');
        
        CodeLog::add('captcha',  '================='.  date('Y-m-d H:i:s',  time()));
        CodeLog::add("captcha", json_encode($this->query,JSON_UNESCAPED_UNICODE));
        $req = Arr::extract($this->query, array('userid','openuuid','captcha','email','mobile'));
        $userId = $this->get_id($req['userid']);
        if($userId){
            $qry = DB::select('captcha')->from('captcha_log')->where('user_id','=',$userId)
                    ->where('add_time','BETWEEN',array(
                            DB::expr('DATE_SUB('.DB::expr('NOW()').',INTERVAL 30 MINUTE)'),
                            DB::expr('NOW()')
                        ))->order_by('add_time','desc')->limit(1)->execute('mkmoney')->current();
            if($qry != NULL){
                if($qry['captcha'] != $req['captcha']){
                    $resp->msg = '验证码有误！';
                    $resp->ret = 406;
                }elseif($req['email'] !== NULL && $req['mobile'] === NULL){
                    $res = DB::update("user")->set(array('email'=>$req['email']))->where("id", "=", $userId)->execute("mkmoney");
                    
                }elseif($req['mobile'] !== NULL && $req['email'] ===NULL){
                    $res = DB::update("user")->set(array('mobile'=>$req['mobile']))->where("id", "=", $userId)->execute("mkmoney");
                }
            }else{
                $resp->msg = '验证码有误！';
                $resp->ret = 406;
            }
        }else{
            $resp->msg = '用户不存在！';
            $resp->ret = 401;
        }
        echo $resp;
    }
    /**
     * 解绑支付宝
     */
    public function action_unbind_alipay(){
        $resp = $this->buildResp();
        $req = Arr::extract($this->query, array('userid','openuuid'));
        $userId = $this->get_id($req['userid']);
        if($userId){
            DB::update("user")->set(array('alipay_account'=>''))->where("id", "=", $userId)->execute("mkmoney");
        }else{
            $resp->msg = '用户不存在！';
            $resp->ret = 401;
        }    
        echo $resp;
    }


    public function action_send_sms(){
        
    }

    

    
    

    /**
     * 是否可提现
     * @param type $userId
     * @return boolean
     */
    private function is_withdraw($userId){
        $res = false;
        $coin = DB::select(DB::expr("SUM(coin) as coin"))
                        ->from('account_log')->where('user_id','=',$userId)
                        ->where('event', '=', '')->execute('mkmoney')->current();
        $coin = $coin['coin'];
        if($coin>1000000){
            $res = true;
        }
        return $res;
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

        private function get_level_Info($user){
        $exp = $user['exp'];
        $level = 'G-';
        $nexp = $exp;
        if($exp < 100){
            $level = 'G';$nexp = '100';
        }elseif($exp >= 100&&$exp <300){
            $level = 'G+';$nexp = '300';            
        }elseif($exp >= 300&&$exp <600){
            $level = 'F-';$nexp = '600';          
        }elseif($exp >= 600&&$exp <1100){
            $level = 'F';$nexp = '1100';           
        }elseif($exp >= 1100&&$exp <1900){
            $level = 'F+';$nexp = '1900';           
        }else{
            $level = 'Max';$nexp = $exp;
        }
        return array(
            'next_level'=>$level,
            'exp'=>$user['exp'],
            'up_exp'=>$nexp,
            'priv' =>$user['priv']
        );
    }

    
    private function get_captcha(){
         $captcha = "";
         $prepare = array_merge(range('0', '9'));
         $count = count($prepare)-1;
         for($i=0;$i<6;$i++){
             $captcha .= $prepare[mt_rand(0, $count)];
         }
        return $captcha;
    }
    
    function fit_portrait($img){
        $image = Image::factory($img);
        $width = $image->width;
        $height = $image->height;
        if($height>$width)  {$offset_x = 0;$offset_y = round(($height-$width)/2);$height = $width;}
        if($width>$height)  {$offset_x = 0;$offset_y = round(($width-$height)/2);$width = $height;}
            $cut = $image->crop($width, $height,$offset_x,$offset_y)->save();
         return $cut;
    }
    
    private function get_info($user,$query){
        $User = ORM::factory('Users');
        $token = UserToken::encode($user['id']);
        $balance = $this->coinUnit(Model_UserAccount::get_balance($user['id']));//获取余额
        $question = $User->get_question($user['id']);//获取密保问题
        $level = $this->get_level_info($user);//获取等级信息
        $user = Arr::extract($user, array('userid','nick_name','password','user_name','real_name','level','email','mobile','portrait','alipay_account','trade_password'));
        $user['isset_pwd'] = $user['password'] == true ? "1" : "0";
        $user['isset_trade_pwd'] = $user['trade_password'] == true ? "1" : "0";
        unset($user['password'],$user['trade_password']);
        $user['question'] = $question;
        $info = array(
            'balance'=>$balance,
            'openuuid' => $query['openuuid'],
            'levelinfo'=>$level,
            'user'=>$user
        );
        return array($token,$info);
    }
}
