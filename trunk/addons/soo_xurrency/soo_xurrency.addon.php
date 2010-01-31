<?php
if(!defined("__ZBXE__")) exit();
/**
 * @file soo_xurrency.addon.php
 * @brief 환율 표시 애드온
 * RSS feed이용
 **/
if($called_position == 'before_display_content' && (Context::get('mid') || Context::get('act')=='dispDocumentPrint') && Context::getResponseMethod()!="XMLRPC" && !in_array(Context::get('act'), array('rss','rss','dispBoardWrite','dispBoardModifyComment','procBoardInsertDocument', 'procBoardInsertComment'))) {
  $output=explode('<body',$output);
  $soo_url=Context::getRequestUri();
  $output[1]=preg_replace("/\[xu:([a-zA-Z]{3})\]([0-9]*)\[\/xu\]/i",
                       '<a onclick="window.open(\''.$soo_url.'?xurrency_input=$1&amp;xurrency_sum=$2\',\'window_$1$2\',\'width=300px,height=400px,scrollbars=yes,resizable=yes\');" style="cursor:pointer">$2</a>'
                       ,$output[1]);
  $output=implode('<body',$output);
}
if($called_position == 'before_display_content' && Context::get('act')=='rss') {
  $soo_url=Context::getRequestUri();
  $output=preg_replace("/\[xu:([a-zA-Z]{3})\]([0-9]*)\[\/xu\]/i",
                       '<a onclick="window.open(\''.$soo_url.'?xurrency_input=$1&amp;xurrency_sum=$2\',\'window_$1$2\',\'width=300px,height=400px,scrollbars=yes,resizable=yes\');" style="cursor:pointer">$2</a>'
                       ,$output);
}
if($called_position != 'before_module_init' && Context::get('xurrency_input')!='' && Context::get('xurrency_sum')!='') {
  $soo_xurrency_input = urlencode(strtolower(Context::get('xurrency_input')));
  $soo_xurrency_sum = urlencode(Context::get('xurrency_sum'));
  $query_string = sprintf('/%s/feed', $soo_xurrency_input);
  $fp = fsockopen('xurrency.com', 80, $errno, $errstr);
  fputs($fp, "GET {$query_string} HTTP/1.0\r\n");
  fputs($fp, "User-Agent: Soo_Xurrency_Addon\r\n"); 
  fputs($fp, "Host: xurrency.com\r\n\r\n");
  $buff = '';
  while(!feof($fp)) {
    $str = fgets($fp, 1024);
    if(trim($str)=='') $start = true;
    if($start) $buff .= trim($str);
  }
  fclose($fp);
  $buff = str_replace('<?xml version="1.0" encoding="UTF-8"?>','', $buff);
  $buff = str_replace('rdf:RDF','rdf', $buff);
  $buff = str_replace(array('dc:targetCurrency','dc:value'),array('targetcurrency','value'), $buff); //이상하네.. 왜 안되지.. 안되니까 없애버리기.. 모르겠음..

  $oXmlParser = new XmlParser();
  $xml_doc_xurrency = $oXmlParser->parse($buff);
  $xurrency_items = $xml_doc_xurrency->rdf->item;
  if(!is_array($xurrency_items)) $xurrency_items = array($xurrency_items);
  $xurrency_items_count = count($xurrency_items);
  for($n=0;$n<$xurrency_items_count;$n++) {
    $item = $xurrency_items[$n];
    $xurrency_item = trim($item->value->body);
    $item->targetcurrency->body = str_replace(array('ars','aud','brl','bgn','cad','cop','hrk','czk','dkk','eek','hkd','huf','isk','inr','lvl','ltl','myr','mxn','ron','try','nzd','nok','php','pln','rub','sgd','skk','zar','lkr','sek','chf','twd','thb','vef','usd','krw','gbp','jpy','eur','cny'),
    array('Argentine Peso','Australian Dollar','Brazilian Real','Bulgarian Lev','Canadian Dollar','Colombian Peso','Croatian Kuna','Czech Koruna','Danish Krone','Estonian Kroon','Hong Kong Dollar','Hungarian Forint','Icelandic Krona','Indian Rupee','Latvian Lats','Lithuanian Litas','Malasian Ringgit','Mexican Peso','New Romanian Leu','New Turkish Lira','New Zealand Dollar','Norwegian Krone','Philippine Peso','Polish Zloty','Russian Rouble','Singapore Dollar','Slovak Koruna','South African Rand','Sri Lanka Rupee','Swedish Krona','Swiss Franc','Taiwan Dollar','Thai Baht','Venezuelan Bolivar','＄(United States Dollar)','￦(Korea Won)','￡(Pound Sterling)','￥(Japanese Yen)','€(Euro)','元(Chinese Yuan)'),$item->targetcurrency->body);
    $calculated_xurrency_items[$n] = $xurrency_item*$soo_xurrency_sum.' '.trim($item->targetcurrency->body);
  }
  $calculated_xurrency_items = implode("\n".'      <br />', $calculated_xurrency_items);
echo sprintf('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ko" xml:lang="ko" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>%s %s</title>
%s
</head>
<body>
  <div id="popHeadder">
    <h1>%s %s</h1>
  </div>
  <div id="popBody">
    <p>%s
    </p>
  </div>
  <div id="popFooter" class="tCenter">
    <a href="#" onclick="window.close(); return false;">창 닫기</a>
  </div>
<img id="powerd_by_img" src="http://xurrency.com/images/logo.png" alt="Powerd by Xurrency.com" />
</body>
</html>',$soo_xurrency_sum,$soo_xurrency_input,$addon_info->pop_header_select,$soo_xurrency_sum,str_replace(array('USD','KRW','GBP','JPY','EUR','CNY'),array('＄(USD)','￦(KRW)','￡(GBP)','￥(JPY)','€(EUR)','元(CNY)'),strtoupper($soo_xurrency_input)),$calculated_xurrency_items);
exit;
}

?>
