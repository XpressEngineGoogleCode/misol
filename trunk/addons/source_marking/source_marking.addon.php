<?php
if(!defined("__ZBXE__")) exit();
/**
 * @file source_marking.addon.php
 * @brief 출처 표시
 * @copyright
	소스 표시기능 원본 자바스크립트 저작권 : nhn
	Source marking Original Javascript Copyright : nhn

	그 외 부분:
	이 저작물은 크리에이티브 커먼즈 저작자표시-동일조건변경허락 2.0 대한민국 라이선스에 따라 이용할 수 있습니다.
	이용허락조건을 보려면, http://creativecommons.org/licenses/by-sa/2.0/kr/ 을 클릭하거나,
	크리에이티브 커먼즈 코리아에 문의하세요.
	other part of this addon :
	This work is licensed under the Creative Commons Attribution-Share Alike 2.0 Korea License.
	To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/2.0/kr/
	or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
 **/
if(Context::getResponseMethod()!=='HTML' || Context::get('module')=='admin' || Context::get('act')=='dispAddonAdminSetup' || $called_position != 'before_display_content') return;

$link = Context::get('current_url');
$source_title=str_replace(array('"','\\') , array('&quot;','\\\\') , Context::getBrowserTitle());
$document_srl = Context::get('document_srl');
$oDocumentModel = &getModel('document');
$oDocument = $oDocumentModel->getDocument($document_srl, $this->grant->manager);
$nickname = str_replace(array('"','\\') , array('&quot;','\\\\') , $oDocument->getNickName());

if(!$addon_info->soo_copy_temp) {
	$addon_info->soo_copy_temp = "<cite>[Reference] : {nickname}, <a href=\'{link}\' target=\'_blank\' onclick=\'window.open(this.href);return false;\'>「{title}」 {link}</a>.</cite>";
}
$addon_info->soo_copy_temp = 'AutoSourcing.setTemplate("<!--autosourcing_code//-->'.str_replace(array('{link}','{title}','{nickname}'), array($link, $source_title,$nickname), $addon_info->soo_copy_temp).'<!--autosourcing_code_end//-->");';

$script_code = sprintf('<script type="text/javascript">'.
	'//<!--'."\n".
	"%s".
	'AutoSourcing.setString(1 ,"%s", "%s", "%s");'."\n".
	'//-->'."\n".
	'</script>'
	, $addon_info->soo_copy_temp, $source_title, $link, $link);
Context::addJsFile('./addons/source_marking/autosourcing_by_nhn/autosourcing.open.compact.js');
Context::addJsFile('./addons/source_marking/js/ctlr.js');
Context::addHtmlHeader($script_code);
if(stristr($_SERVER['HTTP_USER_AGENT'],'firefox')){
	Context::addCssFile('./addons/source_marking/css/css.css');
}

$output = str_replace('<!--BeforeDocument(', '<div id="article_1"><!--BeforeDocument(', $output);
$output = str_replace('<!--AfterDocument(', '</div><!--AfterDocument(', $output);
$output=str_replace('autosourcing-stub','autosourcingStub',$output);
?>