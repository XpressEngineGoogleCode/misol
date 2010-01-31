<?php
if(!defined("__ZBXE__")) exit();

/**
 * @file soo_parking.addon.php
 * @brief 공사중 화면표시 애드온
 *
 * 지정된 사용자 이외에는 공사중 화면을 보게됩니다.
 **/
if(Context::getResponseMethod() == 'HTML') {
  if($called_position == 'before_module_proc' && Context::get('module') != 'admin') {
    $Member = &getModel('member');
    $MemberID=$Member->getLoggedUserID();
    $view_checker = 0;
    if($addon_info->until) if(time() > strtotime($addon_info->until)) $view_checker = 1;
    if(isset($MemberID)) {
    // member ID
    if($addon_info->but_group != '' || $addon_info->but_id != '') {
      $MemberSRL=$Member->getMemberSrlByUserID($MemberID);
      $MemberGroups=$Member->getMemberGroups($MemberSRL);
      $but_ids = explode(",",$addon_info->but_id);
      if(is_array($but_ids)) {
        if(in_array($MemberID, $but_ids) && $MemberID!='') $view_checker = 1;
      }
      // member Group
      $but_groups = explode(",",$addon_info->but_group);
      if(is_array($MemberGroups)) {
        foreach($MemberGroups as $value) {
          if(in_array($value,$but_groups) && $value!='') $view_checker = 1;
        }
      }
    }
    }

    //user-agent
    if($addon_info->except_useragent || $addon_info->do_useragent) {
      if($addon_info->except_useragent) {
        $except_useragent = explode("\n",$addon_info->except_useragent);
        foreach($except_useragent as $value) {
          if(trim($value)) if(strpos($_SERVER['HTTP_USER_AGENT'],trim($value)) !== false) $view_checker = 1;
        }
      }
      if($addon_info->do_useragent) {
        $do_useragent = explode("\n",$addon_info->do_useragent);
        foreach($do_useragent as $value) {
          if(trim($value)) if(strpos($_SERVER['HTTP_USER_AGENT'],trim($value)) !== false) {
            $message = FileHandler::readFile('./files/cache/addons/soo_parking/ip_'.$_SERVER['HTTP_USER_AGENT'].'.php');
            $message .= "\nDate".date('Y-m-d H:i:s P')." IP".$_SERVER['REMOTE_ADDR'];
            echo ($message);
            exit;
            FileHandler::writeFile('./files/cache/addons/soo_parking/ip_'.urlencode($_SERVER['HTTP_USER_AGENT']).'.php',$message);
          }
        }
      }
    }

    if($addon_info->view_message && $view_checker == 0) {
      echo Context::convertEncodingStr($addon_info->view_message);
      exit();
    }
    else if($view_checker == 0){
      echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
 <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <title>Not ready to show</title>
 </head>
 <body>
  <div style="text-align:center;">
   <h1>This Homepage is not ready to show!</h1>
   <p>Comming Soon...</p>
  </div>
 </body>
</html>';
      exit();
    }
  }
}
?>
