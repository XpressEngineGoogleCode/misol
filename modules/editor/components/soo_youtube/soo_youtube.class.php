<?php
  /**
   * @class  soo_youtube
   * @author misol(김민수) <misol@korea.ac.kr>
   * @brief  비디오 검색 컴포넌트
   * Copyright (C) Kim,Min-Soo.
   **/
class soo_youtube extends EditorHandler {
  var $editor_sequence = '0';
  var $component_path = '';

  function soo_youtube($editor_sequence, $component_path) {
    $this->editor_sequence = $editor_sequence;
    $this->component_path = $component_path;
  }

  function xml_api_request($uri) {
    $rss = '';
    $rss = FileHandler::getRemoteResource($uri, null, 3, 'GET', 'application/xml');

    $rss = preg_replace("/<\?xml([.^>]*)\?>/i", "", $rss);

    $oXmlParser = new XmlParser();
    $xml_doc = $oXmlParser->parse($rss);

    return $xml_doc;
  }

  function page_calculator($total_result_no, $soo_result_start, $soo_search_display, $soo_display_set) {
    $obj = new Object();

    if($total_result_no >= $soo_result_start+$soo_search_display) $soo_next_page=$soo_result_start+$soo_display_set;
    else $soo_next_page="1";

    if($soo_result_start!='1')  $soo_before_page=$soo_result_start-$soo_display_set;
    else $soo_before_page="1";

    $obj->soo_next_page = $soo_next_page;
    $obj->soo_before_page = $soo_before_page;

    return $obj;
  }

  function soo_search() {
    $soo_display_set=trim($this->soo_display);
    $q_site = trim(Context::get('q_site'));
    $q_sort = urlencode(trim(Context::get('q_sort')));
    $query = urlencode(trim(Context::get('query')));
    $soo_result_start = urlencode(Context::get('soo_result_start'));

    if($q_site == 'daum') return $this->soo_search_daum($query, $soo_display_set, $soo_result_start, $q_sort);
    elseif($q_site == 'youtube' || !$q_site) return $this->soo_search_youtube($query, $soo_display_set, $soo_result_start, $q_sort);
    else return new Object(-1, '::  Component Error  ::'."\n".'Unexpected request.');
  }

  function soo_search_youtube($query, $soo_display_set = '20', $soo_result_start = '1', $q_sort = 'relevance') {
    if(!$soo_display_set) $soo_display_set = '20';
    if(!$soo_result_start) $soo_result_start = '1';
    if(!$q_sort) $q_sort = 'relevance';
    $uri = sprintf('http://gdata.youtube.com/feeds/api/videos?q=%s&start-index=%s&max-results=%s&orderby=%s&v=2&alt=rss',$query, $soo_result_start, $soo_display_set, $q_sort);

    $xml_doc = $this->xml_api_request($uri);

    $error_code = trim($xml_doc->errors->error->code->body);
    $error_message = trim($xml_doc->errors->error->internalreason->body);
    if($error_message) return new Object(-1, '::  Youtube API Error  ::'."\n".$error_code."\n".$error_message);


    $total_result_no = trim($xml_doc->rss->channel->{'opensearch:totalresults'}->body);
    $soo_result_start = trim($xml_doc->rss->channel->{'opensearch:startindex'}->body);
    $soo_search_display = trim($xml_doc->rss->channel->{'opensearch:itemsperpage'}->body);

    $obj = $this->page_calculator($total_result_no, $soo_result_start, $soo_search_display, $soo_display_set);

    $soo_next_page = $obj->soo_next_page;
    $soo_before_page = $obj->soo_before_page;

    $soo_results = $xml_doc->rss->channel->item;
    if(!is_array($soo_results)) $soo_results = array($soo_results);

    $soo_results_count = count($soo_results);
    $soo_result_start_end = trim($soo_result_start.' - '.($soo_result_start+$soo_results_count-1));
    $soo_list = array();
    for($i=0;$i<$soo_results_count;$i++) {
      $item = $soo_results[$i];
      $item_images = $item->{'media:group'}->{'media:thumbnail'};
      if(!is_array($item_images)) $item_images = array($item_images);
      $item_published = explode('T',$item->{'media:group'}->{'yt:uploaded'}->body);
      $item_updated = explode('T',$item->{'atom:updated'}->body);
      $item_second = ($item->{'media:group'}->{'yt:duration'}->attrs->seconds%60);
      $item_minute = (intval($item->{'media:group'}->{'yt:duration'}->attrs->seconds/60))%60;
      $item_hour =  intval((intval($item->{'media:group'}->{'yt:duration'}->attrs->seconds/60))/60);
      if(!is_array($item->{'media:group'}->{'media:content'})) $item->{'media:group'}->{'media:content'} = array($item->{'media:group'}->{'media:content'});

      $soo_list[] = sprintf("%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s",
        trim($item->title->body),
        trim($item->author->body),
        trim($item->{'media:group'}->{'media:content'}[0]->attrs->url),
        trim($item_images[0]->attrs->url),
        trim($item_published[0]),
        trim($item_updated[0]),
        trim($item_hour),
        trim($item_minute),
        trim($item_second),
        trim($item->{'yt:statistics'}->attrs->viewcount),
        trim($item->link->body),
        cut_str(trim($item->title->body),20)
        );
    }

    $this->add("total_result_no", $total_result_no);
    $this->add("soo_result_start", $soo_result_start);
    $this->add("soo_result_start_end", $soo_result_start_end);
    $this->add("result_list_bfpage", $soo_before_page);
    $this->add("result_list_nextpage", $soo_next_page);
    $this->add("result_list", implode("\n", $soo_list));
  }


