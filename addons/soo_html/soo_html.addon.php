<?php
if(!defined("__ZBXE__")) exit();
/**
 * @file soo_html.addon.php
 * @brief html
 * @author 민수 <misol221@paran.com>
 * @copyright license  Creative Commons 저작자표시 2.0 대한민국 라이선스 http://creativecommons.org/licenses/by/2.0/kr/
 **/

// called_position이 before_module_init일때만 실행, 관리자모드에서 작동 안하기
if($called_position == 'before_module_proc' && in_array(Context::get('act'), array('trackback','procBoardInsertDocument', 'procBoardInsertComment'))) {
  $word_q = trim(Context::get('content'));
  $soo_everytag_array = array();
  $soo_tags_no = '-1';
  // 콜백 함수
  function soo_xhtml_attribute_replace($matches)  {
    $matches[1] = strtolower($matches[1]);
    if(substr($matches[2],0,1)!='"' && substr($matches[2],0,1)!='\'' && substr($matches[2],-1)!='"' && substr($matches[2],-1)!='\'') {
      $soo_pos = stristr($matches[2], '"');
      $soo_pos2 = stristr($matches[2], '\'');
      if($soo_pos=='') {
      $matches[2] = '"'.$matches[2].'"';
      }
      elseif($soo_pos2=='') {
        $matches[2] = '\''.$matches[2].'\'';
      }
    }
    return $matches[1]."=".$matches[2];
  }
  $word_q = explode('<', $word_q);
  $soo_count = count($word_q);
  for($i=1;$i<$soo_count;$i++) {
    $word_q[$i] = explode('>', $word_q[$i]);
    if(substr($word_q[$i][0],0,1) != '!') {
      $word_q[$i][0] = explode(' ', trim($word_q[$i][0]));
      if(!is_array($word_q[$i][0])) $word_q[$i][0] = array($word_q[$i][0]);
      $word_q[$i][0][0] = strtolower($word_q[$i][0][0]);
      $soo_count2 = count($word_q[$i][0]);
      if(in_array($word_q[$i][0][0], array('br','hr','img','area', 'base', 'basefont', 'col', 'frame', 'input', 'isindex', 'link', 'meta', 'param'))) {
        if(substr($word_q[$i][0][($soo_count2-1)],-1) != '/') {
          $word_q[$i][0][($soo_count2)] = '/';
        }
        else if($word_q[$i][0][($soo_count2-1)] != '/') {
          $word_q[$i][0][($soo_count2-1)] = substr($word_q[$i][0][($soo_count2-1)],0,strlen($word_q[$i][0][($soo_count2-1)])-1);
          $word_q[$i][0][($soo_count2)] = '/';
        }
      }
      else {
        if($addon_info->tag_match_select == 'Y') {
          if(substr($word_q[$i][0][0],0,1)!='/') {
            $soo_tags_no = $soo_tags_no + 1;
            $soo_everytag_array[$soo_tags_no] = $word_q[$i][0][0];
          }
          else {
            if(!in_array(substr($word_q[$i][0][0],1),$soo_everytag_array)) {
              unset($word_q[$i][0][0]);
            }
            if($soo_everytag_array[$soo_tags_no]==substr($word_q[$i][0][0],1)) {
              unset($soo_everytag_array[$soo_tags_no]);
              $soo_tags_no = $soo_tags_no - 1;
            }
            else {
              unset($word_q[$i][0][0]);
              for($k=$soo_tags_no;$k<=0;$k=$k-'1') {
                $word_q[$i][0][0] = '/'.$soo_everytag_array[$soo_tags_no].'><'.$word_q[$i][0][0];
                unset($soo_everytag_array[$soo_tags_no]);
                $soo_tags_no = $soo_tags_no - 1;
              }
            }
          }
        }
      }
      if($soo_count2>=2) {
        for($n=1;$n<$soo_count2;$n++) {
          $soo_tags = stristr($word_q[$i][0][$n], '=');
          if($soo_tags) {
            $word_q[$i][0][$n] = preg_replace_callback("/<>([a-zA-Z^=]*)=(.*)/i","soo_xhtml_attribute_replace", '<>'.$word_q[$i][0][$n]);
          }
          elseif(in_array($word_q[$i][0][$n], array('checked', 'compact', 'declare', 'defer', 'disabled', 'ismap', 'multiple', 'noresize', 'noshade', 'nowrap', 'readonly', 'selected'))) {
            $word_q[$i][0][$n]=$word_q[$i][0][$n].'="'.$word_q[$i][0][$n].'"';
          }
        }
      }
      if($word_q[$i][0][0] == 'img') {
        $soo_special_tag = implode(' ', $word_q[$i][0]);
        $soo_special_tag = preg_split('/( |=)/',$soo_special_tag);
        if(!array_search('alt',$soo_special_tag)) {
          $soo_count2 = count($word_q[$i][0]);
          $word_q[$i][0][($soo_count2-1)] = 'alt=""';
          $word_q[$i][0][($soo_count2)] = '/';
        }
        if(!array_search('src',$soo_special_tag)) {
          unset($word_q[$i][0]);
        }
      }
      elseif($word_q[$i][0][0] == 'style') {
        $soo_special_tag = implode(' ', $word_q[$i][0]);
        $soo_special_tag = preg_split('/( |=)/',$soo_special_tag);
        if(!array_search('type',$soo_special_tag)) {
          $soo_count2 = count($word_q[$i][0]);
          $word_q[$i][0][($soo_count2)] = 'type="text/css"';
        }
      }
      elseif($word_q[$i][0][0] == 'script') {
        $soo_special_tag = implode(' ', $word_q[$i][0]);
        $soo_special_tag = preg_split('/( |=)/',$soo_special_tag);
        if(!array_search('type',$soo_special_tag)) { //type 이 없는 스크립트는 .. 보통 javascript...
          $soo_count2 = count($word_q[$i][0]);
          $word_q[$i][0][($soo_count2)] = 'type="text/javascript"';
        }
      }
      $word_q[$i][0] = implode(' ', $word_q[$i][0]);
    }
    $word_q[$i] = implode('>', $word_q[$i]);
  }
  unset($soo_count2);
  unset($soo_count);
  unset($soo_tags);
  $soo_everytag = '';
  foreach ($soo_everytag_array as $soo_tags) {
    $soo_everytag = '</'.$soo_tags.'>'.$soo_everytag;
    unset($soo_tags);
  }
  $word_q = trim(implode('<', $word_q)).$soo_everytag;
  unset($soo_everytag);
  $word_q = str_replace('<>','',$word_q);
  Context::set('content', $word_q);
  unset($word_q);
}
?>
