<?php
if(!defined("__ZBXE__")) exit();
/**
 * @file soo_body_content.addon.php
 * @author misol (misol@korea.ac.kr)
 * @brief 게시글의 상/하단에 내용을 출력할 수 있도록 한다.
 * 모든 출력이 끝난후에 사용이 됨.
**/
if($called_position != 'before_module_init' || Context::getResponseMethod()!=='HTML') return;
Context::addBodyHeader($addon_info->soo_body);
?>
