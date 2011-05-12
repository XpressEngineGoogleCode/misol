<?php
if(!defined("__ZBXE__")) exit();
// file : soo_mcrblog_link.addon.php
// author : misol (misol@korea.ac.kr)
// license : Creative Commons License Attribution-ShareAlike 2.0 Korea (저작자표시-동일조건변경허락 2.0 대한민국) http://creativecommons.org/licenses/by-sa/2.0/kr/
// brief : 마이크로 블로그에 글의 링크를 퍼갈 수 있게 합니다.

// called position이 애드온이 동작하는 코드가 없는 곳에서- 여기서 끝
if($called_position != 'before_display_content' && $called_position != 'before_module_init') return;
// 로봇에게는 보이지 않음.
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

if($called_position == 'before_display_content' && Context::getResponseMethod() == 'HTML') {
	// 하단 자바스크립트 이동을 지원하는지 XE 버전으로 체크
	$body_js_support = false;
	if(version_compare(__ZBXE_VERSION__, '1.4.4.1', ">")) {
		$body_js_support = true;
	}

	$button_class = '';

	// 모바일 접속인 경우 모바일 버튼 설정
	if($mobile_set == true) {
		if(isset($addon_info->mobile_button_type) && $addon_info->mobile_button_type != 'none') $addon_info->button_type = $addon_info->mobile_button_type;
		Context::addJsFile("./common/js/jquery.js");
		Context::addJsFile("./common/js/xml_handler.js");
	}

	// 버튼 설정별로 다른 CSS 파일을 불러옵니다.
	if(!isset($addon_info->button_type) || (is_numeric($addon_info->button_type) && intval($addon_info->button_type) == 0)) $addon_info->button_type = 'oneBtn';
	if($addon_info->button_type =='oneBtn') Context::addCssFile('./addons/soo_mcrblog_link/css/oneBtn.css');
	elseif($addon_info->button_type =='oneBtn_mini') Context::addCssFile('./addons/soo_mcrblog_link/css/oneBtn_mini.css');
	elseif($addon_info->button_type =='oneBtn_mobile') Context::addCssFile('./addons/soo_mcrblog_link/css/oneBtn_mobile.css');
	else $button_class = 'button';

	// 오른쪽 정렬이 기본값
	if(!$addon_info->text_align) $addon_info->text_align = 'right';

	// 드롭다운 없는 스크랩 버튼
	if($addon_info->button_type =='oneBtn' || $addon_info->button_type =='oneBtn_mini' || $addon_info->button_type =='oneBtn_mobile') {
		if($body_js_support == false) {
			Context::addJsFile('./addons/soo_mcrblog_link/js/oneBtn.mini.js');
		} else {
			Context::addJsFile('./addons/soo_mcrblog_link/js/oneBtn.mini.js', false, '', null, 'body');
		}
		// 기본 가로 크기 설정. IE7에서 이렇게 해야 잘 보이길래
		$width = '150';
		if($addon_info->button_type =='oneBtn_mini') $width = $width - 52;
		elseif($addon_info->button_type =='oneBtn_mobile') $width = $width - 10;

		if($addon_info->ex_use) { // 확장 ShopXE 등
			$document_srl = intval(Context::get('document_srl'));
			$oDocumentModel = &getModel('document');
			$oDocument = $oDocumentModel->getDocument(Context::get('document_srl'), false, false);

			if($oDocument->isExists()) {
				$document = $oDocument->getTransContent(false,false,false,false);
				$btn_text = '';
				if($mobile_set == true) $btn_text .= "<a class=\"SooLinkerAddon Facebook\" href=\"#\" onclick=\"soo_Linker(0,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a class=\"SooLinkerAddon Twitter\" href=\"#\" onclick=\"soo_Linker(1,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a class=\"SooLinkerAddon me2day\" href=\"#\" onclick=\"soo_Linker(2,".$document_srl.");return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a class=\"SooLinkerAddon Yozm\" href=\"#\" onclick=\"soo_Linker(3,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
				else  $btn_text .= "<a class=\"SooLinkerAddon Facebook\" href=\"#\" onclick=\"soo_Linker(0,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a class=\"SooLinkerAddon Twitter\" href=\"#\" onclick=\"soo_Linker(1,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a class=\"SooLinkerAddon me2day\" href=\"#\" onclick=\"soo_Linker(2,".$document_srl.");return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a class=\"SooLinkerAddon Yozm\" href=\"#\" onclick=\"soo_Linker(3,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
				if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') {
					$width = $width + 24;
					if($addon_info->button_type =='oneBtn_mobile') $width = $width + 11;
					if($mobile_set == true) $btn_text .= "<a class=\"SooLinkerAddon Cyworld\" href=\"#\" onclick=\"soo_Linker(4,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
					else $btn_text .= "<a class=\"SooLinkerAddon Cyworld\" href=\"#\" onclick=\"soo_Linker(4,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
				}
				if($addon_info->text_position == 1) $output = str_replace($document,"<div style=\"text-align:".$addon_info->text_align."\"><div style=\"width:".$width."px;\" class=\"soo_link_popup_menu\"><span class=\"SooLinkerAddon Scrap\">".Context::getLang('cmd_scrap')."</span>".$btn_text."</div></div>".$document ,$output);
				else $output = str_replace($document,$document."<div style=\"text-align:".$addon_info->text_align."\"><div style=\"width:".$width."px;\" class=\"soo_link_popup_menu\"><span class=\"SooLinkerAddon Scrap\">".Context::getLang('cmd_scrap')."</span>".$btn_text."</div></div>",$output);
			}

			unset($oDocumentModel);
			unset($oDocument);
		} else {
			$btn_text = '';
			if($mobile_set == true) $btn_text .= "<a class=\"SooLinkerAddon Facebook\" title=\"Facebook\" href=\"#\" onclick=\"soo_Linker(0,$1);return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a class=\"SooLinkerAddon Twitter\" title=\"Twitter\" href=\"#\" onclick=\"soo_Linker(1,$1);return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a class=\"SooLinkerAddon me2day\" title=\"me2day\" href=\"#\" onclick=\"soo_Linker(2,$1);return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a class=\"SooLinkerAddon Yozm\" title=\"Yozm\" href=\"#\" onclick=\"soo_Linker(3,$1);return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
			else $btn_text .= "<a class=\"SooLinkerAddon Facebook\" title=\"Facebook\" href=\"#\" onclick=\"soo_Linker(0,$1);return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a class=\"SooLinkerAddon Twitter\" title=\"Twitter\" href=\"#\" onclick=\"soo_Linker(1,$1);return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a class=\"SooLinkerAddon me2day\" title=\"me2day\" href=\"#\" onclick=\"soo_Linker(2,$1);return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a class=\"SooLinkerAddon Yozm\" title=\"Yozm\" href=\"#\" onclick=\"soo_Linker(3,$1);return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
			if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') {
				$width = $width + 24;
				if($addon_info->button_type =='oneBtn_mobile') $width = $width + 11;
				if($mobile_set == true) $btn_text .= "<a class=\"SooLinkerAddon Cyworld\" title=\"Cyworld\" href=\"#\" onclick=\"soo_Linker(4,$1);return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
				else $btn_text .= "<a class=\"SooLinkerAddon Cyworld\" title=\"Cyworld\" href=\"#\" onclick=\"soo_Linker(4,$1);return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
			}
			if($addon_info->text_position == 1) $output=preg_replace("/<\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<div style=\"text-align:".$addon_info->text_align.";\"><div style=\"width:".$width."px;\" class=\"soo_link_popup_menu\"><span class=\"SooLinkerAddon Scrap\">".Context::getLang('cmd_scrap')."</span>".$btn_text."</div></div><!--BeforeDocument($1,$2)-->" , $output);
			else $output=preg_replace("/<\!--AfterDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<!--AfterDocument($1,$2)--><div style=\"text-align:".$addon_info->text_align.";\"><div style=\"width:".$width."px;\" class=\"soo_link_popup_menu\"><span class=\"SooLinkerAddon Scrap\">".Context::getLang('cmd_scrap')."</span>".$btn_text."</div></div>", $output);
		}
	} else { // 드롭다운 메뉴가 있는 스크랩 메뉴
		if($button_class == 'button' && $addon_info->button_type != 1) $button_class .= ' '.$addon_info->button_type;

		if($addon_info->ex_use) { // 확장
			$document_srl = intval(Context::get('document_srl'));
			$oDocumentModel = &getModel('document');
			$oDocument = $oDocumentModel->getDocument(Context::get('document_srl'), false, false);

			if($oDocument->isExists()) {
				$document = $oDocument->getTransContent(false,false,false,false);
				if($addon_info->text_position == 1) $output = str_replace($document,"<div class=\"soo_link_popup_menu\" style=\"text-align:".$addon_info->text_align.";\"><a href=\"#popup_menu_area\" class=\"".$button_class."\" onclick=\"return false\"><span class=\"SooLinkerAddon_".$document_srl."\">".Context::getLang('cmd_scrap')."</span></a></div>".$document ,$output);
				else $output = str_replace($document,$document."<div class=\"soo_link_popup_menu\" style=\"text-align:".$addon_info->text_align.";\"><a href=\"#popup_menu_area\" class=\"".$button_class."\" onclick=\"return false\"><span class=\"SooLinkerAddon_".$document_srl."\">".Context::getLang('cmd_scrap')."</span></a></div>",$output);
			}

			unset($oDocumentModel);
			unset($oDocument);
		} else {
			if($addon_info->text_position == 1) $output=preg_replace("/<\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<div class=\"soo_link_popup_menu\" style=\"text-align:".$addon_info->text_align.";\"><a href=\"#popup_menu_area\" class=\"".$button_class."\" onclick=\"return false\"><span class=\"SooLinkerAddon_$1\">".Context::getLang('cmd_scrap')."</span></a></div><!--BeforeDocument($1,$2)-->" , $output);
			else $output=preg_replace("/<\!--AfterDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<!--AfterDocument($1,$2)--><div class=\"soo_link_popup_menu\" style=\"text-align:".$addon_info->text_align.";\"><a href=\"#popup_menu_area\" class=\"".$button_class."\" onclick=\"return false\"><span class=\"SooLinkerAddon_$1\">".Context::getLang('cmd_scrap')."</span></a></div>", $output);
		}
	}

} elseif ($called_position == 'before_module_init' && Context::get('module') == 'SooLinkerAddon' && Context::get('act') == 'getSooLinkerAddonMenu' && Context::getResponseMethod() == 'XMLRPC') {
	$document_srl = intval(Context::get('target_srl'));
	$url = getFullUrl('','document_srl',$document_srl);
	$original_url = $url;

	// 트위터 버튼 API가 없는(?.. 작동이 잘 안되고 있는.) 모바일 환경에서만 쓰기.
	if($mobile_set == true) {
		if($addon_info->url_shorten != 1) {
			// 주소 줄이기 http://us7.kr 이용.
			$headers = array('User-Agent' => 'XpressEngine FileHandler (misol221@paran.com; http://www.imsoo.net/; twitter @misol221) SNS Bookmarker Addon by misol');
			$shorten_url = '';
			if($addon_info->url_shorten != 3) {
				$shorten_url = trim(FileHandler::getRemoteResource('http://api.us7.kr/?mode=shorten&link='.urlencode($url), null, 3, 'GET', 'application/xml', $headers));
			}
			if(substr($shorten_url, 0, 13) == 'http://us7.kr') $url = $shorten_url;
			elseif($addon_info->url_shorten != 4) {//tln.kr 이용.
				$shorten_url = trim(FileHandler::getRemoteResource('http://tln.kr/?mode=shorten&link='.urlencode($url), null, 3, 'GET', 'application/xml', $headers));
				if(substr($shorten_url, 0, 13) == 'http://tln.kr') $url = $shorten_url;
			}
		}
	}

	// 트위터 문자열 제한에 알맞게 문자를 자르기 위해 주소 길이 재기. 모바일 환경이 아니라면 t.co가 이용됨. 20자를 22자로 어림해도 큰 문제 없을듯.
	if($mobile_set == true) $url_len = strlen($url);
	else $url_len = 22;

	// document model 객체 생성 
	$oDocumentModel = &getModel('document');
	$oDocument = $oDocumentModel->getDocument($document_srl, false, false);

	if($oDocument->isExists()) {
		$title_str = $oDocument->getTitleText();
		if($addon_info->tag) $hash_len = strlen($addon_info->tag)+2;
		else $hash_len = 0;

		$title_str_len = strlen($title_str);
		$tag_list = $oDocument->get('tag_list');
		$tag_list = implode(', ',$tag_list);
		if($addon_info->tag) {
			if($tag_list) $tag_list .= ', '.$addon_info->tag;
			else $tag_list = $addon_info->tag;
		}
		$tag_list = urlencode($tag_list);

		$str_cut_allow = 134 - $url_len - $hash_len;
		$char_count = 0;

		$idx = 0;
		while($idx < $title_str_len && $char_count < $str_cut_allow) {
			$c = ord(substr($title_str, $idx,1));
			$char_count++;
			if($c<128) {
				$idx++;
			} else if (191<$c && $c < 224) {
				$idx += 2;
			} else {
				$idx += 3;
			}
		}
		$title_cut_str = trim(substr($title_str,0,$idx));
		if(strlen($title_cut_str) < $title_str_len) $title_cut_str .= '…';

		if($addon_info->tag) $twitter_title = $title_cut_str.' #'.$addon_info->tag;
		else $twitter_title = $title_cut_str;

		// 싸이월드 스크랩을 사용하는 경우에는 싸이월드쪽 크롤러가 읽을 수 있게 xml파일 생성.
		if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') {
			$cy_xml_path = FileHandler::getRealPath('./files/cache/addons/soo_mcrblog_link/'.md5($addon_info->cyworld_key).'/cyxml/document_srl/'.$document_srl.'.xml');
			if(file_exists($cy_xml_path)) $cy_xml_mtime = filemtime($cy_xml_path);
			else $cy_xml_mtime = 0;

			if($cy_xml_mtime < $oDocument->getUpdateTime()) {
				if(!$document_srl) return;
				// model 객체 생성 
				$oModuleModel = &getModel('module');

				$oModuleInfo = $oModuleModel->getModuleInfoByModuleSrl($oDocument->get('module_srl'));
				$oModuleGrant = $oModuleModel->getGrant($oModuleInfo);

				$title = '';
				$title = $oDocument->getTitleText();
				if(!$title) $title = 'Untitled';
				$thumbnail_img = '';
				// 익명 열람 권한 확인 (싸이월드 크롤러를 구분할 수는 없으므로 익명 권한 적용)
				if($oModuleGrant->access && $oModuleGrant->view) {
					if($oDocument->thumbnailExists()) {
						$thumbnail_img = $oDocument->getThumbnail();
					}
					$content = str_replace(array("\n","\n",'<![CDATA[',']]>'), array('','','',''), $oDocument->getTransContent(false, false, true, false));
				} else {
					$content = Context::getLang('msg_is_secret');
				}
				$lastupdate = $oDocument->getRegdate('YmdHis');
				if($lastupdate < $oDocument->getUpdate('YmdHis')) $lastupdate = $oDocument->getUpdate('YmdHis');
				$uri = $oDocument->getPermanentUrl();

				unset($oModuleInfo);
				unset($oModuleGrant);
				unset($oModuleModel);

				$print_xml = '';
				$print_xml .= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
				$print_xml .= '<PostInfo xmlns="urn:skcomms:prod" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:skcomms:prod http://api.cyworld.com/xml/openscrap/post/v1/post.xsd">'."\n";
				$print_xml .= sprintf("<SID>%s</SID>\n", $addon_info->cyworld_key);
				$print_xml .= "<Post>\n";
				$print_xml .= sprintf("<Subject><![CDATA[%s]]></Subject>\n", $title);
				if($thumbnail_img != '') {
					$print_xml .= sprintf("<OriginContentsText01><![CDATA[%s]]></OriginContentsText01>\n<OriginPhotoLink01>\n<Url><![CDATA[%s]]></Url>\n<Height>80</Height>\n<Width>80</Width>\n</OriginPhotoLink01>\n", $content, $thumbnail_img);
					$print_xml .= '<OriginOrder>OriginPhotoLink01|OriginContentsText01</OriginOrder>'."\n";
				} else {
					$print_xml .= sprintf("<OriginContentsText01><![CDATA[%s]]></OriginContentsText01>\n", $content);
					$print_xml .= '<OriginOrder>OriginContentsText01</OriginOrder>'."\n";
				}
				$print_xml .= sprintf("<Url><![CDATA[%s]]></Url>\n",$uri);
				$print_xml .= sprintf("<LastUpdateDate>%s</LastUpdateDate>\n",$lastupdate);
				$print_xml .= "</Post>\n</PostInfo>";

				FileHandler::writeFile('./files/cache/addons/soo_mcrblog_link/'.md5($addon_info->cyworld_key).'/cyxml/document_srl/'.$document_srl.'.xml', $print_xml);
			}

			$urlencoded_xml_url = '';
			if(substr($addon_info->cyworld_key_uri, -9) == 'index.php') $addon_info->cyworld_key_uri = str_replace('index.php', '', $addon_info->cyworld_key_uri);
			if(substr($addon_info->cyworld_key_uri, -1) != '/') $addon_info->cyworld_key_uri .= '/';
			if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') $urlencoded_xml_url = urlencode(sprintf("%sfiles/cache/addons/soo_mcrblog_link/%s/cyxml/document_srl/%s.xml",$addon_info->cyworld_key_uri,md5($addon_info->cyworld_key),$document_srl));
		}

		header("Content-Type: text/xml; charset=UTF-8");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		print("<response>\n<menus>\n");
		if($mobile_set == true) {
			printf("<item>\n<url><![CDATA[http://m.facebook.com/sharer.php?u=%s&t=%s]]></url>\n<str>Facebook</str>\n<icon>%saddons/soo_mcrblog_link/images/facebook.gif</icon>\n<target>default</target>\n</item>\n", urlencode($original_url), urlencode($title_str),Context::getRequestUri());
			printf("<item>\n<url><![CDATA[http://mobile.twitter.com/home/?status=%s]]></url>\n<str>Twitter</str>\n<icon>%saddons/soo_mcrblog_link/images/twitter.gif</icon>\n<target>default</target>\n</item>\n", urlencode($twitter_title.' '.$url),Context::getRequestUri());
			printf("<item>\n<url><![CDATA[http://m.me2day.net/p/posts/new?new_post[body]=%s&new_post[tags]=%s]]></url>\n<str>me2day</str>\n<icon>%saddons/soo_mcrblog_link/images/me2day.gif</icon>\n<target>default</target>\n</item>\n", urlencode('"'.str_replace('"','\\"',$title_cut_str).'":'.$original_url), $tag_list,Context::getRequestUri());
			printf("<item>\n<url><![CDATA[http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s]]></url>\n<str>Yozm</str>\n<icon>%saddons/soo_mcrblog_link/images/yozm.gif</icon>\n<target>default</target>\n</item>\n", urlencode($original_url), urlencode($title_cut_str),Context::getRequestUri());
		} else {
			printf("<item>\n<url><![CDATA[window.open('http://www.facebook.com/sharer.php?u=%s&t=%s', 'facebookscrap','width=770,height=450')]]></url>\n<alturl><![CDATA[http://www.facebook.com/sharer.php?u=%s&t=%s]]></alturl>\n<str>Facebook</str>\n<icon>%saddons/soo_mcrblog_link/images/facebook.gif</icon>\n<target>javascript</target>\n</item>\n", urlencode($original_url), urlencode($title_str),urlencode($original_url), urlencode($title_str),Context::getRequestUri());
			printf("<item>\n<url><![CDATA[window.open('http://twitter.com/share?text=%s&url=%s', 'twitterscrap','width=500,height=450')]]></url>\n<alturl><![CDATA[http://twitter.com/share?text=%s&url=%s]]></alturl>\n<str>Twitter</str>\n<icon>%saddons/soo_mcrblog_link/images/twitter.gif</icon>\n<target>javascript</target>\n</item>\n", urlencode($twitter_title), urlencode($original_url), urlencode($twitter_title), urlencode($original_url), Context::getRequestUri());
			printf("<item>\n<url><![CDATA[http://me2day.net/posts/new?new_post[body]=%s&new_post[tags]=%s]]></url>\n<str>me2day</str>\n<icon>%saddons/soo_mcrblog_link/images/me2day.gif</icon>\n<target>default</target>\n</item>\n", urlencode('"'.str_replace('"','\\"',$title_cut_str).'":'.$original_url), $tag_list,Context::getRequestUri());
			printf("<item>\n<url><![CDATA[window.open('http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s', 'yozmscrap','width=450,height=350')]]></url>\n<alturl><![CDATA[http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s]]></alturl>\n<str>Yozm</str>\n<icon>%saddons/soo_mcrblog_link/images/yozm.gif</icon>\n<target>javascript</target>\n</item>\n", urlencode($original_url), urlencode($title_cut_str), urlencode($original_url), urlencode($title_cut_str),Context::getRequestUri());
		}
		if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') {
			if($mobile_set == true) printf("<item>\n<url><![CDATA[http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s]]></url>\n<alturl><![CDATA[http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s]]></alturl>\n<str>Cyworld</str>\n<icon>%saddons/soo_mcrblog_link/images/cyworld.gif</icon>\n<target>default</target>\n</item>\n", $urlencoded_xml_url, $addon_info->cyworld_key,$urlencoded_xml_url, $addon_info->cyworld_key,Context::getRequestUri());
			else printf("<item>\n<url><![CDATA[window.open('http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s', 'cyopenscrap','width=450,height=410')]]></url>\n<alturl><![CDATA[http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s]]></alturl>\n<str>Cyworld</str>\n<icon>%saddons/soo_mcrblog_link/images/cyworld.gif</icon>\n<target>javascript</target>\n</item>\n", $urlencoded_xml_url, $addon_info->cyworld_key,$urlencoded_xml_url, $addon_info->cyworld_key,Context::getRequestUri());
		}
		print("</menus>\n<error>0</error>\n<message>success</message>\n</response>");
		Context::close();
		exit();
	}
}
?>