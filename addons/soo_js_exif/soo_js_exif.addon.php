<?php
if(!defined('__ZBXE__')) exit();
// file soo_js_exif.addon.php
// author misol (misol@korea.ac.kr)
// brief 브라우저로 접속시 프레임을 나눕니다.
// ⓒ 2010 김민수.
// This addon uses MIT License based librarys, Binary Ajax 0.1.5, EXIF Reader 0.1.2, and ImageInfo 0.1.2, which is written by Jacob Seidelin.

if($called_position == 'after_module_proc' && Context::getResponseMethod() == 'HTML') {
	Context::addJsFile('./addons/soo_js_exif/js/xe_exif_applyer.js');
  Context::addJsFile('./addons/soo_js_exif/js/imageinfo/imageinfo.js');
	Context::addJsFile('./addons/soo_js_exif/js/imageinfo/binaryajax.js');
	Context::addJsFile('./addons/soo_js_exif/js/imageinfo/exif.js');
  Context::addCssFile('./addons/soo_js_exif/style/exif.css');
}
?>