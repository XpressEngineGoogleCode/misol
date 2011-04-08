<?php
if(!defined("__ZBXE__")) exit();
// file : soo_facebook_twitter.addon.php
// author : misol (misol@korea.ac.kr)
// license : Creative Commons License Attribution-ShareAlike 2.0 Korea (������ǥ��-�������Ǻ������ 2.0 ���ѹα�) http://creativecommons.org/licenses/by-sa/2.0/kr/
// brief : ����ũ�� ��α׿� ���� ��ũ�� �۰� �� �ְ� �մϴ�.

if(Context::get('module')=='addon') return;

// called position�� �ֵ���� �����ϴ� �ڵ尡 ���� ������- ���⼭ ��
if($called_position != 'before_display_content' && $called_position != 'before_module_init') return;
// �κ����Դ� ������ ����.
if(function_exists('isCrawler')) if(isCrawler()) return;

// ����Ʈ�� �������� Ȯ���ϴ� �κ�. XE ��� �������� �������� �ʴ� ����̹Ƿ� �ش� ����� �ִ��� ���� Ȯ��. Mobile �� �� �� �ֽ� ���������� �� ���������� �ֵ���� ���������� smartphoneXE�� ����.
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
	// ���� ������ �⺻��
	if(!$addon_info->text_align) $addon_info->text_align = 'left';

	Context::loadLang($addon_path.'lang');
	$sns_share = Context::getlang('sns_share');
	Context::set('facebook_share', sprintf($sns_share,Context::getlang('facebook')));
	Context::set('twitter_share', sprintf($sns_share,Context::getlang('twitter')));

	Context::addJsFile('./common/js/jquery.js', false, '',-100000000);
	Context::addJsFile('./common/js/common.js', false, '',-10000000);
	Context::addJsFile('./common/js/xml_handler.js', false, '',-1000000);

	// ��Ų ������ �Ǿ� ������ �� ������ ����.
	if($addon_info->pc_skin) {
		if($mobile_set) $soo_linker_lite_skin = $addon_info->mobile_skin;
		else $soo_linker_lite_skin = $addon_info->pc_skin;
	} else $soo_linker_lite_skin = 'default';

	$oTemplate = new TemplateHandler();
	$template_btn_text = $oTemplate->compile('./addons/soo_facebook_twitter/skin/'.$soo_linker_lite_skin, 'index');

	if($addon_info->ex_use != 2) {
		if($addon_info->ex_use == 1) { // Ȯ�� ShopXE ��
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

	$btn_text = array();
	$btn_text[0] = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'mid\'','\''.str_replace('\'','\\\'',Context::get('mid')).'\''),$template_btn_text);
	$btn_text[1] = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'document_srl\'','\''.Context::get('document_srl').'\''),$template_btn_text);
	$btn_text[2] = str_replace(array('##__CONTENT_ID_TYPE__##','##__CONTENT_ID__##'),array('\'curr_url\'','\'1\''),$template_btn_text);

	foreach($btn_text as $key=>$val) {
		$btn_text[$key] = "<div style=\"text-align:".$addon_info->text_align."\">".$val."</div>";
	}

	$output = str_replace(array('###__SNS_BOOKMARKER_BY_MID__###','###__SNS_BOOKMARKER_BY_DOCUMENT_SRL__###','###__SNS_BOOKMARKER_BY_URL__###'),$btn_text,$output);

} elseif ($called_position == 'before_module_init' && Context::get('module') == 'SooLinkerAddon' && Context::get('act') == 'getSooLinkerAddonUrls' && Context::getResponseMethod() == 'XMLRPC') {
	$document_srl = intval(Context::get('document_srl'));
	if($document_srl > 0) $url = getFullUrl('','document_srl',$document_srl);
	elseif(Context::get('curr_url')) $url = Context::get('soo_url');
	else $url = getFullUrl('','mid',Context::get('mid'));
	$original_url = $url;

	// us7.kr�� ���� ������, �ּҸ� ���� �ҹ��ڷ� �ٲ������(�ڸ�Ʈ�� ��ħ��) tln.kr�� ���.
	if($mobile_set == true || Context::get('curr_url')) {
		$shorten_url = trim(FileHandler::getRemoteResource('http://tln.kr/?mode=shorten&link='.urlencode($url), null, 3, 'GET', 'application/xml', $headers));
		if(substr($shorten_url, 0, 13) == 'http://tln.kr') $url = $shorten_url;
	}

	// Ʈ���� ���ڿ� ���ѿ� �˸°� ���ڸ� �ڸ��� ���� �ּ� ���� ���. ����� ȯ���� �ƴ϶�� t.co�� �̿��. 20�ڸ� 22�ڷ� ��ص� ū ���� ������.
	if($mobile_set == true) $url_len = strlen($url);
	else $url_len = 22;

	if($document_srl > 0) {
		// document model ��ü ���� 
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
		if(strlen($title_cut_str) < $title_str_len) $title_cut_str .= '��';

		if($addon_info->tag) $twitter_title = $title_cut_str.' #'.$addon_info->tag;
		else $twitter_title = $title_cut_str;


		header("Content-Type: text/xml; charset=UTF-8");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		print("<response>\r\n<urls>");
		if($mobile_set == true) {
			printf("<url><![CDATA[http://m.facebook.com/sharer.php?u=%s&t=%s]]></url>", urlencode($original_url), urlencode($title_str));
			printf("<url><![CDATA[http://mobile.twitter.com/home/?status=%s]]></url>", urlencode($twitter_title.' '.$url));
		} else {
			printf("<url><![CDATA[http://www.facebook.com/sharer.php?u=%s&t=%s]]></url>", urlencode($original_url), urlencode($title_str));
			printf("<url><![CDATA[http://twitter.com/share?text=%s&url=%s]]></url>", urlencode($twitter_title), urlencode($url));
		}
		print("</urls>\r\n<error>0</error>\r\n<message>success</message>\r\n</response>");
		Context::close();
		exit();
	}
}
?>