  function soo_search_daum($query, $soo_display_set = '20', $soo_result_start = '1', $q_sort = 'exact') {
    $apikey = $this->soo_daum_api_key;

    if(!$soo_display_set) $soo_display_set = '20';
    if(!$soo_result_start) $soo_result_start = '1';
    if(!$apikey) return new Object(-1, ':: Component Error ::'."\n".'No Daum API KEY');
    $pageno = intval($soo_result_start / $soo_display_set) + 1;

    if($q_sort == 'published') $q_sort = 'date';
    elseif($q_sort == 'rating') $q_sort = 'recommend';
    else $q_sort = 'exact';
    $uri = sprintf('http://apis.daum.net/search/vclip?q=%s&apikey=%s&pageno=%s&result=%s&sort=%s&output=xml',$query, $apikey, $pageno, $soo_display_set, $q_sort);

    $xml_doc = $this->xml_api_request($uri);

    $error_code = trim($xml_doc->apierror->code->body);
    $error_code .= ' | '.trim($xml_doc->apierror->dcode->body);
    $error_message = trim($xml_doc->apierror->message->body);
    $error_message .= "\n".trim($xml_doc->apierror->dmessage->body);
    if($xml_doc->apierror->code->body) return new Object(-1, ':: DAUM API Error ::'."\n".$error_code."\n".$error_message);

    $total_result_no = trim($xml_doc->channel->totalcount->body);
    $soo_result_start = trim($xml_doc->channel->pageno->body);
    $soo_search_display = trim($xml_doc->channel->result->body);
    $soo_result_start = $soo_result_start*$soo_display_set - ($soo_display_set-1);

    $obj = $this->page_calculator($total_result_no, $soo_result_start, $soo_search_display, $soo_display_set);

    $soo_next_page = $obj->soo_next_page;
    $soo_before_page = $obj->soo_before_page;

    $soo_results = $xml_doc->channel->item;
    if(!is_array($soo_results)) $soo_results = array($soo_results);

    $soo_results_count = count($soo_results);
    $soo_result_start_end = trim($soo_result_start.' - '.($soo_result_start+$soo_results_count-1));
    $soo_list = array();
    for($i=0;$i<$soo_results_count;$i++) {
      $item = $soo_results[$i];
      $item_updated = explode('T',$item->{'atom:updated'}->body);
      $item_second = ($item->playtime->body % 60);
      $item_minute = (intval($item->playtime->body/60))%60;
      $item_hour =  intval((intval($item->playtime->body/60))/60);
      if(!is_array($item->{'media:group'}->{'media:content'})) $item->{'media:group'}->{'media:content'} = array($item->{'media:group'}->{'media:content'});

      $soo_list[] = sprintf("%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s,[[soo]],%s",
        trim($item->title->body),
        trim($item->author->body),
        trim($item->player_url->body),
        trim($item->thumbnail->body),
        trim(date('Y-m-d',strtotime($item->pubdate->body))),
        trim($item_updated[0]),
        trim($item_hour),
        trim($item_minute),
        trim($item_second),
        trim($item->playcnt->body),
        trim($item->link->body),
        cut_str(trim($item->title->body),20),
        trim($item->score->body),
        trim($item->tag->body),
        trim($item->cpname->body)
        );
    }

    $this->add("total_result_no", $total_result_no);
    $this->add("soo_result_start", $soo_result_start);
    $this->add("soo_result_start_end", $soo_result_start_end);
    $this->add("result_list_bfpage", $soo_before_page);
    $this->add("result_list_nextpage", $soo_next_page);
    $this->add("result_list", implode("\n", $soo_list));
  }

  function getPopupContent() {
    $tpl_path = $this->component_path.'tpl';
    $tpl_file = 'popup.html';
    if(!$this->soo_design) $this->soo_design = 'habile';
    Context::set("tpl_path", $tpl_path);
    Context::set("design", htmlspecialchars(trim($this->soo_design)).'_popup.css');
    if(trim($this->soo_daum_api_key)) Context::set("soo_daum_api_key", trim($this->soo_daum_api_key));
    $oTemplate = &TemplateHandler::getInstance();
    return $oTemplate->compile($tpl_path, $tpl_file);
  }

  function transHTML($obj) {
    $style = trim($obj->attrs->style).' ';
    preg_match('/width([ ]*):([ ]*)([0-9 a-z]+)(;| )/i', $style, $width_style);
    preg_match('/height([ ]*):([ ]*)([0-9 a-z]+)(;| )/i', $style, $height_style);
    if($width_style[3]) $width_style[3] = intval($width_style[3]);
    if($width_style[3]) $width = trim($width_style[3]);
    else $width = intval($obj->attrs->width);

    if($height_style[3]) $height_style[3] = intval($height_style[3]);
    if($height_style[3]) $height = trim($height_style[3]);
    else $height = intval($obj->attrs->height);
    $obj->attrs->value = trim($obj->attrs->value);
    $value_url = parse_url($obj->attrs->value);
    if(!preg_match('/^(.+?)\.daum\.net$/m', $value_url['host']) && !preg_match('/^(.+?)\.youtube\.com$/m', $value_url['host'])) return 'Unexpected host error';
    $value = htmlspecialchars($obj->attrs->value);
    return sprintf('<object width="%d" height="%d"><param name="wmode" value="opaque"></param><param name="movie" value="%s"></param><param name="allowFullScreen" value="true"></param><embed width="%d" height="%d" src="%s" allowfullscreen="true" wmode="opaque"></embed></object>', $width, $height, $value, $width, $height, $value);
  }
}
?>