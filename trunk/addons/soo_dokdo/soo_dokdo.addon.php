<?php
if(!defined("__ZBXE__")) exit();

/**
 * @file soo_dokdo.addon.php
 * @brief 홈페이지 상단에 독도 배너 추가. 다음의 모금 페이지 보다는.. 독도에 관한 자료가 있는 페이지로 가는게 나을 듯 하여 독도에 우호적으로 보이는
 * 어느 미국인의 홈페이지로 링크..
 * @author 민수 <misol221@paran.com>
 **/

// called_position이 before_module_proc일때만 실행, 관리자모드에서 작동 안하기
if(Context::get('module')!='admin' && $called_position == 'before_module_proc' && Context::getResponseMethod()!="XMLRPC") {
  $soo_dokdo_script = '';
  $soo_dokdo_foot_script = '';

if(!$addon_info->soo_dokdo_banner) {
$addon_info->soo_dokdo_banner = "eng_White";
  }
if($addon_info->soo_dokdo_no == '') {
$addon_info->soo_dokdo_no = '1';
  }

if(!$addon_info->soo_dokdo_banner_rl_self) {
if(!$addon_info->soo_dokdo_banner_rl) { $addon_info->soo_dokdo_banner_rl = 'left: 0; top: 0; z-index: 100;'; }
elseif($addon_info->soo_dokdo_banner_rl == 'none') { $addon_info->soo_dokdo_banner_rl = ''; }
}
else { $addon_info->soo_dokdo_banner_rl = $addon_info->soo_dokdo_banner_rl_self; }

if($addon_info->soo_dokdo_banner != 'none'){
if(substr($addon_info->soo_dokdo_banner, 0, 3) != 'dok') {
    $soo_dokdo_script = sprintf("<style type=\"text/css\">"."\n".
                        " #tistorySticker {"."\n".
                        "  position: absolute; %s"."\n".
                        "  background: url('%saddons/soo_dokdo/images/dokdo_%s.gif') no-repeat; width: 132px; height: 132px; text-indent: -100em; display: block; overflow: hidden;"."\n".
                        " }"."\n".
                        "</style>", $addon_info->soo_dokdo_banner_rl, Context::getRequestUri(), $addon_info->soo_dokdo_banner);
    $soo_dokdo_foot_script = "<a href=\"http://www.dokdo-takeshima.com/\" title=\"Dokdo\" alt=\"Dokdo is a Korean territory\" target=\"_blank\" id=\"tistorySticker\">Dokdo is a Korean territory</a>";
}
else {
    $soo_dokdo_script = sprintf("<style type=\"text/css\">"."\n".
                        " #dokdo_banner {"."\n".
                        "  position: absolute; %s"."\n".
                        "  border-style: none;"."\n".
                        " }"."\n".
                        "</style>", $addon_info->soo_dokdo_banner_rl);
    $soo_dokdo_foot_script = sprintf("<a href=\"http://www.dokdo-takeshima.com/\" title=\"Dokdo\" target=\"_blank\"><img id=\"dokdo_banner\" src=\"%saddons/soo_dokdo/images/%s.gif\" title=\"Dokdo\" alt=\"Dokdo is a Korean territory\" /></a>", Context::getRequestUri(), $addon_info->soo_dokdo_banner);
}
}
Context::addHtmlHeader($soo_dokdo_script);
Context::addHtmlFooter($soo_dokdo_foot_script);
}
?>
