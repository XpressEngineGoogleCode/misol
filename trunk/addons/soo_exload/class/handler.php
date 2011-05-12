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
				'<link rel="stylesheet" href="./common/css/default.css" type="text/css" charset="UTF-8" media="all" />',
				'<link rel="stylesheet" href="./common/css/button.css" type="text/css" charset="UTF-8" media="all" />',
				'<link rel="stylesheet" href="./common/js/plugins/ui/jquery-ui.css" type="text/css" charset="UTF-8" media="all" />',
				'<script type="text/javascript" src="./common/js/jquery.js"></script>',
				'<script type="text/javascript" src="./common/js/x.js"></script>',
				'<script type="text/javascript" src="./common/js/common.js"></script>',
				'<script type="text/javascript" src="./common/js/js_app.js"></script>',
				'<script type="text/javascript" src="./common/js/xml_handler.js"></script>',
				'<script type="text/javascript" src="./common/js/xml_js_filter.js"></script>',
				'<script type="text/javascript" src="./common/js/plugins/ui/jquery-ui.packed.js"></script>',
				'<script type="text/javascript" src="./common/js/plugins/ui/jquery.ui.datepicker-ko.js"></script>'
			),
			array(
				'<link rel="stylesheet" href="https://files.ucloud.com/pf/D600737_206749_928585" type="text/css" charset="UTF-8" media="all" />',
				'<link rel="stylesheet" href="https://files.ucloud.com/pf/D600737_206749_928573" type="text/css" charset="UTF-8" media="all" />',
				'<link rel="stylesheet" href="https://files.ucloud.com/pf/D600737_206749_928801" type="text/css" charset="UTF-8" media="all" />',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928594"></script>',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928516"></script>',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928581"></script>',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928507"></script>',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928518"></script>',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928512"></script>',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928815"></script>',
				'<script type="text/javascript" src="https://files.ucloud.com/pf/D600737_206749_928819"></script>'
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