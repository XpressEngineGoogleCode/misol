<?php
if(!defined("__ZBXE__")) exit();
/**
 * @file source_marking.addon.php
 * @brief 출처 표시
 * @copyright
   소스 표시기능 원본 자바스크립트 저작권 : nhn
   Source marking Original Javascript Copyright : nhn

   그 외 부분, 일부 수정 :
   이 저작물은 크리에이티브 커먼즈 저작자표시-동일조건변경허락 2.0 대한민국 라이선스에 따라 이용할 수 있습니다.
   이용허락조건을 보려면, http://creativecommons.org/licenses/by-sa/2.0/kr/ 을 클릭하거나,
   크리에이티브 커먼즈 코리아에 문의하세요.
   other part of this addon :
   This work is licensed under the Creative Commons Attribution-Share Alike 2.0 Korea License.
   To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/2.0/kr/
   or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
 **/
// 출력결과가 HTML 이 아니라면 동작하지 않도록 합니다.
if(Context::getResponseMethod()!=='HTML') return;
// called_position이 after_module_proc일때만 실행, 관리자모드에서 작동 안하기
if($called_position == 'after_module_proc' && Context::get('module')!='admin' && Context::get('act')!='dispAddonAdminSetup') {
  //getUrl 함수에서 호스트를 포함할 때도 있고 아닐때도 있어 보정함.
  $link = getUrl('mid', Context::get('mid'), 'vid', Context::get('vid'), 'document_srl', Context::get('document_srl'), 'act', Context::get('act'), 'rnd','');
  if(!strpos($link,'://')) {
    if($_SERVER['HTTPS']=='on') $link = 'https://'.$_SERVER['HTTP_HOST'].$link;
    else $link = 'http://'.$_SERVER['HTTP_HOST'].$link;
  }

  $imsoo_source_title=str_replace(array('"','\\') , array('&quot;','\\\\') , Context::getBrowserTitle());
  if($addon_info->soo_copy_temp) {
    $addon_info->soo_copy_temp = 'AutoSourcing.setTemplate("<!--autosourcing_code//--><div><cite>'.str_replace(array('{link}','{title}'), array($link, $imsoo_source_title), $addon_info->soo_copy_temp).'</cite></div><!--autosourcing_code_end//-->");';
  }
  else {
    $document_srl = Context::get('document_srl');
    $oDocumentModel = &getModel('document');
    $oDocument = $oDocumentModel->getDocument($document_srl, $this->grant->manager);
    $nickname = $oDocument->getNickName();

    $addon_info->soo_copy_temp = sprintf($addon_info->soo_copy_temp = 'AutoSourcing.setTemplate("<!--autosourcing_code//--><div><cite>출처(ref.) : <a href=\'%s\' target=\'_blank\' onclick=\'window.open(this.href);return false;\'>%s - %s</a>'
    , $link, $imsoo_source_title, $link);
    if($nickname!='') {
      $addon_info->soo_copy_temp .= '<br />by '.$nickname;
    }
    $addon_info->soo_copy_temp .= '</cite></div><!--autosourcing_code_end//-->");'."\n";
  }
  Context::addJsFile('./addons/source_marking/autosourcing_by_nhn/autosourcing.open.compact.js');
  $script_code = sprintf('<script type="text/javascript">//<![CDATA['."\n".
    '<!--'."\n".
    "%s".
    "AutoSourcing.setString(1 ,\"%s\", \"%s\", \"%s\");"."\n".
    '//-->'."\n".'//]]>'."\n".
    '</script>'."\n".
    '<style type="text/css">'."\n".
    'div.autosourcing-stub { display:none }'."\n".
    '</style>'."\n"
    , $addon_info->soo_copy_temp, $imsoo_source_title, $link, $link);
  Context::addHtmlHeader($script_code);
  Context::addBodyHeader('<div id="article_1">');
  Context::addHtmlFooter('</div>'."\n".'<script type="text/javascript"><!--'."\n".'AutoSourcing.init("article_%id%", true);'."\n".'//--></script>');
}
if($called_position == 'before_display_content' && Context::get('module')!='admin') {
    $output=preg_replace("/<div([^>]*)autosourcing-stub([^>]*)>/i" , "<div\\1quotation_mark_layer\\2>" , $output);
}
?>
