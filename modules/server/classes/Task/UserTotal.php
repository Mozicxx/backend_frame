<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Task_UserTotal extends Minion_Task{
    protected function _execute(array $params) {
        $this->new_feedback();
    }
    
    private function new_feedback(){
          DB::insert('feedback',array('user_id','content'))->values(array('88','现在时间：'.time()))->execute('mkmoney');
    }

}
