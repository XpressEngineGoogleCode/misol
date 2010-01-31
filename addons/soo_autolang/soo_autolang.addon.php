<?php
if(!defined("__ZBXE__")) exit();
/**
 * @file soo_autolang.addon.php
 * @brief 언어 자동 선택 애드온
 **/
if($called_position != 'before_module_init' || Context::get('module')=='admin') return;
if($_COOKIE['lang_type']) return;
$lang_supported = Context::get('lang_supported');
$http_user_lang = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
if(is_array($lang_supported)) {
  $auto_selected = -2;
  foreach($lang_supported as $key => $val) {
    foreach($http_user_lang as $user_key => $user_val) {
      if(eregi($key, $user_val)) {
        if($auto_selected != -1) {
          if(Context::getLangType() != $key) {
            $_COOKIE['lang_type'] = $key;
            Context::setLangType($key);
            Context::get('lang');
            Context::set('lang',new Object);
            Context::loadLang(_XE_PATH_."common/lang/");
            Context::loadLang(_XE_PATH_.'modules/module/lang');
            Context::addHtmlHeader('<script type="text/javascript">
            \/\/<![CDATA[
            setLangType(\''.$key.'\');
            \/\/]]></script>');
          }
          $auto_selected = -1;
          break;
        }
        else break;
      }
    }
  }
}
?>
