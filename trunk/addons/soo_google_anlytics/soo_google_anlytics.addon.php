<?php
if(!defined("__ZBXE__")) exit();
// author : misol (misol221@paran.com; misol.kr@gmail.com)
// license : Creative Commons License Attribution-ShareAlike 2.0 Korea

if($called_position != 'before_module_proc' || Context::getResponseMethod() != 'HTML') return;
if(!$addon_info->api_key) return;

// 관리자는 분석하지 않음
if(Context::get('is_logged')) {
	$logged_info = Context::get('logged_info');
	$oModuleModel = &getModel('module');
	if($logged_info->is_admin == "Y" || $oModuleModel->isSiteAdmin($logged_info)) return;
}

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
//구글에서 코드를 안읽어주는듯?;; 일단 테스트용으로 모두 컴퓨터 코드만 띄우기
$mobile_set = false;
if($mobile_set != true) {
	$header = "<script type=\"text/javascript\">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '".strtoupper(trim($addon_info->api_key))."']);
	_gaq.push(['_setDomainName', 'none']);
	_gaq.push(['_setAllowLinker', true]);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

</script>";
	Context::addHtmlHeader($header);
} else {
	// Copyright 2009 Google Inc. All Rights Reserved.
	// modified by misol for XE
	$GA_ACCOUNT = str_replace('UA', 'MO', strtoupper(trim($addon_info->api_key)));
	$GA_PIXEL = Context::getRequestUri()."addons/soo_google_anlytics/ga.php";

		$url = "";
		$url .= $GA_PIXEL . "?";
		$url .= "utmac=" . $GA_ACCOUNT;
		$url .= "&utmn=" . rand(0, 0x7fffffff);
		$referer = $_SERVER["HTTP_REFERER"];
		$query = $_SERVER["QUERY_STRING"];
		$path = $_SERVER["REQUEST_URI"];
		if (empty($referer)) {
			$referer = "-";
		}
		$url .= "&utmr=" . urlencode($referer);
		if (!empty($path)) {
			$url .= "&utmp=" . urlencode($path);
		}
		$url .= "&guid=ON";
		$url = str_replace("&", "&amp;", $url);
	Context::addHtmlFooter('<img src="' . $url . '" />');

}
unset($mobile_set);
?>