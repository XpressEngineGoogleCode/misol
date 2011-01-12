<?php
if(!defined("__ZBXE__")) exit();
// file : soo_feed_delay.addon.php
// author : misol (misol@korea.ac.kr)
if(!in_array(Context::get('act'), array('rss','atom'))) return;

// called position에 따라 동작 나누기. 캐시 확인은 가장 먼저. 캐시 없으면 출력 직전에 캐시 생성 후 이후 요청에서는 캐시 문서 보여줌. 캐시는 정해진 시간마다 재생성.
if($called_position == 'before_module_init') {
	// 피드 캐시가 있는지 확인
	switch ($addon_info->cycle) {
		case "apm":
			$feed_time_code = date('Y.m.W.d.a');
			break;
		case "hour":
			$feed_time_code = date('Y.m.W.d.a.H');
			break;
		case "minute":
			$feed_time_code = date('Y.m.W.d.a.H.i');
			break;
		default:
			$feed_time_code = date('Y.m.W.d');
			break;
	}
	$feed_code = md5(Context::getRequestUrl());
	$addon_info->feed_file = './files/cache/addons/soo_feed_delay/'.$feed_code.'/'.$feed_time_code.'.xml';
	$addon_info->feed_path = './files/cache/addons/soo_feed_delay/'.$feed_code.'/';
	$feed_cache = FileHandler::getRealPath($addon_info->feed_file);

	// 캐시파일이 있으면 출력 브라우저 캐시를 이용하려면 머리가 복잡해지는듯 하여... 그냥 가자~!
	if(file_exists($feed_cache)) {
		header("Content-Type: text/xml; charset=UTF-8");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s",filemtime($feed_cache)) . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		$feed = FileHandler::readFile($feed_cache);
		if( (defined('__OB_GZHANDLER_ENABLE__') && __OB_GZHANDLER_ENABLE__ == 1) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')!==false && function_exists('ob_gzhandler') && extension_loaded('zlib') ) {
			header("Content-Encoding: gzip");
			print ob_gzhandler($feed, 5);
		} else print $feed;
		exit();
	}
	
	Context::set('soo_feed_delay',$addon_info);

} elseif($called_position == 'before_display_content') {
	Context::set('soo_feed_delay_handler',$handler);
	unset($handler);

	require_once("./addons/soo_feed_delay/class/handler.php");
	$handler = new SooFeedSaver();
}
?>