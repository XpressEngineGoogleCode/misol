<?php
if(!defined("__ZBXE__")) exit();
// author misol (misol@korea.ac.kr)

// ����Ʈ�� �������� Ȯ���ϴ� �κ�. XE ��� �������� �������� �ʴ� ����̹Ƿ� �ش� ����� �ִ��� ���� Ȯ��. Mobile �� �� �� �ֽ� ���������� �� ���������� �ֵ���� ���������� smartphoneXE�� ����.
if(!isset($mobile_set)) {
	$mobile_set = false;
	if(class_exists('Mobile')) {
		if(Mobile::isFromMobilePhone()) {
			$mobile_set = true;
		}
	} elseif(class_exists('smartphoneXE')) {
		if(smartphoneXE::isFromSmartPhone()) {
			$mobile_set = true;
		}
	}
}

if($called_position == 'before_display_content') {
	if($mobile_set) {
		$oEditorController = &getController('editor');
		$output = $oEditorController->transComponent($output);
	}
}
?>
