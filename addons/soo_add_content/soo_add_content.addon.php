<?php
if(!defined("__ZBXE__")) exit();
// file soo_add_content.addon.php
// author misol (misol@korea.ac.kr)
// brief 게시글의 상/하단에 내용을 출력할 수 있도록 한다.

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

if($called_position == 'before_display_content' && Context::getResponseMethod() == 'HTML') {
	if($mobile_set) {
		$addon_info->soo_top .= $addon_info->soo_top_m;
		$addon_info->soo_bottom .= $addon_info->soo_bottom_m;
	} else {
		$addon_info->soo_top .= $addon_info->soo_top_pc;
		$addon_info->soo_bottom .= $addon_info->soo_bottom_pc;
	}

	$output = str_replace("<!--BeforeDocument(", $addon_info->soo_top."<!--BeforeDocument(", $output);
	$output = str_replace("<!--AfterDocument(", $addon_info->soo_bottom."<!--AfterDocument(", $output);
}
?>
