<?php
if(!defined("__ZBXE__")) exit();
// file soo_for_muzik_player.addon.php
// author misol (misol@korea.ac.kr)
// brief 브라우저로 접속시 프레임을 나눕니다.
// ⓒ 2010 김민수.
// 특정 액션에서 동작 안함
if(Context::get('module') == 'admin') return;
if(class_exists('smartphoneXE')) {
  if(smartphoneXE::isFromSmartPhone()) return;
}
$nac = 'soo_for_muzik_player';
if($called_position == 'before_module_init') {
  if(Context::get('outframe')=='true') {
    $_SESSION['soo_for_muzik_player']['outframe'] = 2;
    FileHandler::writeFile('./files/cache/addons/soo_for_muzik_player/ip/ip_'.urlencode($_SERVER['REMOTE_ADDR']).'.txt',1);
  }
}
if(Context::get('fr')) {
  Context::addJsFile('./addons/soo_for_muzik_player/js/frame_loader.js');
}
// 프레임을 사용하지 않도록 세션이 정의되어 있으면 작동하지 않음.
if($_SESSION['soo_for_muzik_player']['outframe'] == 2) return;
// 로봇일 경우 동작 안함.
if(function_exists('isCrawler')) {
  if(isCrawler()) return;
}

if(Context::getResponseMethod()=='XMLRPC' && Context::get('module') == 'addon' && Context::get('act') == 'soo_for_muzik_player' && !Context::get('soo_m_output')) {
  if(Context::get('do') == 'doFrameSessionStart') {
    $_SESSION['soo_for_muzik_player']['doFrameSessionStart'] = true;
    $output_xml  = "<response>\n<error>0</error>\n<message>success</message>\n</response>";
    Context::set('soo_m_output',$output_xml);
  }
  if(Context::get('do') == 'doFrameSessionChecker') {
    if($_SESSION['soo_for_muzik_player']['doFrameSessionStart']) {
      $output_xml  = "<response>\n<error>0</error>\n<message>FrameSessionStartSuccess</message>\n</response>";
      Context::set('soo_m_output',$output_xml);
    }
    else {
      $output_xml  = "<response>\n<error>0</error>\n<message>FrameSessionStartFail</message>\n</response>";
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
  ${$nac} = 'so';
  ${$nac} .= 'o_fo';
  //POST 내용 있으면 동작 안함.
  if(is_array($_POST)) if(count($_POST)) return;
  if($soo_for_muzik_player != 'soo_fo') return;
  // 프레임 탈출 코드.
  if($called_position == 'before_module_init') {
    //세션이 동작하지 않는 User-Agent를 위한 처리
    $check = FileHandler::readFile('./files/cache/addons/soo_for_muzik_player/ip/ip_'.urlencode($_SERVER['REMOTE_ADDR']).'.txt');
    if($check) {
      // 세션을 전역 변수처럼 사용. (애드온 사용 순서가 순차적으로 적용되니까.)
      $_SESSION['soo_for_muzik_player']['outframe'] = 2;
      return;
    }
  } elseif($called_position == 'before_display_content') {
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

      if(!$addon_info->frame_size && $addon_info->frame_size != 0) $addon_info->frame_size = 20;
      if(!$addon_info->frame_border) $addon_info->frame_border = '0';
      if(!$addon_info->frame_resize) $addon_info->frame_resize = 'noresize';

      $attrbt = ' frameborder="'.$addon_info->frame_border.'" ';
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
      $output .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$langtype.'" lang="'.$langtype.'">
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <title>'.$title.'</title>
  <script type="text/javascript">'.'/'.'/'.'<![CDATA[
  var is_sooframe = true;
  var is_sooframe_loaded = false;
  function framelct() {
    if(!is_sooframe_loaded) {
      document.getElementById("contents_frame").src = document.getElementById("contents_frame").src;
      document.getElementById("new_frame").src = document.getElementById("new_frame").src;
    }
  }
  '.'/'.'/]]></script>
  </head> <frameset '.$frame_pos.'="'.$size.'" onload="framelct()">';
      if(!$addon_info->frame_position || $addon_info->frame_position == 'up' || $addon_info->frame_position == 'left') {
        $output .= sprintf(' <frame id="new_frame" name="new_frame" src="%s" %s />', $addon_info->frame_page, $attrbt);
      }

      $output .= sprintf(' <frame id="contents_frame" name="contents_frame" src="%s" frameborder="%s" scrolling="auto" />', $uri, $addon_info->frame_border);

      if($addon_info->frame_position == 'down' || $addon_info->frame_position == 'right') {
        $output .= sprintf(' <frame id="new_frame" name="new_frame" src="%s" %s />', $addon_info->frame_page, $attrbt);
      }
      $output .= "\n";
      $output .= '<noframes><body> <p>죄송합니다. 이 브라우저는 프레임셋을 지원하지 않습니다.<br />Sorry, your browser does not handle frames!</p> <p>프레임 셋을 제거하고 페이지를 계속 보시려면 아래 링크를 클릭해주세요.<br />If you want to see this site without frames, click the following link.<br />';
      $output .= ' <a href="'.$exit_uri.'">'.$exit_uri.'</a></p> </body> </noframes> </frameset> </html>';
      // 변경된 문서가 헤더를 제대로 달고 나가고, 다른 애드온들에 의해 HTML편집 당하는 일 없게 JSON방식인것처럼 속이기(?!) 이런 방식으로 출력하는 것이 아니었으나... 어떤 홈페이지에서 작동 안되는 것을 발견하여서... 하다보니.. 이 방법 외엔 없었다 ㅜ.ㅜ;
      Context::setResponseMethod('JSON');

    } else {
      Context::addJsFile('./addons/soo_for_muzik_player/js/frame_loader.js');
    }
  }
  return;
}
?>