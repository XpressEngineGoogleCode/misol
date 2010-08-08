<?php
if(!defined("__ZBXE__")) exit();
// file soo_add_content.addon.php
// author misol (misol@korea.ac.kr)
// brief 게시글의 상/하단에 내용을 출력할 수 있도록 한다.
if($called_position == 'before_display_content' && Context::getResponseMethod() == 'HTML') {
	$output = str_replace("<!--BeforeDocument(", $addon_info->soo_top."<!--BeforeDocument(", $output);
	$output = str_replace("<!--AfterDocument(", $addon_info->soo_bottom."<!--AfterDocument(", $output);
}
?>
