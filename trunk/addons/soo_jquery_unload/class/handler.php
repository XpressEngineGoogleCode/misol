<?php
class SooHTMLDisplayHandler {
	function init() {
		return;
	}
	function prepareToPrint(&$output) {
		$soo_handler = Context::get('soo_jquery_unload_handler');
		if(method_exists($soo_handler, "prepareToPrint")) $soo_handler->prepareToPrint($output);

		$addon_info = Context::get('soo_jquery_unload');

		if(!$addon_info->jquery_ui_css) {
			$addon_info->jquery_url = 'http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.4.2.min.js';
			$addon_info->jqueryui_url = 'http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.7/jquery-ui.min.js';
			$addon_info->jquery_ui_css = './common/js/plugins/ui/jquery-ui.css';
		}

		$lang_type = Context::getLangType();

		// datepicker
		if($lang_type == 'jp') {
			$output = str_replace(
				array(
					'<script type="text/javascript" src="./common/js/plugins/ui/jquery.ui.datepicker-ko.js"></script>',
					'$.extend(option,$.datepicker.regional[\'jp\']);'
				),
				array(
					'<script type="text/javascript" src="http://jquery-ui.googlecode.com/svn/trunk/ui/i18n/jquery.ui.datepicker-ja.js"></script>',
					'var soo_date_option = {dateFormat: \'yy-mm-dd\'}; $.extend(option,$.datepicker.regional[\'ja\'],soo_date_option);'
				),
				$output);
		} elseif($lang_type != 'en' && $lang_type != 'mn') {
			$output = str_replace(
				array(
					'<script type="text/javascript" src="./common/js/plugins/ui/jquery.ui.datepicker-ko.js"></script>',
					'$.extend(option,$.datepicker.regional[\''.$lang_type.'\']);'
				),
				array(
					'<script type="text/javascript" src="http://jquery-ui.googlecode.com/svn/trunk/ui/i18n/jquery.ui.datepicker-'.$lang_type.'.js"></script>',
					'var soo_date_option = {dateFormat: \'yy-mm-dd\'}; $.extend(option,$.datepicker.regional[\''.$lang_type.'\'],soo_date_option);'
				),
				$output);
		} else {
			// 필요 없을때 불러오지 않기
			$output = str_replace(
				array(
					'<script type="text/javascript" src="./common/js/plugins/ui/jquery.ui.datepicker-ko.js"></script>',
					'$.extend(option,$.datepicker.regional[\''.$lang_type.'\']);'
				),
				array(
					'',
					'var soo_date_option = {dateFormat: \'yy-mm-dd\'}; $.extend(option, soo_date_option);'
				),
				$output);
		}

		//common
		if(Context::isAllowRewrite()) {
			// 주소/mid/document_srl 형태의 주소에서 공통 파일을 주소/mid/common/~~에서 찾게 되어, 브라우저 캐시파일을 많이 생성하게 되는 문제 수정
			$request_uri = Context::getRequestUri();
			$before_script = array(
				'<script type="text/javascript" src="./common/js/jquery.js"></script>',
				'<script type="text/javascript" src="./common/js/plugins/ui/jquery-ui.packed.js"></script>',
				'<link rel="stylesheet" href="./common/js/plugins/ui/jquery-ui.css" type="text/css" charset="UTF-8" media="all" />',
				'<link rel="stylesheet" href="./',
				'<script type="text/javascript" src="./'
			);
			$after_script = array(
				'<script type="text/javascript" src="'.$addon_info->jquery_url.'"></script>',
				'<script type="text/javascript" src="'.$addon_info->jqueryui_url.'"></script>',
				'<link rel="stylesheet" href="'.$addon_info->jquery_ui_css.'" type="text/css" charset="UTF-8" media="all" />',
				'<link rel="stylesheet" href="'.$request_uri,
				'<script type="text/javascript" src="'.$request_uri
			);
			unset($request_uri);
		} else {
			$before_script = array(
				'<script type="text/javascript" src="./common/js/jquery.js"></script>',
				'<script type="text/javascript" src="./common/js/plugins/ui/jquery-ui.packed.js"></script>',
				'<link rel="stylesheet" href="./common/js/plugins/ui/jquery-ui.css" type="text/css" charset="UTF-8" media="all" />'
			);
			$after_script = array(
				'<script type="text/javascript" src="'.$addon_info->jquery_url.'"></script>',
				'<script type="text/javascript" src="'.$addon_info->jqueryui_url.'"></script>',
				'<link rel="stylesheet" href="'.$addon_info->jquery_ui_css.'" type="text/css" charset="UTF-8" media="all" />'
			);
		}

		$output = str_replace($before_script, $after_script, $output);

		unset($before_script);
		unset($after_script);
		unset($soo_handler);
		unset($addon_info);
	}
}
?>