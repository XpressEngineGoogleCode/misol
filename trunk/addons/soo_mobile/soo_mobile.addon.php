<?php
if(!defined('__XE__')) exit();
// file : soo_mobile.addon.php
// author : misol (misol221@paran.com)
// called position이 애드온이 동작하는 코드가 없는 곳에서- 여기서 끝
if($called_position != 'before_display_content' || Context::getResponseMethod() != 'HTML') return;
// 스마트폰 접속인지 확인하는 부분.
if(!isset($mobile_set)) {
	$mobile_set = false;
	if(Mobile::isFromMobilePhone()) {
		Context::loadFile(array('./common/js/jquery.min.js','head', NULL,-100000),true);
		$mobile_set = true;
	}
} elseif($mobile_set===true) {
	Context::loadFile(array('./common/js/jquery.min.js','head', NULL,-100000),true);
}

if($mobile_set == true) {
	Context::loadFile(array('./addons/soo_mobile/js/scrolltop.js','body'),false);
	Context::loadFile(array('./addons/soo_mobile/css/mobile.css','all'),false);
	Context::addHtmlFooter('<div id="waitingforserverresponse"></div>');

	$oEditorController = &getController('editor');
	$output = $oEditorController->transComponent($output);
}