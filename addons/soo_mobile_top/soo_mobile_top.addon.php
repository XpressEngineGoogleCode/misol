<?php
if(!defined("__ZBXE__")) exit();
// file : soo_mobile_top.addon.php
// author : misol (misol@korea.ac.kr)
// brief : 페이지를 최상단으로 스크롤

// called position이 애드온이 동작하는 코드가 없는 곳에서- 여기서 끝
if($called_position != 'before_module_proc' || Context::getResponseMethod() != 'HTML') return;
// 로봇에게는 보이지 않음.
if(isCrawler()) return;

// 스마트폰 접속인지 확인하는 부분. XE 모든 버전에서 지원하지 않는 기능이므로 해당 기능이 있는지 먼저 확인. Mobile 이 좀 더 최신 버전이지만 구 버전에서도 애드온이 켜져있으면 smartphoneXE가 존재.
if(!isset($mobile_set)) {
	$mobile_set = false;
	if(class_exists('Mobile')) {
		if(Mobile::isFromMobilePhone()) {
			$mobile_set = true;
		}
	} elseif(class_exists('smartphoneXE')) {
		if(smartphoneXE::isFromSmartPhone()) {
			$mobile_set = true;
		}
	}
}
if($mobile_set == true) {
	Context::addJsFile("./common/js/jquery.js");
	Context::addJsFile("./addons/soo_mobile_top/js/scrolltop.js");
}
?>