<?php
class SooExloadDisplayHandler {
	function init() {
		return;
	}
	function prepareToPrint(&$output) {
		$soo_handler = Context::get('soo_exload_handler');
		if(method_exists($soo_handler, "prepareToPrint")) $soo_handler->prepareToPrint($output);

		$addon_info = Context::get('soo_exload');

		if(!$addon_info->xe_ver) {
			$addon_info->xe_ver = '1.4.5.7';
		}

		$output = str_replace(
			array(
				'<script type="text/javascript" src="./common/js/jquery.js"></script>',
				'<script type="text/javascript" src="./common/js/plugins/ui/jquery-ui.packed.js"></script>',
				'<link rel="stylesheet" href="./common/js/plugins/ui/jquery-ui.css" type="text/css" charset="UTF-8" media="all" />',
				'<script type="text/javascript" src="./common/js/'
			),
			array(
				'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>',
				'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>',
				'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/smoothness/jquery-ui.css" type="text/css" charset="UTF-8" media="all" />',
				'<script type="text/javascript" src="https://misol.googlecode.com/svn/xe_web/common/js/'
			),
			$output);

		//common
		if(Context::isAllowRewrite()) {
			// 주소/mid/document_srl 형태의 주소에서 공통 파일을 주소/mid/common/~~에서 찾게 되어, 브라우저 캐시파일을 많이 생성하게 되는 문제 수정
			$request_uri = Context::getRequestUri();
			$before_script = array(
				'<link rel="stylesheet" href="./',
				'<script type="text/javascript" src="./'
			);
			$after_script = array(
				'<link rel="stylesheet" href="'.$request_uri,
				'<script type="text/javascript" src="'.$request_uri
			);
			$output = str_replace($before_script, $after_script, $output);
			unset($request_uri);
		}

		unset($before_script);
		unset($after_script);
		unset($soo_handler);
		unset($addon_info);
	}
}
?>