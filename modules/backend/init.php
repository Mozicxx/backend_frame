<?php

defined('SYSPATH') or die('No direct script access.');
Route::set('backend', '<directory>(/<controller>(/<action>(/<id>)))', array('directory' => '(backend)'))
        ->defaults(array(
            'controller' => 'home',
            'action' => 'index',
        ));
