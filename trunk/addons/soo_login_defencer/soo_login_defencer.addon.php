<?php
	if(!defined('__ZBXE__') && !defined('__XE__')) exit();
	/**
	 * @file soo_login_defencer.addon.php
	 * @author misol (misol@korea.ac.kr)
	 * @brief 일정 시간동안 할 수 있는 로그인 횟수를 제한합니다.
	 * 중복 로그인 방지기능 추가 예정
	 **/
	if(Context::getResponseMethod() == "HTML" && Context::get('is_logged') && $called_position == 'after_module_proc') {
		$member_info = Context::get('logged_info');
		$oMemberModel = &getModel('member');
		if(trim($member_info->user_id)) {
			$id_attention = FileHandler::readFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5(trim($member_info->user_id)).'.php');
			if(defined('__XE__')) {
				$config = $oMemberModel->getMemberConfig();
				if ($config->identifier == 'email_address') {
					$id_attention = FileHandler::readFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5(trim($member_info->email_address)).'.php');
				}
			}
			$id_attention = trim(str_replace(array('<?php /**','**/ ?>'),array('',''),$id_attention));
		}

		if(trim($id_attention)) {
			$title = '로그인 실패 기록 알림';
			$content = sprintf('<h2>확인하지 않은 로그인 실패 기록이 있습니다.</h2><p>%s</p><p>*이 알림은 한번만 보입니다.<br />*이 메시지는 쪽지와 이메일로 발송됩니다.<br />발송 시각 : %s</p>',str_replace("\r\n","<br />",$id_attention),date('Y-m-d H:i:s P'));

			$oCommunicationController = &getController('communication');
			$oCommunicationController->sendMessage($member_info->member_srl, $member_info->member_srl, $title, $content, true);

			if($member_info->email_address) {
				$view_url = Context::getRequestUri();
				$title = sprintf("%s @ %s",$title,$view_url);
				$content = sprintf("%s<hr /><p>From : <a href=\"%s\" target=\"_blank\">%s</a><br />To : %s(%s)</p>",$content, $view_url, $view_url, $member_info->user_name, $member_info->user_id);
				$oMail = new Mail();
				$oMail->setTitle($title);
				$oMail->setContent($content);
				$oMail->setSender($member_info->user_name.'('.$member_info->nick_name.')', $member_info->email_address);
				$oMail->setReceiptor($member_info->user_name.'('.$member_info->nick_name.')', $member_info->email_address);
				$oMail->send();
			}

			$script = sprintf('<script type="text/javascript">//<![CDATA['."\r\n".
												'alert("확인하지 않은 로그인 실패 기록이 있습니다.\n\n%s\n\n*이 알림은 한번만 보입니다.\n*이 메시지는 쪽지와 이메일로 발송됩니다.");'."\r\n".
												'</script>',str_replace("\r\n","\\n",$id_attention));
			Context::addHtmlHeader($script);
			FileHandler::removeFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5(trim($member_info->user_id)).'.php');
			if(defined('__XE__')) {
				if ($config->identifier == 'email_address') {
					$id_attention = FileHandler::removeFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5(trim($member_info->email_address)).'.php');
				}
			}
		}
	}
	if(Context::get('act') == 'procMemberLogin') {
		if($called_position == 'before_display_content') {
			$addon_info->frequency = intval($addon_info->frequency);
			$addon_info->set_time = doubleval($addon_info->set_time);
			if(!$addon_info->frequency) $addon_info->frequency = 5;
			if(!$addon_info->set_time) $addon_info->set_time = 1;
			$ip_based_info = Context::get('soo_login_defencer_ip_based_info');

			if($oModule->getError() == -1) {
				//로그인 실패시 기록.
				$user_id = trim(Context::get('user_id'));
				$oMemberModel = &getModel('member');
				$member_info = $oMemberModel->getMemberInfoByUserID($user_id);
				if(defined('__XE__')) {
					$config = $oMemberModel->getMemberConfig();
					if ($config->identifier == 'email_address') {
						$member_info = $oMemberModel->getMemberInfoByEmailAddress($user_id);
						$member_info->user_id = $member_info->email_address;
					}
				}
				if($user_id && $member_info->user_id == $user_id) {
					$id_attention = FileHandler::readFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5($user_id).'.php');
					$id_attention = $id_attention."\r\n\r\n";
					$id_attention .= '<?php /**'."시간 : ".date('Y-m-d H:i:s P')."\r\n -접속 IP : ".$_SERVER['REMOTE_ADDR']."\r\n -기록된 메시지 : ".$oModule->getMessage().'**/ ?>';
					FileHandler::writeFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5($user_id).'.php',$id_attention);
				}
				$output = str_replace($oModule->getMessage(), $oModule->getMessage()."\r\n".'로그인을 '.$ip_based_info->frequency.'회 시도하였습니다.'."\r\n".$addon_info->frequency.'회 시도하면 차단됩니다.', $output);
			}
		}
		if($called_position == 'before_module_init') {
			$addon_info->frequency = intval($addon_info->frequency);
			$addon_info->set_time = doubleval($addon_info->set_time);
			if(!$addon_info->frequency) $addon_info->frequency = 5;
			if(!$addon_info->set_time) $addon_info->set_time = 1;
			$user_id = trim(Context::get('user_id'));
			$oMemberModel = &getModel('member');
			if(defined('__XE__')) {
				$config = $oMemberModel->getMemberConfig();
				if ($config->identifier == 'email_address') {
					$member_info = $oMemberModel->getMemberInfoByEmailAddress($user_id);
					$member_info->user_id = $member_info->email_address;
				}
			}

			$ip_based_info = FileHandler::readFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php');
			$ip_based_info = trim(str_replace(array('<?php /**','**/ ?>'),array('',''),$ip_based_info));
			$ip_based_info = unserialize($ip_based_info);

			if((time() - $ip_based_info->date) >= ($addon_info->set_time * 120)) {
				$ip_based_info->userid = urlencode($user_id);
				$ip_based_info->frequency = 0;
				$ip_based_info->date = time();
				FileHandler::removeFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php');
			}

			if($ip_based_info->frequency >= $addon_info->frequency) {
				if((time() - $ip_based_info->date) < ($addon_info->set_time * 60)) {
					if(!$ip_based_info->is_denied) {
						$ip_based_info->is_denied = true;
						$ip_based_info->date = time();
						$ip_denied_info = '<?php /**'.serialize($ip_based_info).'**/ ?>';

						// 올바른 비밀번호일때는 패스
						if(!(md5(trim(Context::get('password'))) == $member_info->password && $member_info->user_id == $user_id && $member_info->member_srl)) 
							FileHandler::writeFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php',$ip_denied_info);

						if($user_id && $member_info->user_id == $user_id && $member_info->member_srl) {
							$id_attention = FileHandler::readFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5($user_id).'.php');
							$id_attention = $id_attention."\r\n\r\n";
							$id_attention .= '<?php /**'."시간 : ".date('Y-m-d H:i:s P')."\r\n -접속 IP : ".$_SERVER['REMOTE_ADDR']."\r\n -방어 프로그램 동작 : ".$addon_info->set_time.'분간 로그인이 차단되었습니다. **/ ?>';
							FileHandler::writeFile('./files/cache/addons/soo_login_defencer/id_attention/id_'.md5($user_id).'.php',$id_attention);
						}
					}
					header("Content-Type: text/xml; charset=UTF-8");
					header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
					header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
					header("Cache-Control: no-store, no-cache, must-revalidate");
					header("Cache-Control: post-check=0, pre-check=0", false);
					header("Pragma: no-cache");
					if(defined('__XE__')) {
						printf("<?xml version=\"1.0\" encoding=\"UTF-8\"?><response><error>-1</error><message>로그인이 차단되었습니다. Login is not available.\r\n%0.1f 분 후에 다시 로그인 할 수 있습니다. %0.1f minute left.</message><message_type></message_type></response>",(($addon_info->set_time * 60)-(time() - ($ip_based_info->date)))/60,(($addon_info->set_time * 60)-(time() - $ip_based_info->date))/60);
					} else {
						printf("<response>\r\n<error>-1</error>\r\n<message>로그인이 차단되었습니다. Login is not available.\r\n%0.1f 분 후에 다시 로그인 할 수 있습니다. %0.1f minute left.</message>\r\n</response>",(($addon_info->set_time * 60)-(time() - ($ip_based_info->date)))/60,(($addon_info->set_time * 60)-(time() - $ip_based_info->date))/60);
					}
					Context::close();
					exit();
				}
			}

			$ip_based_info->userid = urlencode($user_id);
			$ip_based_info->frequency++;
			$ip_based_info->date = time();

			Context::set('soo_login_defencer_ip_based_info', $ip_based_info);

			$ip_based_info = '<?php /**'.serialize($ip_based_info).'**/ ?>';

			// 비밀번호 옳을 때는 패스.
			if(!(md5(trim(Context::get('password'))) == $member_info->password && $member_info->user_id == $user_id && $member_info->member_srl)) 
				FileHandler::writeFile('./files/cache/addons/soo_login_defencer/ip_'.$_SERVER['REMOTE_ADDR'].'.php',$ip_based_info);

		}
	}

/** End of file soo_login_defencer.addon.php **/