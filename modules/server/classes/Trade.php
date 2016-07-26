<?php

final class Trade
{
    private $account;
    public  $userId = null;
    public  $event = null;
    public  $money = '0';


    public function __construct() {
        $this->account = ORM::factory('UserAccount');
    }

    public function income(){
        $this->cashFlow();
    }
    
    public function expend(){
        $this->money = 'coin-'.$this->money;
        $this->cashFlow();
    }
    
    private function cashFlow(){
        $account = $this->account;
        $account->values(array('user_id'=>$this->userId,'event'=>$this->event,'coin' => $this->money));
        $account->save();
    }
}

