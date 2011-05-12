<?php
if(!defined("__ZBXE__")) exit();
// file : soo_exload.addon.php
// author : misol (misol@korea.ac.kr)
if(Context::getResponseMethod() != 'HTML') return;

// called position이 애드온이 동작하는 코드가 없는 곳에서- 여기서 끝
if($called_position != 'before_display_content') return;

Context::set('soo_exload',$addon_info);
Context::set('soo_exload_handler',$handler);
unset($handler);

require_once("./addons/soo_exload/class/handler.php");
$handler = new SooExloadDisplayHandler();
?>