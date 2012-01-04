<?php
if(!defined('__ZBXE__') && !defined('__XE__')) exit();
// google plus +1버튼 삽입 애드온
// by misol (misol.kr@gmail.com)

// called position이 애드온이 동작하는 코드가 없는 곳에서- 여기서 끝
if($called_position != 'before_display_content') return;
if(Context::getResponseMethod() != 'HTML' || Context::get('module')=='addon') return;
// 로봇에게는 보이지 않음.
if(function_exists('isCrawler')) if(isCrawler()) return;

if($called_position == 'before_display_content') {
	// 몽골어는 아직 지원 안하는듯
	$langtype = array(
		'ko' => 'ko',
		'en' => 'en-US',
		'zh-CN' => 'zh-CN',
		'jp' => 'ja',
		'es' => 'es',
		'ru' => 'ru',
		'fr' => 'fr',
		'zh-TW' => 'zh-TW',
		'vi' => 'vi',
		'mn' => 'en-US',
		'tr' => 'tr'
	);
	$lang_type = Context::getLangType();
	if(isset($langtype[$lang_type])) $ggl_langtype = $langtype[$lang_type];
	else $ggl_langtype = 'en-US';

	if(isset($_SERVER['HTTPS'])) $trans_proc = 'https';
	else $trans_proc = 'http';

	Context::addHtmlHeader('<script type="text/javascript" src="'.$trans_proc.'://apis.google.com/js/plusone.js">'."\n".'{"lang":"'.$ggl_langtype.'","parsetags": "explicit"}'."\n".'</script>');

	if(!$addon_info->text_align) $addon_info->text_align = 'left';
	if(!$addon_info->size) $addon_info->size = 'standard';
	if(!$addon_info->count) $addon_info->count = 'true';
	$GLOBALS['soo_googleplus'] = $addon_info;

	if($addon_info->text_position == 1) {
		$output=preg_replace_callback("/<\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)-->/i", create_function('$matches','if(\'.$matches[1].\' < 0) return $matches[0]; $addon_info = $GLOBALS[\'soo_googleplus\']; return \'<div style="text-align:\'.$addon_info->text_align.\'"><div id="g-plusone-\'.$matches[1].\'" style="float:\'.$addon_info->text_align.\'"></div>\'.\'<script type="text/javascript">//<!--\'."\n".\'gapi.plusone.render("g-plusone-\'.$matches[1].\'", {"href":"\'.getFullUrl(\'\',\'document_srl\',$matches[1]).\'","size": "\'.$addon_info->size.\'", "count": "\'.$addon_info->count.\'"});\'."\n".\'//-->\'."\n".\'</script></div>\'.$matches[0];'), $output);
	} else {
		$output=preg_replace_callback("/<\!--AfterDocument\(([0-9]*),([0-9\-]*)\)-->/i", create_function('$matches','if(\'.$matches[1].\' < 0) return $matches[0]; $addon_info = $GLOBALS[\'soo_googleplus\']; return $matches[0].\'<div style="text-align:\'.$addon_info->text_align.\'"><div id="g-plusone-\'.$matches[1].\'"></div>\'.\'<script type="text/javascript">//<!--\'."\n".\'gapi.plusone.render("g-plusone-\'.$matches[1].\'", {"href":"\'.getFullUrl(\'\',\'document_srl\',$matches[1]).\'","size": "\'.$addon_info->size.\'", "count": "\'.$addon_info->count.\'"});\'."\n".\'//-->\'."\n".\'</script></div>\';'), $output);
	}
	if(Context::get('document_srl') && $addon_info->comment_set != 1) {
		$output=preg_replace_callback("/<\!--AfterComment\(([0-9]*),([0-9\-]*)\)-->/i", create_function('$matches','if(\'.$matches[1].\' < 0) return $matches[0]; $addon_info = $GLOBALS[\'soo_googleplus\']; $document_srl = Context::get(\'document_srl\'); return $matches[0].\'<div style="text-align:\'.$addon_info->text_align.\'"><div id="g-plusone-\'.$matches[1].\'"></div>\'.\'<script type="text/javascript">//<!--\'."\n".\'gapi.plusone.render("g-plusone-\'.$matches[1].\'", {"href":"\'.getFullUrl(\'\',\'document_srl\',$document_srl).\'#comment_\'.$matches[1].\'","size": "\'.$addon_info->size.\'", "count": "\'.$addon_info->count.\'"});\'."\n".\'//-->\'."\n".\'</script></div>\';'), $output);
	}
	unset($GLOBALS['soo_googleplus']);
	unset($lang_type);
	unset($ggl_langtype);
	unset($langtype);
}
?>