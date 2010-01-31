<?php
// file soo_js_exif.addon.php
// author misol (misol@korea.ac.kr)
// brief 브라우저로 접속시 프레임을 나눕니다.
// ⓒ 2010 김민수.
// This addon uses MPL License based librarys, Binary Ajax 0.1.7 and Javascript EXIF Reader 0.1.4, which is written by Jacob Seidelin.

if(!defined('__ZBXE__')) exit();

if($called_position == 'after_module_proc' && Context::getResponseMethod() == 'HTML') {
	Context::addJsFile('./addons/soo_js_exif/js/binaryajax.js');
	Context::addJsFile('./addons/soo_js_exif/js/exif.js');
	Context::addJsFile('./addons/soo_js_exif/js/xe_exif_applyer.js');
}
?>