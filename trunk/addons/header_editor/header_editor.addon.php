<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file header_editor.addon.php
     * @author MinSooKim (http://imsoo.net)
     * @brief HTML Header Editor addon
     *
     * HTML 헤더부분 입력을 웹상에서 할 수 있도록 하는 애드온입니다.
     **/
    if($called_position != 'after_module_proc' || Context::get('module')=='admin') return;

    // 대상 모듈
    $but_list = explode("|@|",$addon_info->but_list);
	if(in_array(Context::get('mid'),$mid_list) && Context::get('mid')!="") return;

    $header_script = sprintf(
        '%s',
        $addon_info->hd1
    );

    Context::addHtmlHeader($header_script);
?>
