<?php
    /**
     * @class  soo_diclink
     * @author 민수 <misol221@paran.com>
     * @brief  사전 검색 링크 컴포넌트
     **/
class soo_diclink extends EditorHandler {
  var $editor_sequence = '0';
  var $component_path = '';
  var $api_key = '';
  function soo_diclink($editor_sequence, $component_path) {
    $this->editor_sequence = $editor_sequence;
    $this->component_path = $component_path;
  }
  function soo_search() {
    $apikey=trim($this->daum_api_key);
    $result_set=trim($this->soo_diclink_display);
    $qu = urlencode(trim(Context::get('query')));
    $target = urlencode(trim(Context::get('where')));
    $page = urlencode(Context::get('page'));
    if(!$page) $page = 1;
    if(!$result_set) $result_set='20';
    if(!$soo_result_start) $soo_result_start='1';
    if(!$kind) $kind = 'WORD';

    $uri = sprintf('http://apis.daum.net/dic/%s?apikey=%s&q=%s&kind=%s&result=%d&pageno=%d&output=xml',$target,$apikey,$qu,$kind,$result_set,$page);

    $xml = '';
    $xml = FileHandler::getRemoteResource($uri, null, 3, 'GET', 'application/xml');

    $xml = preg_replace("/<\?xml([^(\?>)]+)\?>/i", '', $xml);

    $oXmlParser = new XmlParser();
    $xml_doc = $oXmlParser->parse($xml);

    $soo_error_code = trim($xml_doc->apierror->code->body);
    $soo_error_dcode = trim($xml_doc->apierror->dcode->body);
    $soo_error_message = trim($xml_doc->apierror->message->body);
    $soo_error_dmessage = trim($xml_doc->apierror->dmessage->body);
    if($soo_error_code || $soo_error_message) return new Object(-1, 'API Error'."\n"."\n".':: Daum Api Error Code ::'."\n".$soo_error_code.' | '.$soo_error_dcode."\n"."\n".':: Message ::'."\n".$soo_error_message."\n".$soo_error_dmessage."\n"."\n".'ref. http://dna.daum.net/apis/Errors');

    $total_result_no = trim($xml_doc->channel->totalcount->body);
    $pageno = trim($xml_doc->channel->pageno->body);
    $result = trim($xml_doc->channel->result->body);
    $total_page = trim($xml_doc->channel->totalcount->body/$result_set);
    
    if($xml_doc->channel->totalcount->body % $result_set != '0') $total_page = intval($total_page) + 1;
    

    if($total_page >= $pageno && $pageno <= '500') $soo_next_page = $pageno + 1;
    else $soo_next_page="-1";

    if($pageno != '1') $soo_before_page = $pageno - 1;
    else $soo_before_page="-1";

    $soo_results = $xml_doc->channel->item;
    if(!is_array($soo_results)) $soo_results = array($soo_results);
    $soo_results_count = count($soo_results);

    for($i=0;$i<$soo_results_count;$i++) {
      $item = $soo_results[$i];
      $soo_list[] = sprintf("%s[***[[[[}[soo]{]]]]***],%s[***[[[[}[soo]{]]]]***],%s[***[[[[}[soo]{]]]]***],%s[***[[[[}[soo]{]]]]***],%s[***[[[[}[soo]{]]]]***],%s",
      trim(strip_tags($item->title->body)),
      trim($item->origin->body),
      trim($item->part_of_speech->body),
      trim(strip_tags($item->description->body)),
      trim($item->exact_yn->body),
      trim($item->link->body));
    }

        $this->add("total_result_no", $total_result_no);
        $this->add("total_page", $total_page);
        $this->add("pageno", $pageno);
        $this->add("result", $result);
        $this->add("result_list_bfpage", $soo_before_page);
        $this->add("result_list_nextpage", $soo_next_page);
        $this->add("result_list", implode("\n", $soo_list));
    }

    /** @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다**/
    function getPopupContent() {
        // 템플릿을 미리 컴파일해서 컴파일된 소스를 return
        $tpl_path = $this->component_path.'tpl';
        $tpl_file = 'popup.html';
        $api_key=trim($this->daum_api_key);
        if(!$api_key) $tpl_file = 'error.html';
        Context::set("tpl_path", $tpl_path);

        $oTemplate = &TemplateHandler::getInstance();
        return $oTemplate->compile($tpl_path, $tpl_file);
    }
}
?>