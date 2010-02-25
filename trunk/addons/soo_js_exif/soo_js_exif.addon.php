<?php
if(!defined('__ZBXE__')) exit();
// file soo_js_exif.addon.php
// author misol (misol@korea.ac.kr)
// brief 자바스크립트를 이용해 이미지의 Exif 정보를 보여줍니다. Show Exif information of the images in a document on XE with only javascript.
// ⓒ 2010 김민수.
// This addon uses MIT License based librarys, Binary Ajax 0.1.5, EXIF Reader 0.1.2, and ImageInfo 0.1.2, which is written by Jacob Seidelin.

if($called_position == 'after_module_proc' && Context::getResponseMethod() == 'HTML') {
  // 언어파일을 읽음
  Context::loadLang(_XE_PATH_.'addons/soo_js_exif/lang');
  $soo_exif_string = Context::getLang('soo_exif_string');
  $soo_exif_point = Context::getLang('soo_exif_point');
  if($soo_exif_string || $soo_exif_point) {
    Context::addHtmlHeader('<script type="text/javascript">//<![CDATA['."\n".
      '<!--'."\n".'var soo_exif_PointerLang = {'."\n".$soo_exif_point."\n".'}'."\n".'var soo_exif_StringValues = {'."\n".$soo_exif_string."\n".'}'."\n".'//-->'."\n".'//]]>'."\n".'</script>'."\n");
  }

  Context::addJsFile('./addons/soo_js_exif/js/xe_exif_applyer.js');
  Context::addJsFile('./addons/soo_js_exif/js/imageinfo/imageinfo.js');
  Context::addJsFile('./addons/soo_js_exif/js/imageinfo/binaryajax.js');
  Context::addJsFile('./addons/soo_js_exif/js/imageinfo/exif.js');
  //Context::addJsFile('./addons/soo_js_exif/js/imageinfo/jquery.exif.js');
  Context::addCssFile('./addons/soo_js_exif/style/exif.css');
}
?>