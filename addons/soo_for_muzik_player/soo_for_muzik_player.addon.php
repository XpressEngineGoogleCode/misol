<?php
if(!defined("__ZBXE__")) exit();
// file soo_for_muzik_player.addon.php
// author misol (misol@korea.ac.kr)
// brief 브라우저로 접속시 프레임을 나눕니다.
// ⓒ 2010 김민수.
if($called_position == 'before_module_init') {
  if(Context::get('outframe')) {
    $_SESSION['soo_for_muzik_player']['outframe'] = false;
    FileHandler::writeFile('./files/cache/addons/soo_for_muzik_player/ip/ip_'.urlencode($_SERVER['REMOTE_ADDR']).'.txt',1);
  }
}
if(Context::get('fr')) return;
// 프레임을 사용하지 않도록 세션이 정의되어 있으면 작동하지 않음.
if($_SESSION['soo_for_muzik_player']['outframe'] === false) return;
// 로봇일 경우 동작 안함.
if(function_exists('isCrawler')) {
  if(isCrawler()) return;
}

if(Context::getResponseMethod()=='XMLRPC' && Context::get('soo_frame') == 'addon' && Context::get('act') == 'soo_for_muzik_player') {
  if(Context::get('do') == 'doFrameSessionStart') {
    $_SESSION['soo_for_muzik_player']['doFrameSessionStart'] = true;
    return;
  }
  if(Context::get('do') == 'doFrameSessionChecker') {
    header("Content-Type: text/xml; charset=UTF-8");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    if($_SESSION['soo_for_muzik_player']['doFrameSessionStart']) {
      $xmlDoc  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<response>\n<error>0</error>\n<message>FrameSessionStartSuccess</message>\n</response>";
      echo $xmlDoc;
      exit();
    }
    else {
      $xmlDoc  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<response>\n<error>0</error>\n<message>FrameSessionStartFail</message>\n</response>";
      echo $xmlDoc;
      exit();
    }
  }
}

if(Context::getResponseMethod()!='HTML') return;
//POST 내용 있으면 동작 안함.
if(count($_POST)) return;
// 특정 액션에서 동작 안함
if(in_array(Context::get('module'), array('admin'))) return;
if(class_exists('smartphoneXE')) {
  if(smartphoneXE::isFromSmartPhone()) return;
}
// 프레임 탈출 코드.
if($called_position == 'before_module_init') {
  //세션이 동작하지 않는 User-Agent를 위한 처리
  $check = FileHandler::readFile('./files/cache/addons/soo_for_muzik_player/ip/ip_'.urlencode($_SERVER['REMOTE_ADDR']).'.txt');
  if($check) {
    // 세션을 전역 변수처럼 사용. (애드온 사용 순서가 순차적으로 적용되니까.)
    $_SESSION['soo_for_muzik_player']['outframe'] = false;
    return;
  }
}
if($called_position == 'after_module_proc') {

  if($_SESSION['soo_for_muzik_player']['doFrameSessionStart']) {
    $uri = Context::getRequestUri();
    if(count($_GET)) {
      $uri = Context::getRequestUrl();
    }
    if(Context::getRequestUri() != $uri) {
      $exit_uri = $uri.'&outframe=true';
      $uri .= '&fr=true';
    }
    else {
      $exit_uri = $uri.'?outframe=true';
      $uri .= '?fr=true';
    }
    $exit_uri = htmlspecialchars($exit_uri);
    $uri = htmlspecialchars($uri);

    unset($_SESSION['soo_for_muzik_player']['doFrameSessionStart']);

    if(!isset($addon_info->frame_size)) $addon_info->frame_size = 20;
    if(!isset($addon_info->frame_border)) $addon_info->frame_border = 0;
    if(!isset($addon_info->frame_resize)) $addon_info->frame_resize = 'noresize';

    $attrbt = ' frameborder="'.$addon_info->frame_border.'" ';
    if($addon_info->frame_resize != 'Y') $attrbt .= $addon_info->frame_resize.'="'.$addon_info->frame_resize.'" ';
    if(!$addon_info->frame_scroll) $attrbt .= 'scrolling="no" ';


    if(!$addon_info->frame_position || $addon_info->frame_position == 'up') $size = $addon_info->frame_size.',*';
    else $size = '*,'.$addon_info->frame_size;

    header("Content-Type: text/html; charset=UTF-8");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.Context::getLangType().'" lang="'.Context::getLangType().'">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<title>'.Context::getBrowserTitle().'</title>
<script type="text/javascript">//<![CDATA[
var is_sooframe = true;
//]]></script>
</head>
<frameset rows="'.$size.'">');
    if(!$addon_info->frame_position || $addon_info->frame_position == 'up') print('<frame id="new_frame" name="new_frame" src="'.$addon_info->frame_page.'" '.$attrbt.' />');
    print('<frame id="contents_frame" name="contents_frame" src="'.$uri.'" frameborder="'.$addon_info->frame_border.'" />');
    if($addon_info->frame_position == 'down') print('<frame id="new_frame" name="new_frame" src="'.$addon_info->frame_page.'" '.$attrbt.' />');
    print('
<noframes>
<body>
  <p>죄송합니다. 당신의 브라우저는 프레임셋을 지원하지 않습니다.<br />Sorry, your browser does not handle frames!</p>
  <p>프레임 셋을 제거하고 페이지를 계속 보시려면 아래 링크를 클릭해주세요.<br />If you want to see this site without frames, click the following link.<br />
  <a href="'.$exit_uri.'">'.$exit_uri.'</a></p>
</body>
</noframes>
</frameset>
</html>');
    exit;
  } else {
    Context::addJsFile('./addons/soo_for_muzik_player/js/frame_loader.js');
  }
}
?>