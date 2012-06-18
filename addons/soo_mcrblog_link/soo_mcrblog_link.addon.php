<?php
if(!defined('__XE__')) exit();
// file : soo_mcrblog_link.addon.php
// author : misol (misol@korea.ac.kr)
// license : Creative Commons License Attribution-ShareAlike 2.0 Korea (저작자표시-동일조건변경허락 2.0 대한민국) http://creativecommons.org/licenses/by-sa/2.0/kr/
// brief : 마이크로 블로그에 글의 링크를 퍼갈 수 있게 합니다.

if(Context::get('module')=='addon' || Context::get('module')=='admin') return;
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
	// 왼쪽 정렬이 기본값
	if(!$addon_info->text_align) $addon_info->text_align = 'left';

	Context::loadLang($addon_path.'lang');
	$sns_share = Context::getlang('sns_share');
	Context::set('facebook_share', sprintf($sns_share,Context::getlang('facebook')));
	Context::set('twitter_share', sprintf($sns_share,Context::getlang('twitter')));
	Context::set('me2day_share', sprintf($sns_share,Context::getlang('me2day')));
	Context::set('yozm_share', sprintf($sns_share,Context::getlang('yozm')));
	Context::set('clog_share', sprintf($sns_share,Context::getlang('clog')));

	// 스킨 설정이 되어 있으면 그 설정을 따름.
	if($addon_info->pc_skin) {
		if($mobile_set) $soo_linker_skin = $addon_info->mobile_skin;
		else $soo_linker_skin = $addon_info->pc_skin;
	} else $soo_linker_skin = 'bada';

	$oTemplate = new TemplateHandler();
	$template_btn_text = $oTemplate->compile('./addons/soo_mcrblog_link/skin/'.$soo_linker_skin, 'index');

	if($addon_info->ex_use != 2) {
		if($addon_info->ex_use == 1) { // 확장 ShopXE 등
			$document_srl = intval(Context::get('document_srl'));
			$oDocumentModel = &getModel('document');
			$oDocument = $oDocumentModel->getDocument(Context::get('document_srl'), false, false);

			if($oDocument->isExists()) {
				$document = $oDocument->getTransContent(false,false,false,false);
				$btn_text = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'document_srl\'',$document_srl),$template_btn_text);

				$btn_text = "<div style=\"text-align:".$addon_info->text_align."\">".$btn_text."</div>";

				if($addon_info->text_position == 1) $output = str_replace($document,$btn_text.$document ,$output);
				else $output = str_replace($document,$document.$btn_text,$output);
			}

			unset($oDocumentModel);
			unset($oDocument);
		} else {
			$btn_text = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'document_srl\'','$1'),$template_btn_text);
			$btn_text = "<div style=\"text-align:".$addon_info->text_align."\">".$btn_text."</div>";

			if($addon_info->text_position == 1) $output=preg_replace("/<\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)-->/i" , $btn_text."<!--BeforeDocument($1,$2)-->" , $output);
			else $output=preg_replace("/<\!--AfterDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<!--AfterDocument($1,$2)-->".$btn_text, $output);
		}
	}

	if($addon_info->trans != 1) {
		$btn_text = array();
		$btn_text[0] = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'mid\'','\''.str_replace('\'','\\\'',Context::get('mid')).'\''),$template_btn_text);
		$btn_text[1] = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'document_srl\'','\''.Context::get('document_srl').'\''),$template_btn_text);
		$btn_text[2] = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'curr_url\'','\'1\''),$template_btn_text);

		foreach($btn_text as $key=>$val) {
			$btn_text[$key] = "<div style=\"text-align:".$addon_info->text_align."\">".$val."</div>";
		}

		$output = str_replace(array('###__SNS_BOOKMARKER_BY_MID__###','###__SNS_BOOKMARKER_BY_DOCUMENT_SRL__###','###__SNS_BOOKMARKER_BY_URL__###'),$btn_text,$output);
	}

} elseif ($called_position == 'before_module_init' && Context::get('addon') == 'SooLinkerAddon' && Context::get('addonFunc') == 'getSooLinkerAddonUrls') {
	$document_srl = intval(Context::get('id_type'));
	if($document_srl > 0) $url = getFullUrl('','document_srl',$document_srl);
	elseif(Context::get('curr_url')) $url = Context::get('soo_url');
	else $url = getFullUrl('','mid',Context::get('mid'));

	// 트위터 문자열 제한에 알맞게 문자를 자르기 위해 주소 길이 재기.. t.co가 이용됨. 20자를 22자로 어림해도 큰 문제 없을듯.
	$url_len = 22;

	if($document_srl > 0) {
		// document model 객체 생성 
		$oDocumentModel = &getModel('document');
		$oDocument = $oDocumentModel->getDocument($document_srl, false, false);

		if($oDocument->isExists()) {
			$title_str = $oDocument->getTitleText();
			$tag_list = $oDocument->get('tag_list');
			$tag_list = implode(', ',$tag_list);
		}
	} else {
		$title_str = strip_tags(Context::get('doc_title'));
		$tag_list = '';
	}

	if($title_str) {
		if($addon_info->tag) $hash_len = strlen($addon_info->tag)+2;
		else $hash_len = 0;

		$title_str_len = strlen($title_str);
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


		header("Content-Type: text/html; charset=UTF-8");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		print("{\"urls\":[");
		if($mobile_set == true) {
			printf("\"https://m.facebook.com/sharer.php?u=%s&t=%s\",", urlencode($url), urlencode($title_str));
			printf("\"https://twitter.com/share?text=%s&url=%s\",", urlencode($twitter_title), urlencode($url));
			printf("\"https://me2day.net/p/posts/new?new_post[body]=%s&new_post[tags]=%s\",", urlencode('"'.str_replace('"','\\"',$title_cut_str).'":'.$url), $tag_list);
			printf("\"https://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s\",", urlencode($url), urlencode($title_cut_str));
			printf("\"http://csp.cyworld.com/bi/bi_recommend_pop.php?url=%s\"", urlencode($url));
		} else {
			printf("\"https://www.facebook.com/sharer.php?u=%s&t=%s\",", urlencode($url), urlencode($title_str));
			printf("\"https://twitter.com/share?text=%s&url=%s\",", urlencode($twitter_title), urlencode($url));
			printf("\"https://me2day.net/posts/new?new_post[body]=%s&new_post[tags]=%s\",", urlencode('"'.str_replace('"','\\"',$title_cut_str).'":'.$url), $tag_list);
			printf("\"https://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s\",", urlencode($url), urlencode($title_cut_str));
			printf("\"http://csp.cyworld.com/bi/bi_recommend_pop.php?url=%s\"", urlencode($url));
		}
		print("]}");
		Context::close();
		exit();
	}
}