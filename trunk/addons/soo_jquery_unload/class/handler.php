<?php
class SooHTMLDisplayHandler {
	function init() { return; }
	function prepareToPrint(&$output) {
		$soo_handler = Context::get('soo_jquery_unload_handler');
		if(method_exists($soo_handler, "prepareToPrint")) $soo_handler->prepareToPrint($output);

		$addon_info = Context::get('soo_jquery_unload');

		if(!$addon_info->jquery) {
			$addon_info->jquery = '://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js';
			$addon_info->jqueryui = '://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js';
			$addon_info->ui_css = '://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/smoothness/jquery-ui.css';
		}
		if(!$addon_info->domain) {
			$request_uri = Context::getRequestUri();
		} else $request_uri = $addon_info->domain;

		$lang_type = Context::getLangType();

		if(isset($_SERVER['HTTPS'])) $trans_proc = 'https';
		else $trans_proc = 'http';

		// datepicker
		if($lang_type == 'jp') {
			$output = str_replace(
				array(
					'<script type="text/javascript" src="./common/js/plugins/ui/jquery.ui.datepicker-ko.js"></script>',
					'$.extend(option,$.datepicker.regional[\'jp\']);'
				),
				array(
					'<script type="text/javascript" src="'.$trans_proc.'://jquery-ui.googlecode.com/svn/trunk/ui/i18n/jquery.ui.datepicker-ja.js"></script>',
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
					'<script type="text/javascript" src="'.$trans_proc.'://jquery-ui.googlecode.com/svn/trunk/ui/i18n/jquery.ui.datepicker-'.$lang_type.'.js"></script>',
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
		$before_script = array(
			'<script type="text/javascript" src="./common/js/jquery.js"></script>',
			'<script type="text/javascript" src="./common/js/plugins/ui/jquery-ui.packed.js"></script>',
			'<link rel="stylesheet" href="./common/js/plugins/ui/jquery-ui.css" type="text/css" charset="UTF-8" media="all" />',
			'<link rel="stylesheet" href="./',
			'<script type="text/javascript" src="./'
		);
		$after_script = array(
			'<script type="text/javascript" src="'.$trans_proc.$addon_info->jquery.'"></script><script type="text/javascript">if(typeof(jQuery)==\'undefined\'){document.write(unescape("%3Cscript src=\''.$request_uri.'common/js/jquery.js\' type=\'text/javascript\'%3E%3C/script%3E"));}</script>',
			'<script type="text/javascript" src="'.$trans_proc.$addon_info->jqueryui.'"></script>',
			'<link rel="stylesheet" href="'.$trans_proc.$addon_info->ui_css.'" type="text/css" charset="UTF-8" media="all" />',
			'<link rel="stylesheet" href="'.$request_uri,
			'<script type="text/javascript" src="'.$request_uri
		);
		unset($request_uri);

		$output = str_replace($before_script, $after_script, $output);

		unset($before_script);
		unset($after_script);
		unset($soo_handler);
		unset($addon_info);
	}
}
?>