<?php
if(!defined("__ZBXE__")) exit();
// author 김민수 misol221@paran.com
// distributed by misol under creative commons licence (Attribution 2.0 Korea), http://creativecommons.org/licenses/by/2.0/kr/ .

if($called_position != 'before_module_init') return;

if(trim($addon_info->do_useragent) && isset($addon_info->do_useragent)) {
	$do_useragent = explode("\n",$addon_info->do_useragent);
	foreach($do_useragent as $value) {
		if(trim($value) && trim($value) != '') if(stristr($_SERVER['HTTP_USER_AGENT'],trim($value)) != FALSE) {
			header("HTTP/1.1 404 Not Found");
			header("Status: 404 Not Found");
			header("X-Powered-By: misol");
			exit();
		}
	}
}
?>