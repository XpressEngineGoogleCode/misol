<?php
  if(!defined("__ZBXE__")) exit();
  /**
   * @file soo_login_defencer.addon.php
   * @author misol (misol@korea.ac.kr)
   * @brief 일정 시간동안 할 수 있는 로그인 횟수를 제한합니다.
   **/
  if(Context::getResponseMethod() == "HTML" && Context::get('is_logged') && $called_position == 'after_module_proc') {
    $member_info = Context::get('logged_info');
    if(trim($member_info->user_id)) {
      $id_attention = FileHandler::readFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.urlencode(trim($member_info->user_id)).'.php');
      $id_attention = trim(str_replace(array('<?php /**','**/ ?>'),array('',''),$id_attention));
    }

    if(trim($id_attention)) {
      $title = '로그인 실패 기록 알림';
      $content = sprintf('<h2>확인하지 않은 로그인 실패 기록이 있습니다.</h2><p>%s</p><p>*이 알림은 한번만 보입니다.<br />*이 메세지는 쪽지와 이메일로 발송됩니다.<br />발송 시각 : %s</p>',str_replace("\r\n","<br />",$id_attention),date('Y-m-d H:i:s P'));

      $oCommunicationController = &getController('communication');
      $oCommunicationController->sendMessage($member_info->member_srl, $member_info->member_srl, $title, $content, true);

      if($member_info->email_address) {
        $view_url = Context::getRequestUri();
        $title = sprintf("%s @ %s",$title,$view_url);
        $content = sprintf("%s<br /><br />From : <a href=\"%s\" target=\"_blank\">%s</a><br />To : %s(%s)",$content, $view_url, $view_url, $member_info->user_name, $member_info->user_id);
        $oMail = new Mail();
        $oMail->setTitle($title);
        $oMail->setContent($content);
        $oMail->setSender($member_info->user_name, $member_info->email_address);
        $oMail->setReceiptor($member_info->user_name, $member_info->email_address);
        $oMail->send();
      }

      $script = sprintf('<script type="text/javascript">//<![CDATA['."\r\n".
                        'alert("확인하지 않은 로그인 실패 기록이 있습니다.\n\n%s\n\n*이 알림은 한번만 보입니다.\n*이 메세지는 쪽지와 이메일로 발송됩니다.");'."\r\n".
                        '</script>',str_replace("\r\n","\\n",$id_attention));
      Context::addHtmlHeader($script);
      FileHandler::removeFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.urlencode(trim($member_info->user_id)).'.php');
    }
  }
  if(Context::get('act') == 'procMemberLogin') {
    if($called_position == 'before_display_content') {
      $addon_info->frequency = intval($addon_info->frequency);
      $addon_info->set_time = doubleval($addon_info->set_time);
      if(!$addon_info->frequency) $addon_info->frequency = 5;
      if(!$addon_info->set_time) $addon_info->set_time = 1;
      $ip_based_info = Context::get('soo_login_defencer_ip_based_info');


      //로그인 성공시 기록 삭제
      if($oModule->getError() == -1) {
        //로그인 실패시 기록.
        $user_id = trim(Context::get('user_id'));
        $oMemberModel = &getModel('member');
        $member_info = $oMemberModel->getMemberInfoByUserID($user_id);
        if($user_id && $member_info->user_id == $user_id) {
          $id_attention = FileHandler::readFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.urlencode($user_id).'.php');
          $id_attention = $id_attention."\r\n\r\n";
          $id_attention .= '<?php /**'."시간 : ".date('Y-m-d H:i:s P')."\r\n -접속 IP : ".$_SERVER['REMOTE_ADDR']."\r\n -기록된 메시지 : ".$oModule->getMessage().'**/ ?>';
          FileHandler::writeFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.urlencode($user_id).'.php',$id_attention);
        }
        $output = str_replace($oModule->getMessage(), $oModule->getMessage()."\r\n".'로그인을 '.$ip_based_info->frequency.'회 시도하였습니다.'."\r\n".$addon_info->frequency.'회 시도하면 차단됩니다.', $output);
      }
      else {
        FileHandler::removeFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php');
      }
    }
    if($called_position == 'before_module_init') {
      $addon_info->frequency = intval($addon_info->frequency);
      $addon_info->set_time = doubleval($addon_info->set_time);
      if(!$addon_info->frequency) $addon_info->frequency = 5;
      if(!$addon_info->set_time) $addon_info->set_time = 1;
      $user_id = trim(Context::get('user_id'));

      $ip_based_info = FileHandler::readFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php');
      $ip_based_info = trim(str_replace(array('<?php /**','**/ ?>'),array('',''),$ip_based_info));
      $ip_based_info = unserialize($ip_based_info);

      if($ip_based_info->frequency >= $addon_info->frequency) {
        if((time() - $ip_based_info->date) < ($addon_info->set_time * 60)) {
          if(!$ip_based_info->is_denied) {
            $ip_based_info->is_denied = true;
            $ip_based_info->date = time();
            $ip_denied_info = '<?php /**'.serialize($ip_based_info).'**/ ?>';
            FileHandler::writeFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php',$ip_denied_info);

            $oMemberModel = &getModel('member');
            $member_info = $oMemberModel->getMemberInfoByUserID($user_id);
            if($user_id && $member_info->user_id == $user_id) {
              $id_attention = FileHandler::readFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.urlencode($user_id).'.php');
              $id_attention = $id_attention."\r\n\r\n";
              $id_attention .= '<?php /**'."시간 : ".date('Y-m-d H:i:s P')."\r\n -접속 IP : ".$_SERVER['REMOTE_ADDR']."\r\n -방어 프로그램 동작 : ".$addon_info->set_time.'분간 로그인이 차단되었습니다. **/ ?>';
              FileHandler::writeFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.urlencode($user_id).'.php',$id_attention);
            }
          }
          header("Content-Type: text/xml; charset=UTF-8");
          header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
          header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
          header("Cache-Control: no-store, no-cache, must-revalidate");
          header("Cache-Control: post-check=0, pre-check=0", false);
          header("Pragma: no-cache");
          printf("<response>\r\n<error>-1</error>\r\n<message>로그인이 차단되었습니다. Login is not available.\r\n%0.1f 분 후에 다시 로그인 할 수 있습니다. %0.1f minute left.</message>\r\n</response>",(($addon_info->set_time * 60)-(time() - ($ip_based_info->date)))/60,(($addon_info->set_time * 60)-(time() - $ip_based_info->date))/60);
          Context::close();
          exit();
        }
        else {
          $ip_based_info->userid = urlencode($user_id);
          $ip_based_info->frequency = 0;
          $ip_based_info->date = time();
        }
      }

      $ip_based_info->userid = urlencode($user_id);
      $ip_based_info->frequency++;
      $ip_based_info->date = time();

      Context::set('soo_login_defencer_ip_based_info', $ip_based_info);

      $ip_based_info = '<?php /**'.serialize($ip_based_info).'**/ ?>';
      FileHandler::writeFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php',$ip_based_info);

    }
  }
?>
