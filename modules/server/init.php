<?php

defined('SYSPATH') or die('No direct script access.');
Route::set('app', '<directory>(/<controller>(/<action>(/<id>)))', array('directory' => '(v1)'))
        ->defaults(array(
            'director' => 'v1',
            'controller' => 'ad',
            'action' => 'list',
        ));
