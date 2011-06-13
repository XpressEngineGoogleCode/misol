<?php
if(!defined("__ZBXE__")) exit();
//file meta_add.addon.php
//brief 기본적인 metatag 입력과 파비콘 경로 입력.

// called_position이 before_module_proc일때만 실행, 관리자모드에서 작동 안하기
if($called_position != 'before_module_proc' || Context::get('module')=='admin' || Context::getResponseMethod() != 'HTML') return;

unset($value);
unset($tag_list);
unset($nickname);
unset($contexttext);
unset($meta_add);
unset($thumbnail);
unset($url);

$document_srl = Context::get('document_srl');

if($document_srl) {
	$oDocumentModel = &getModel('document');
	$oDocument = $oDocumentModel->getDocument($document_srl, $this->grant->manager);
	$contexttext = $oDocument->getSummary(200);
	$contexttext = str_replace(array("\r","\n","\t"),array('','',''),$contexttext);

	$tag_list = $oDocument->get('tag_list');
	$nickname = $oDocument->getNickName();
	$thumbnail = $oDocument->getThumbnail(300,300,'');
	$url = $oDocument->getPermanentUrl();
}

// 사이트 srl이 다르면 index 강제로 제거.
$oModuleModel = &getModel('module');

$site_module_info = Context::get('site_module_info');
$module_info = Context::get('current_module_info');
$site_info = $oModuleModel->getSiteInfo($module_info->site_srl);

if($module_info && $module_info->site_srl != $site_module_info->site_srl) {
	if($module_info->site_srl) {
		$site_info = $oModuleModel->getSiteInfo($module_info->site_srl);
		$redirect_url = getNotEncodedSiteUrl($site_info->domain, 'mid',Context::get('mid'),'document_srl',Context::get('document_srl'),'module_srl',Context::get('module_srl'),'entry',Context::get('entry'));
	} else {
		$db_info = Context::getDBInfo();
		if(!$db_info->default_url) return Context::getLang('msg_default_url_is_not_defined');
		else $redirect_url = getNotEncodedSiteUrl($db_info->default_url, 'mid',Context::get('mid'),'document_srl',Context::get('document_srl'),'module_srl',Context::get('module_srl'),'entry',Context::get('entry'));
	}
	header('HTTP/1.1 301 Moved Permanently'); 
	header('Location:'.$redirect_url);

	$addon_info->meta_robot_index = 2;
	unset($url);
}

unset($meta_keyword);
if($tag_list) {
	foreach($tag_list as $value) {
		if($meta_keyword=='') {
			$meta_keyword = $value;
		} else {
			$meta_keyword = $meta_keyword.','.$value;
		}
	}
	$meta_add .= sprintf('<meta name="keywords" content="%s" />'."\n",$meta_keyword);
} elseif($addon_info->meta_keyword != '') {
	$meta_add .= sprintf('<meta name="keywords" content="%s" />'."\n",$addon_info->meta_keyword);
}

unset($meta_decription);
if($contexttext || $document_srl) {
	$meta_add .= sprintf('<meta name="description" content="%s" />'."\n",$contexttext);
} elseif($module_info->description) {
	$meta_add .= sprintf('<meta name="description" content="%s" />'."\n",$module_info->description);
} elseif($addon_info->meta_decription) {
	$meta_add .= sprintf('<meta name="description" content="%s" />'."\n",$addon_info->meta_decription);
}

unset($meta_author);
if($nickname) {
	$meta_add .= sprintf('<meta name="author" content="%s" />'."\n",$nickname);
}
if($addon_info->meta_author) {
	$meta_add .= sprintf('<meta name="author" content="%s" />'."\n",$addon_info->meta_author);
}

unset($meta_icon);
if($addon_info->meta_icon!='') {
	$meta_add .= sprintf('<link rel="shortcut icon" href="%s" />'."\n",$addon_info->meta_icon);
}

unset($meta_robot_index);
unset($meta_robot_follow);
if($addon_info->meta_robot_index=='1') {
	$meta_robot_index='index';
} elseif($addon_info->meta_robot_index=='2') {
	$meta_robot_index='noindex';
}

if($addon_info->meta_robot_follow=='1') {
	$meta_robot_follow='follow';
} elseif($addon_info->meta_robot_follow=='2') {
	$meta_robot_follow='nofollow';
}

if($meta_robot_index!='' && $meta_robot_follow!='') {
	$meta_add .= sprintf('<meta name="robots" content="%s,%s" />'."\n",$meta_robot_index,$meta_robot_follow);
}

if($thumbnail) $meta_add .= sprintf('<link rel="image_src" href="%s" />'."\n",$thumbnail);

if($url) $meta_add .= sprintf('<link rel="canonical" href="%s" />'."\n",$url);

Context::addHtmlHeader($meta_add);

unset($meta_add);
unset($value);
unset($tag_list);
unset($nickname);
unset($contexttext);
unset($meta_add);
unset($thumbnail);
unset($url);
?>