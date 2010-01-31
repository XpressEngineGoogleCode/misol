<?php
if(!defined("__ZBXE__")) exit();
/**
 * @file soo_add_content.addon.php
 * @author misol (misol@korea.ac.kr)
 * @brief 게시글의 상/하단에 내용을 출력할 수 있도록 한다.
**/
if($called_position != 'before_display_content' || Context::getResponseMethod() != 'HTML') return;
$output=preg_replace("/<\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)-->/i" , $addon_info->soo_top."<!--BeforeDocument($1,$2)-->" , $output);
$output=preg_replace("/<\!--AfterDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<!--AfterDocument($1,$2)-->".$addon_info->soo_bottom , $output);
?>
