<?php
    /**
     * @class  soo_naver_shop
     * @author 민수 <misol221@paran.com>
     * @brief  네이버 상품정보 입력 컴포넌트
     **/

class soo_naver_shop extends EditorHandler {

    // editor_sequence 는 에디터에서 필수로 달고 다녀야 함
    var $editor_sequence = '0';
    var $component_path = '';

    // openapi 키 값
    var $naver_api_key = '';
    /**
     * @brief editor_sequence과 컴포넌트의 경로를 받음
     **/
    function soo_naver_shop($editor_sequence, $component_path) {
        $this->editor_sequence = $editor_sequence;
        $this->component_path = $component_path;
    }

    function soo_search() {
        $naver_api_key=trim($this->naver_api_key);
        $soo_display_set=trim($this->soo_shop_display);
        $q_sort = urlencode(trim(Context::get('q_sort')));
        $query = urlencode(trim(Context::get('query')));
        $soo_result_start = urlencode(Context::get('soo_result_start'));

        if(!$soo_display_set) $soo_display_set='20';
        if(!$soo_result_start) $soo_result_start='1';
        $query_string = sprintf('/search?key=%s&target=shop&start=%s&display=%s&sort=%s&query=%s', $naver_api_key, $soo_result_start, $soo_display_set, $q_sort, $query);

        $fp = fsockopen('openapi.naver.com', 80, $errno, $errstr);
        if(!$fp) return new Object(-1, Context::getLang('msg_fail_to_socket_open'));
        fputs($fp, "GET {$query_string} HTTP/1.0\r\n");
        fputs($fp, "Host: openapi.naver.com\r\n\r\n");

        $buff = '';
        while(!feof($fp)) {
            $str = fgets($fp, 1024);
            if(trim($str)=='') $start = true;
            if($start) $buff .= trim($str);
        }

        fclose($fp);

        $buff = explode('<channel', $buff);
        if($buff[1]) {
            $buff = '<channel'.$buff[1];
        }
        else {
            $buff = $buff[0];
        }

        $oXmlParser = new XmlParser();
        $xml_doc = $oXmlParser->parse($buff);

        $soo_naver_error_code = trim($xml_doc->error->error_code->body);
        $soo_naver_error_message = trim($xml_doc->error->message->body);
        if($soo_naver_error_message) return new Object(-1, 'Error'."\n".'Naver Open Api Error Code : '.$soo_naver_error_code."\n".'Message : '.$soo_naver_error_message);

        $total_result_no = trim($xml_doc->channel->total->body);
        $soo_result_start = trim($xml_doc->channel->start->body);
        $soo_search_display = trim($xml_doc->channel->display->body);

        if($total_result_no >= $soo_result_start+$soo_search_display && $soo_result_start+$soo_search_display <= '1000') {
        $soo_next_page=$soo_result_start+$soo_display_set;
        }
        else {
        $soo_next_page="1";
        }
        if($soo_result_start!='1') {
        $soo_before_page=$soo_result_start-$soo_display_set;
        }
        else {
        $soo_before_page="1";
        }

        $soo_results = $xml_doc->channel->item;
        if(!is_array($soo_results)) $soo_results = array($soo_results);
        $soo_results_count = count($soo_results);
        $soo_result_start_end = trim($soo_result_start.' - '.($soo_result_start+$soo_results_count-1));
        $soo_list = array();
        for($i=0;$i<$soo_results_count;$i++) {
            $item = $soo_results[$i];
            $soo_list[] = sprintf("%s,soo,%s,soo,%s,soo,%s",
                trim(str_replace(array('<b>','</b>','"'),array('','','&quot;'),$item->title->body)),
                trim($item->link->body),
                trim($item->image->body),
                trim($item->lprice->body),
                trim($item->hprice->body));
        }

        $this->add("total_result_no", $total_result_no);
        $this->add("soo_result_start", $soo_result_start);
        $this->add("soo_result_start_end", $soo_result_start_end);
        $this->add("result_list_bfpage", $soo_before_page);
        $this->add("result_list_nextpage", $soo_next_page);
        $this->add("result_list", implode("\n", $soo_list));
    }

    /** @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다**/
    function getPopupContent() {
        // 템플릿을 미리 컴파일해서 컴파일된 소스를 return
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