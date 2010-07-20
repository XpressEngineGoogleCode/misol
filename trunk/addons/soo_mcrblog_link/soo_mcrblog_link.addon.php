<?php
if(!defined("__ZBXE__")) exit();
// file soo_mcrblog_link.addon.php
// author misol (misol@korea.ac.kr)
// brief 마이크로 블로그에 글의 링크를 퍼갈 수 있게 합니다.
// 로봇에게는 보이지 않음.
if(isCrawler()) return;
if($called_position == 'before_display_content' && Context::getResponseMethod() == 'HTML') {
  $button_class = '';

  if(!isset($addon_info->button_type) || (is_numeric($addon_info->button_type) && intval($addon_info->button_type) == 0)) Context::addCssFile('./addons/soo_mcrblog_link/css/style.css');
  else $button_class = 'button';

  if($button_class == 'button' && $addon_info->button_type != 1) $button_class .= ' '.$addon_info->button_type;

  if($addon_info->text_position == 1) $output=preg_replace("/<\!--BeforeDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<div class=\"soo_link_popup_menu\"><a href=\"#popup_menu_area\" class=\"".$button_class."\" style=\"float:right;\" onclick=\"return false\"><span class=\"SooLinkerAddon_$1\">".Context::getLang('cmd_scrap')."</span></a></div><!--BeforeDocument($1,$2)-->" , $output);
  else $output=preg_replace("/<\!--AfterDocument\(([0-9]*),([0-9\-]*)\)-->/i" , "<!--AfterDocument($1,$2)--><div class=\"soo_link_popup_menu\"><a href=\"#popup_menu_area\" class=\"".$button_class."\" style=\"float:right;\" onclick=\"return false\"><span class=\"SooLinkerAddon_$1\">".Context::getLang('cmd_scrap')."</span></a></div>", $output);

} elseif (Context::get('module') == 'SooLinkerAddon' && Context::get('act') == 'getSooLinkerAddonMenu') {
  $document_srl = intval(Context::get('target_srl'));
  $url = getFullUrl('','document_srl',$document_srl);
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

    header("Content-Type: text/xml; charset=UTF-8");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    print("<response>\r\n<menus>\r\n");
    printf("<item>\r\n<url><![CDATA[http://www.facebook.com/sharer.php?u=%s&t=%s]]></url>\r\n<str>Facebook</str>\r\n<icon>%saddons/soo_mcrblog_link/images/facebook.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", $urlencode_url, urlencode($title_str),Context::getRequestUri());
    printf("<item>\r\n<url><![CDATA[http://twitter.com/home/?status=%s]]></url>\r\n<str>Twitter</str>\r\n<icon>%saddons/soo_mcrblog_link/images/twitter.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", urlencode($title_cut_str.' '.$url),Context::getRequestUri());
    printf("<item>\r\n<url><![CDATA[http://me2day.net/posts/new?new_post[body]=%s&new_post[tags]=%s]]></url>\r\n<str>me2day</str>\r\n<icon>%saddons/soo_mcrblog_link/images/me2day.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", urlencode('"'.str_replace('"','\\"',$title_cut_str).'":'.$url), $tag_list,Context::getRequestUri());
    printf("<item>\r\n<url><![CDATA[http://yozm.daum.net/api/popup/prePost?sourceid=0&link=%s&prefix=%s]]></url>\r\n<str>Yozm</str>\r\n<icon>%saddons/soo_mcrblog_link/images/yozm.gif</icon>\r\n<target>default</target>\r\n</item>\r\n", $urlencode_url, urlencode($title_cut_str.' '.$url),Context::getRequestUri());
    print("</menus>\r\n<error>0</error>\r\n<message>success</message>\r\n</response>");

    Context::close();
    exit();
  }

}
?>