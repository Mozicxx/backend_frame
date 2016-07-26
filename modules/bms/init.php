<?php

defined('SYSPATH') or die('No direct script access.');
Route::set('bms', '<directory>(/<controller>(/<action>(/<id>)))', array('directory' => '(bms)'))
        ->defaults(array(
            'controller' => 'home',
            'action' => 'index',
        ));
