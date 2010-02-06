<?php
// soo_naver_bookinfo
// ⓒ 김민수 <misol@korea.ac.kr> 2009.
// 네이버 책 검색 추가 컴포넌트
class soo_naver_bookinfo extends EditorHandler {
  var $editor_sequence = '0';
  var $component_path = '';
  var $naver_api_key = '';
  function soo_naver_bookinfo($editor_sequence, $component_path) {
    $this->editor_sequence = $editor_sequence;
    $this->component_path = $component_path;
  }

  function xml_api_request($uri) {
    $xml = '';
    $xml = FileHandler::getRemoteResource($uri, null, 3, 'GET', 'application/xml');
    if(!$xml) return new Object(-1, 'Error'."\n".'Server can not connect to Naver Api Server.');

    $xml = Context::convertEncodingStr(trim(preg_replace("/<\?xml([.^>]*)\?>/i", "", $xml)));

    $oXmlParser = new XmlParser();
    $xml_doc = $oXmlParser->parse($xml);

    return $xml_doc;
  }

  function search_soo_bookinfo() {
    $naver_api_key=trim($this->naver_api_key);
    $soo_bookinfo_display_set=trim($this->soo_bookinfo_display);
    $query = urlencode(trim(Context::get('query')));
    $soo_target = urlencode(trim(Context::get('where')));
    $soo_cata = urlencode(trim(Context::get('cata')));
    $bookinfo_start = urlencode(Context::get('bookinfo_start'));
    $soo_para_cata = '';
    $soo_para_target = '';

    if(!$soo_bookinfo_display_set) $soo_bookinfo_display_set='20';
    if(!$bookinfo_start) $bookinfo_start='1';
    if($soo_target != 'any' || $soo_cata != 'all') {
      if($soo_cata != 'all') {
        $soo_para_cata = '&d_catg='.$soo_cata;
      }
      if($soo_target != 'any') {
        $soo_para_target = '&d_'.$soo_target.'='.$query;
      }
      $uri = sprintf('http://openapi.naver.com/search?key=%s&target=book_adv&start=%s&display=%s&query=%s%s%s', $naver_api_key, $bookinfo_start, $soo_bookinfo_display_set, $query, $soo_para_target, $soo_para_cata);
    }
    else {
      $uri = sprintf('http://openapi.naver.com/search?key=%s&target=book&start=%s&display=%s&query=%s', $naver_api_key, $bookinfo_start, $soo_bookinfo_display_set, $query);
    }

    $xml_doc = '';
    $xml_doc = $this->xml_api_request($uri);
    if($xml_doc->error == -1) return $xml_doc;

    $soo_naver_error_code = trim($xml_doc->error->error_code->body);
    $soo_naver_error_message = trim($xml_doc->error->message->body);
    if($soo_naver_error_message) return new Object(-1, 'Error'."\n".'Naver Open Api Error Code : '.$soo_naver_error_code."\n".'Message : '.$soo_naver_error_message);

    $total_bookinfo_no = trim($xml_doc->rss->channel->total->body);
    $soo_bookinfo_start = trim($xml_doc->rss->channel->start->body);
    $soo_bookinfo_display = trim($xml_doc->rss->channel->display->body);
    $soo_bookinfo_start_end = trim($soo_bookinfo_start.' - '.($soo_bookinfo_start+$soo_bookinfo_display-1));
    if($total_bookinfo_no >= $soo_bookinfo_start+$soo_bookinfo_display && $soo_bookinfo_start+$soo_bookinfo_display <= '1000') {
      $soo_next_page=$soo_bookinfo_start+$soo_bookinfo_display;
    }
    else {
      $soo_next_page="1";
    }
    if($soo_bookinfo_start!='1') {
      $soo_before_page=$soo_bookinfo_start-$soo_bookinfo_display_set;
    }
    else {
      $soo_before_page="1";
    }

    $nvbookinfos = $xml_doc->rss->channel->item;
    if(!is_array($nvbookinfos)) $nvbookinfos = array($nvbookinfos);
    $nvbookinfos_count = count($nvbookinfos);

    $soo_image_list = array();
    $c = 0;
    for($i=0;$i<$nvbookinfos_count;$i++) {
      $item = $nvbookinfos[$i];

      $Nitem = '';
      $Nitem->title = trim(strip_tags($item->title->body));
      $Nitem->link = trim(strip_tags($item->link->body));
      $Nitem->author = trim(strip_tags($item->author->body));
      $Nitem->image = trim(str_replace('http://', '', $item->image->body));
      $Nitem->price = trim(strip_tags($item->price->body));
      $Nitem->discount = trim(strip_tags($item->discount->body));
      $Nitem->publisher = trim(strip_tags($item->publisher->body));
      $Nitem->pubdate = trim(strip_tags($item->pubdate->body));
      $Nitem->isbn = trim(strip_tags($item->isbn->body));
      $Nitem->description = trim(strip_tags($item->description->body));

      if($Nitem->title) $return_item->{'item_'.$i} = $Nitem;
      else $c--;
      $Nitem = '';

    }
    $this->add("num", $i+$c);
    $this->add("item", $return_item);

    $this->add("total_bookinfo_no", $total_bookinfo_no);
    $this->add("bookinfo_start", $soo_bookinfo_start);
    $this->add("bookinfo_start_end", $soo_bookinfo_start_end);
    $this->add("result_list_bfpage", $soo_before_page);
    $this->add("result_list_nextpage", $soo_next_page);
  }

  function getPopupContent() {
    $tpl_path = $this->component_path.'tpl';
    $tpl_file = 'popup.html';
    $naver_api_key=trim($this->naver_api_key);
    if(!$naver_api_key) $tpl_file = 'error.html';
    Context::set("tpl_path", $tpl_path);

    $oTemplate = &TemplateHandler::getInstance();
    return $oTemplate->compile($tpl_path, $tpl_file);
  }
}
?>