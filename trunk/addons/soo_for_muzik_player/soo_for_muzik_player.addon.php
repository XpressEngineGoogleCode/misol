<?php
if(!defined("__ZBXE__")) exit();
// file soo_for_muzik_player.addon.php
// author misol (misol221@paran.com)
// brief 브라우저로 접속시 프레임을 나눕니다.
// ⓒ 2010-2011 김민수.
// 특정 액션에서 동작 안함
if(in_array(Context::get('module'), array('admin','editor')) || Context::get('outframe_set') == true || $_SESSION['soo_for_muzik_player']['outframe'] == 2) return;
// 로봇일 경우 동작 안함.
if(function_exists('isCrawler')) if(isCrawler()) return;

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
// 모바일에서는 동작 안함.
if($mobile_set == true) return;

if($called_position == 'before_module_proc') {
	if(Context::get('outframe')=='true') {
		FileHandler::writeFile('./files/cache/addons/soo_for_muzik_player/ip/ip_'.urlencode($_SERVER['REMOTE_ADDR']).'.txt', 'noframe');
		$_SESSION['soo_for_muzik_player']['outframe'] = 2;
		Context::set('outframe_set',true);
	}
}
if(Context::get('outframe_set') == true) return;

if(Context::getResponseMethod()=='XMLRPC' && Context::get('module') == 'addon' && Context::get('act') == 'soo_for_muzik_player' && !Context::get('soo_m_output')) {
	if(Context::get('do') == 'doFrameSessionStart') {
		$_SESSION['soo_for_muzik_player']['doFrameSessionStart'] = true;
		$output_xml	= "<response>\n<error>0</error>\n<message>success</message>\n</response>";
		Context::set('soo_m_output',$output_xml);
	}
	if(Context::get('do') == 'doFrameSessionChecker') {
		if($_SESSION['soo_for_muzik_player']['doFrameSessionStart']) {
			$output_xml	= "<response>\n<error>0</error>\n<message>FrameSessionStartSuccess</message>\n</response>";
			Context::set('soo_m_output',$output_xml);
		}
		else {
			$output_xml	= "<response>\n<error>0</error>\n<message>FrameSessionStartFail</message>\n</response>";
			Context::set('soo_m_output',$output_xml);
		}
	}
}

if($called_position == 'before_display_content' && Context::getResponseMethod() == 'XMLRPC') {
	if(Context::get('soo_m_output')) {
		$output = Context::get('soo_m_output');
	}
}
if(Context::getResponseMethod() == 'HTML') {
	// 프레임 탈출 코드.
	if($called_position == 'before_module_proc') {
		$check = FileHandler::readFile('./files/cache/addons/soo_for_muzik_player/ip/ip_'.urlencode($_SERVER['REMOTE_ADDR']).'.txt');
		if($check) {
			Context::set('outframe_set',true);
			$_SESSION['soo_for_muzik_player']['outframe'] = 2;
			return;
		}
	} elseif($called_position == 'before_display_content') {
		//POST 내용 있으면 동작 안함.
		if(is_array($_POST)) if(count($_POST)) return;
		if($_SESSION['soo_for_muzik_player']['doFrameSessionStart']) {
			$uri = Context::getRequestUri();
			if(is_array($_GET)) {
				if(count($_GET)) {
					$uri = Context::getRequestUrl();
				}
			}

			if(Context::getRequestUri() != $uri) {
				$exit_uri = $uri.'&outframe=true';
				$uri .= '&fr=true';
			}
			else {
				$exit_uri = $uri.'?outframe=true';
				$uri .= '?fr=true';
			}

			$_SESSION['soo_for_muzik_player']['doFrameSessionStart'] = false;
			$exit_uri = htmlspecialchars($exit_uri);
			$uri = htmlspecialchars($uri);

			$addon_info->frame_border = intval($addon_info->frame_border);

			if(!$addon_info->frame_size && $addon_info->frame_size != 0) $addon_info->frame_size = 20;
			if(!$addon_info->frame_border) $addon_info->frame_border = '0';
			if(!$addon_info->frame_resize) $addon_info->frame_resize = 'noresize';

			$frame_attrbt = "framespacing=\"0\" border=\"".$addon_info->frame_border."\" ";
			if($addon_info->frame_border == '0') $frame_attrbt .= "frameborder=\"0\" ";
			$attrbt = '';
			if($addon_info->frame_resize != 'Y') {
				$attrbt .= $addon_info->frame_resize.'="'.$addon_info->frame_resize.'" ';
			}
			if(!$addon_info->frame_scroll || $addon_info->frame_scroll == 0) {
				$attrbt .= 'scrolling="no" ';
			}

			if($addon_info->frame_position == 'left' || $addon_info->frame_position == 'right') $frame_pos = 'cols';
			else $frame_pos = 'rows';

			if(!$addon_info->frame_position || $addon_info->frame_position == 'up' || $addon_info->frame_position == 'left') $size = $addon_info->frame_size.',*';
			else $size = '*,'.$addon_info->frame_size;

			$langtype = Context::getLangType();
			$title = Context::getBrowserTitle();
			$output = '';
			$output .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
	"http://www.w3.org/TR/html4/frameset.dtd">
	<html>
	<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<title>'.$title.'</title>
	<script type="text/javascript">
	//<!--
	var is_sooframe = true;
	var is_sooframe_loaded = false;
	function framelct() {
		if(!is_sooframe_loaded) {
			document.getElementById("contents_frame").src = document.getElementById("contents_frame").src;
			document.getElementById("new_frame").src = document.getElementById("new_frame").src;
		}
	}
	//--></script>
	</head>';
	$output .= sprintf("<frameset %s=\"%s\" onload=\"framelct()\" %s>", $frame_pos, $size, $frame_attrbt);
			if(!$addon_info->frame_position || $addon_info->frame_position == 'up' || $addon_info->frame_position == 'left') {
				$output .= sprintf(' <frame id="new_frame" name="new_frame" src="%s" %s >', $addon_info->frame_page, $attrbt);
			}

			$output .= sprintf(' <frame id="contents_frame" name="contents_frame" src="%s" frameborder="%s" style="border-width: 0px;" scrolling="auto" marginheight="0" marginwidth="0">', $uri, $addon_info->frame_border);

			if($addon_info->frame_position == 'down' || $addon_info->frame_position == 'right') {
				$output .= sprintf(' <frame id="new_frame" name="new_frame" src="%s" %s >', $addon_info->frame_page, $attrbt);
			}
			$output .= "\n";
			$output .= '<noframes><body> <p>죄송합니다. 이 브라우저는 프레임셋을 지원하지 않습니다.<br>Sorry, your browser does not handle frames!</p> <p>프레임 셋을 제거하고 페이지를 계속 보시려면 아래 링크를 클릭해주세요.<br>If you want to see this site without frames, click the following link.<br>';
			$output .= ' <a href="'.$exit_uri.'">'.$exit_uri.'</a></p> </body> </noframes> </frameset> </html>';
			// 변경된 문서가 헤더를 제대로 달고 나가고, 다른 애드온들에 의해 HTML편집 당하는 일 없게 JSON방식인것처럼 속이기(?!) 이런 방식으로 출력하는 것이 아니었으나... 어떤 홈페이지에서 작동 안되는 것을 발견하여서... 하다보니.. 이 방법 외엔 없었다 ㅜ.ㅜ;
			Context::setResponseMethod('JSON');

		} else {
			Context::addJsFile('./addons/soo_for_muzik_player/js/soo_fld.js');
		}
	}
	return;
}
?>