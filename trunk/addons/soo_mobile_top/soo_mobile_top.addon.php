<?php
if(!defined('__XE__')) exit();
// file : soo_mobile_top.addon.php
// author : misol (misol@korea.ac.kr)
// brief : 페이지를 최상단으로 스크롤

// called position이 애드온이 동작하는 코드가 없는 곳에서- 여기서 끝
if($called_position != 'before_module_proc' || Context::getResponseMethod() != 'HTML') return;
// 로봇에게는 보이지 않음.
if(isCrawler()) return;

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
	Context::loadFile(array('./addons/soo_mobile_top/js/scrolltop.js','body'),false);
}