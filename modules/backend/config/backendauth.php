<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'hash_method'  => 'sha256',
	'hash_key'     => "zhitou",
	'lifetime'     => 12096000,//1209600
	'session_type' => Session::$default,
	'session_key'  => 'backend_auth_user',

);
