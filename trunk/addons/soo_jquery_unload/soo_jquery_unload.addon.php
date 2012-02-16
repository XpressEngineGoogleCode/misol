<?php
if(!defined('__ZBXE__')) exit();
// XE 1.5 이상은 지원하지 않음.
if(defined('__XE__')) return;
// file : addon_example.addon.php
// author : misol (misol@korea.ac.kr)
if(Context::getResponseMethod() != 'HTML') return;

// called position이 애드온이 동작하는 코드가 없는 곳에서- 여기서 끝
if($called_position != 'before_display_content') return;

Context::set('soo_jquery_unload',$addon_info);
Context::set('soo_jquery_unload_handler',$handler);
unset($handler);

require_once("./addons/soo_jquery_unload/class/handler.php");
$handler = new SooHTMLDisplayHandler();
?>