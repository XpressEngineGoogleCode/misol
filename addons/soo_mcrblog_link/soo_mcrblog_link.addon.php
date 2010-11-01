<?php
if(!defined("__ZBXE__")) exit();
// file : soo_mcrblog_link.addon.php
// author : misol (misol@korea.ac.kr)
// license : Creative Commons License Attribution-ShareAlike 2.0 Korea (저작자표시-동일조건변경허락 2.0 대한민국) http://creativecommons.org/licenses/by-sa/2.0/kr/
// brief : 마이크로 블로그에 글의 링크를 퍼갈 수 있게 합니다.
// 로봇에게는 보이지 않음.
if(isCrawler()) return;
if($called_position == 'before_display_content' && Context::getResponseMethod() == 'HTML') {
	$button_class = '';
	$mobile_set = false;
	if(class_exists('Mobile')) {
		if(Mobile::isFromMobilePhone()) {
			$mobile_set = true;
			if(isset($addon_info->mobile_button_type) && $addon_info->mobile_button_type != 'none') $addon_info->button_type = $addon_info->mobile_button_type;
		}
	}

	if(!isset($addon_info->button_type) || (is_numeric($addon_info->button_type) && intval($addon_info->button_type) == 0)) Context::addCssFile('./addons/soo_mcrblog_link/css/style.css');
	elseif($addon_info->button_type =='oneBtn') Context::addCssFile('./addons/soo_mcrblog_link/css/oneBtn.css');
	elseif($addon_info->button_type =='oneBtn_mini') Context::addCssFile('./addons/soo_mcrblog_link/css/oneBtn_mini.css');
	elseif($addon_info->button_type =='oneBtn_mobile') Context::addCssFile('./addons/soo_mcrblog_link/css/oneBtn_mobile.css');
	else $button_class = 'button';

	// 오른쪽 정렬이 기본값
	if(!$addon_info->text_align) $addon_info->text_align = 'right';

	// 드롭다운 없는 스크랩 버튼
	if($addon_info->button_type =='oneBtn' || $addon_info->button_type =='oneBtn_mini' || $addon_info->button_type =='oneBtn_mobile') {
		Context::addJsFile('./addons/soo_mcrblog_link/js/oneBtn.js');
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
				if($mobile_set == true) $btn_text .= "<a href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(0,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(1,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(2,".$document_srl.");return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(3,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
				else  $btn_text .= "<a href=\"#\" onclick=\"sooscrap = window.open('','sooLinker','width=770,height=450');soo_Linker(0,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(1,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(2,".$document_srl.");return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a href=\"#\" onclick=\"sooscrap = window.open('','yozmscrap','width=450,height=350');soo_Linker(3,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
				if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') {
					$width = $width + 24;
					if($addon_info->button_type =='oneBtn_mobile') $width = $width + 11;
					if($mobile_set == true) $btn_text .= "<a href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(4,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
					else $btn_text .= "<a href=\"#\" onclick=\"sooscrap = window.open('','cyopenscrap','width=450,height=410');soo_Linker(4,".$document_srl.");return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
				}
				if($addon_info->text_position == 1) $output = str_replace($document,"<div style=\"text-align:".$addon_info->text_align."\"><div style=\"width:".$width."px;\" class=\"soo_link_popup_menu\"><span class=\"SooLinkerAddon Scrap\">".Context::getLang('cmd_scrap')."</span>".$btn_text."</div></div>".$document ,$output);
				else $output = str_replace($document,$document."<div style=\"text-align:".$addon_info->text_align."\"><div style=\"width:".$width."px;\" class=\"soo_link_popup_menu\"><span class=\"SooLinkerAddon Scrap\">".Context::getLang('cmd_scrap')."</span>".$btn_text."</div></div>",$output);
			}

			unset($oDocumentModel);
			unset($oDocument);
		} else {
			$btn_text = '';
			if($mobile_set == true) $btn_text .= "<a title=\"Facebook\" href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(0,$1);return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a title=\"Twitter\" href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(1,$1);return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a title=\"me2day\" href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(2,$1);return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a title=\"Yozm\" href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(3,$1);return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
			else $btn_text .= "<a title=\"Facebook\" href=\"#\" onclick=\"sooscrap = window.open('','sooLinker','width=770,height=450');soo_Linker(0,$1);return false;\"><span class=\"SooLinkerAddon Facebook\">Facebook</span></a><a title=\"Twitter\" href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(1,$1);return false;\"><span class=\"SooLinkerAddon Twitter\">Twitter</span></a><a title=\"me2day\" href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(2,$1);return false;\"><span class=\"SooLinkerAddon me2day\">me2day</span></a><a title=\"Yozm\" href=\"#\" onclick=\"sooscrap = window.open('','yozmscrap','width=450,height=350');soo_Linker(3,$1);return false;\"><span class=\"SooLinkerAddon Yozm\">Yozm</span></a>";
			if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') {
				$width = $width + 24;
				if($addon_info->button_type =='oneBtn_mobile') $width = $width + 11;
				if($mobile_set == true) $btn_text .= "<a title=\"Cyworld\" href=\"#\" onclick=\"sooscrap = window.open('');soo_Linker(4,$1);return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
				else $btn_text .= "<a title=\"Cyworld\" href=\"#\" onclick=\"sooscrap = window.open('','cyopenscrap','width=450,height=410');soo_Linker(4,$1);return false;\"><span class=\"SooLinkerAddon Cyworld\">Cyworld</span></a>";
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

} elseif (Context::get('module') == 'SooLinkerAddon' && Context::get('act') == 'getSooLinkerAddonMenu') {
	$document_srl = intval(Context::get('target_srl'));
	$url = getFullUrl('','document_srl',$document_srl);

	$mobile_set = false;
	if(class_exists('Mobile')) {
		if(Mobile::isFromMobilePhone()) {
			$mobile_set = true;
			if(isset($addon_info->mobile_button_type) && $addon_info->mobile_button_type != 'none') $addon_info->button_type = $addon_info->mobile_button_type;
		}
	}

	if($addon_info->url_shorten != 1) {
		// 주소 줄이기 http://tln.kr 이용.
		$headers = array('User-Agent' => 'XpressEngine MicroBlogLinker Addon by misol (misol@korea.ac.kr; http://www.imsoo.net/; twitter @misol221)');
		$shorten_url = trim(FileHandler::getRemoteResource('http://tln.kr/?mode=shorten&link='.urlencode($url), null, 3, 'GET', 'application/xml', $headers));
		if(substr($shorten_url, 0, 13) == 'http://tln.kr') $url = $shorten_url;
	}

	$url_len = strlen($url);
	$urlencode_url = urlencode($url);

	// document model 객체 생성 
	$oDocumentModel = &getModel('document');
	$oDocument = $oDocumentModel->getDocument($document_srl, false, false);

	if($oDocument->isExists()) {
		$title_str = $oDocument->getTitleText();
		$title_str_len = strlen($title_str);
		$tag_list = $oDocument->get('tag_list');
		$tag_list = implode(', ',$tag_list);
		$tag_list = urlencode($tag_list);

		$str_cut_allow = 134 - $url_len;
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
		$title_cut_str = substr($title_str,0,$idx);
		if(strlen($title_cut_str) < $title_str_len) $title_cut_str .= '...';

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
					$content = str_replace(array("\r\n","\n",'<![CDATA[',']]>'), array('','','',''), $oDocument->getTransContent(false, false, true, false));
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
		print("<response>\r\n<menus>\r\n");
		if($mobile_set == true) printf("<item>\r\n<url><![CDATA[http://www.facebook.com/sharer.php?u=%s&t=%s]]></url>\r\n<alturl><![CDATA[http://www.facebook.com/sharer.php?u=%s&t=%s]]></alturl>\r\n<str>Facebook</str>\r\n<icon>%saddons/soo_mcrblog_link/images/facebook.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", $urlencode_url, urlencode($title_str),$urlencode_url, urlencode($title_str),Context::getRequestUri());
		else printf("<item>\r\n<url><![CDATA[window.open('http://www.facebook.com/sharer.php?u=%s&t=%s', 'facebookscrap','width=770,height=450')]]></url>\r\n<alturl><![CDATA[http://www.facebook.com/sharer.php?u=%s&t=%s]]></alturl>\r\n<str>Facebook</str>\r\n<icon>%saddons/soo_mcrblog_link/images/facebook.gif</icon>\r\n<target>javascript</target>\r\n</item>\r\n", $urlencode_url, urlencode($title_str),$urlencode_url, urlencode($title_str),Context::getRequestUri());
		printf("<item>\r\n<url><![CDATA[http://twitter.com/home/?status=%s]]></url>\r\n<str>Twitter</str>\r\n<icon>%saddons/soo_mcrblog_link/images/twitter.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", urlencode($title_cut_str.' '.$url),Context::getRequestUri());
		if($mobile_set == true) printf("<item>\r\n<url><![CDATA[http://m.me2day.net/p/posts/new?new_post[body]=%s&new_post[tags]=%s]]></url>\r\n<str>me2day</str>\r\n<icon>%saddons/soo_mcrblog_link/images/me2day.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", urlencode('"'.str_replace('"','\\"',$title_cut_str).'":'.$url), $tag_list,Context::getRequestUri());
		else printf("<item>\r\n<url><![CDATA[http://me2day.net/posts/new?new_post[body]=%s&new_post[tags]=%s]]></url>\r\n<str>me2day</str>\r\n<icon>%saddons/soo_mcrblog_link/images/me2day.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", urlencode('"'.str_replace('"','\\"',$title_cut_str).'":'.$url), $tag_list,Context::getRequestUri());
		if($mobile_set == true) printf("<item>\r\n<url><![CDATA[http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s]]></url>\r\n<alturl><![CDATA[http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s]]></alturl>\r\n<str>Yozm</str>\r\n<icon>%saddons/soo_mcrblog_link/images/yozm.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", $urlencode_url, urlencode($title_cut_str.' '.$url), $urlencode_url, urlencode($title_cut_str.' '.$url),Context::getRequestUri());
		else printf("<item>\r\n<url><![CDATA[window.open('http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s', 'yozmscrap','width=450,height=350')]]></url>\r\n<alturl><![CDATA[http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s]]></alturl>\r\n<str>Yozm</str>\r\n<icon>%saddons/soo_mcrblog_link/images/yozm.gif</icon>\r\n<target>javascript</target>\r\n</item>\r\n", $urlencode_url, urlencode($title_cut_str.' '.$url), $urlencode_url, urlencode($title_cut_str.' '.$url),Context::getRequestUri());
		if($addon_info->cyworld_key_uri != '' && $addon_info->cyworld_key != '') {
			if($mobile_set == true) printf("<item>\r\n<url><![CDATA[http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s]]></url>\r\n<alturl><![CDATA[http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s]]></alturl>\r\n<str>Cyworld</str>\r\n<icon>%saddons/soo_mcrblog_link/images/cyworld.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", $urlencoded_xml_url, $addon_info->cyworld_key,$urlencoded_xml_url, $addon_info->cyworld_key,Context::getRequestUri());
			else printf("<item>\r\n<url><![CDATA[window.open('http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s', 'cyopenscrap','width=450,height=410')]]></url>\r\n<alturl><![CDATA[http://api.cyworld.com/openscrap/post/v1/?xu=%s&sid=%s]]></alturl>\r\n<str>Cyworld</str>\r\n<icon>%saddons/soo_mcrblog_link/images/cyworld.gif</icon>\r\n<target>javascript</target>\r\n</item>\r\n", $urlencoded_xml_url, $addon_info->cyworld_key,$urlencoded_xml_url, $addon_info->cyworld_key,Context::getRequestUri());
		}
		print("</menus>\r\n<error>0</error>\r\n<message>success</message>\r\n</response>");
		Context::close();
		exit();
	}

}
?>