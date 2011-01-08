<?php
if(!defined("__ZBXE__")) exit();
// file : a_soo_wikidoc_pointfixer.addon.php
// author : misol (misol221@paran.com)
if($called_position == 'before_display_content' && Context::getResponseMethod() == 'HTML') {
	$output=preg_replace("/<\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)--><div class=\"document_([0-9]*)_([0-9\-]*) xe_content\"><\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)--><div class=\"document_([0-9]*)_([0-9\-]*) xe_content\">/i" , "<!--BeforeDocument($1,$2)--><div class=\"document_$3_$4 xe_content\">" , $output);
	$output=preg_replace("/<\/div><\!--AfterDocument\(([0-9]*),([0-9\-]*)\)--><\/div><\!--AfterDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "</div><!--AfterDocument($1,$2)-->", $output);
}
?>