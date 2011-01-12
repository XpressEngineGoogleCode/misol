<?php
class SooFeedSaver {
	function init() {
		return;
	}
	function prepareToPrint(&$output) {
		$soo_handler = Context::get('soo_feed_delay_handler');
		if(method_exists($soo_handler, "prepareToPrint")) $soo_handler->prepareToPrint($output);

		$addon_info = Context::get('soo_feed_delay');

		FileHandler::removeDir($addon_info->feed_path);
		FileHandler::writeFile($addon_info->feed_file, str_replace(array("\r","\n","\t","\x0B",'    '),array('','','','',''),$output));
		unset($soo_handler);
		unset($addon_info);
	}
}
?>