<?php
if(!defined("__ZBXE__")) exit();
// author misol (misol@korea.ac.kr)
if($called_position == 'before_display_content') {
	if(class_exists('Mobile')) {
		if(Mobile::isFromMobilePhone()) {
			$oEditorController = &getController('editor');
			$output = $oEditorController->transComponent($output);
		}
	}
}
?>
