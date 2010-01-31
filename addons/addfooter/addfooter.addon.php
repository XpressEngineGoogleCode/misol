<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file addfooter.addon.php
     * @author MinSooKim (http://imsoo.net)
     * @brief addfooter
     *
     * 아기곰돌이님 애드온을 보고서.. 이러면 되지 않을까 해서..
     **/
    if($called_position != 'after_module_proc' || Context::get('module')=='admin') return;
    // 제외 모듈
    $mid_list = explode("|@|",$addon_info->but_list);
	if(in_array(Context::get('mid'),$mid_list) && Context::get('mid')!="") return;
    $footer_script = sprintf(
        '%s',
        $addon_info->footer_script
    );
    Context::addHtmlFooter($footer_script);
?>
