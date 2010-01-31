<?php
if(!defined("__ZBXE__")) exit();

/**
 * @file soo_ad_check.addon.php
 * @brief 다음 문맥 키워드로 추천받은 키워드를 태그에 추가.
 * @author 민수 <misol221@paran.com>
 * @copyright license  Creative Commons 저작자표시 2.0 대한민국 라이선스 http://creativecommons.org/licenses/by/2.0/kr/
 **/

// called_position이 before_module_init일때만 실행, 관리자모드에서 작동 안하기
if(Context::get('module')!='admin' && $called_position == 'before_module_proc' && in_array(Context::get('act'), array('trackback','procBoardInsertDocument', 'procBoardInsertComment')) && Context::get('tags')) {
  $soo_tags = trim(Context::get('tags'));
  $soo_tags = explode(',', $soo_tags);
  foreach($soo_tags as $tag) {
    if(!trim($tag)) continue;
    $soo_tags_list[] = trim($tag);
  }

  $soo_tags = implode(',',array_unique($soo_tags_list));
if($addon_info->soo_daum_api_key) {
  $word_q = strip_tags(trim(Context::get('content')));
  $word_q = urlencode($word_q);

//by http://righths.springnote.com/pages/2129394
  $host = 'apis.daum.net';
  $service_uri = '/suggest/keyword?apikey='.$addon_info->soo_daum_api_key;
  $vars ='output=xml&apid=23d95631278700677323&q='.$word_q;

  $header = "Host: $host\r\n";
  $header .= "User-Agent: Xpress Engine soo_daumapi_autotagging addon\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Content-Length: ".strlen($vars)."\r\n";
  $header .= "Connection: close\r\n\r\n";

  $fp = fsockopen("".$host,80, $errno, $errstr);

  fputs($fp, "POST $service_uri  HTTP/1.1\r\n");
  fputs($fp, $header.$vars);

  while (!feof($fp)) {
    $str = fgets($fp, 1024);
    if(trim($str)=='') $start = true;
    if($start) $buff .= trim($str);
  }
  fclose($fp);

    $buff = explode('<daum', $buff);
    $buff = '<daum'.$buff[1];
    $oXmlParser = new XmlParser();
    $xml_doc = $oXmlParser->parse($buff);
    $daum_items = $xml_doc->daum->item;

    if(!is_array($daum_items)) $daum_items = array($daum_items);
    $daum_items_count = count($daum_items);
    for($n=0;$n<$daum_items_count;$n++) {
      $item = $daum_items[$n];
      $soo_tags .= ','.(trim($item->keyword->body));
      $soo_tags = explode(',', $soo_tags);
      $soo_tags = implode(',',array_unique($soo_tags));
      }
}
if($addon_info->soo_naver_api_key) {
  $soo_tags_arr = explode(',', $soo_tags);
  if(!is_array($soo_tags_arr)) $soo_tags_arr = array($soo_tags_arr);
  foreach($soo_tags_arr as $tag) {
    if(!trim($tag)) continue;
    $query_string = sprintf('/search?key=%s&target=recmd&query=%s', $addon_info->soo_naver_api_key, urlencode(trim($tag)));
    $fp = fsockopen('openapi.naver.com', 80, $errno, $errstr);

    fputs($fp, "GET {$query_string} HTTP/1.0\r\n");
    fputs($fp, "Host: openapi.naver.com\r\n\r\n");

    $buff = '';
    while(!feof($fp)) {
        $str = fgets($fp, 1024);
        if(trim($str)=='') $start = true;
        if($start) $buff .= trim($str);
    }

    fclose($fp);

    $buff = explode('<result>', $buff);
    $buff = '<result>'.$buff[1];

    $oXmlParser = new XmlParser();
    $xml_doc_naver = $oXmlParser->parse($buff);

    $naver_items = $xml_doc_naver->result->item;

    if(!is_array($naver_items)) $naver_items = array($naver_items);
    $naver_items_count = count($naver_items);
    for($m=0;$m<$naver_items_count;$m++) {
      $item = $naver_items[$m];
      $soo_tags = $soo_tags.','.trim($item->body);
    }
  }
  $soo_tags = explode(',', $soo_tags);
  $soo_tags = implode(',',array_unique($soo_tags));
}
  Context::set('tags', ','.$soo_tags);

}
?>
