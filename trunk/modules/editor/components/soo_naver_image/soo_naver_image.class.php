<?php
    /**
     * @class  soo_naver_image
     * @author 민수 <misol221@paran.com>
     * @brief  네이버 이미지 추가 컴포넌트
     **/
class soo_naver_image extends EditorHandler {
    // editor_sequence 는 에디터에서 필수로 달고 다녀야 함
    var $editor_sequence = '0';
    var $component_path = '';
    // openapi 키 값
    var $naver_api_key = '';
    /**
     * @brief editor_sequence과 컴포넌트의 경로를 받음
     **/
    function soo_naver_image($editor_sequence, $component_path) {
        $this->editor_sequence = $editor_sequence;
        $this->component_path = $component_path;
    }
    function search_soo_image() {
        $naver_api_key=trim($this->naver_api_key);
        $soo_img_display_set=trim($this->soo_img_display);
        $sort = urlencode(Context::get('soo_arr'));
        $query = urlencode(trim(Context::get('query')));
        $filter = urlencode(Context::get('filter'));
        $img_start = Context::get('img_start');
        if(!$sort) $sort='sim';
        if(!$filter) $filter='all';
        if(!$soo_img_display_set) $soo_img_display_set='20';
        if(!$img_start) $img_start='1';
        Context::loadLang($this->component_path."lang");
            // 지정된 서버에 요청을 시도한다
            $query_string = sprintf('/search?key=%s&target=image&start=%s&display=%s&filter=%s&sort=%s&query=%s', $naver_api_key, $img_start, $soo_img_display_set, $filter, $sort, $query);
            $fp = fsockopen('openapi.naver.com', 80, $errno, $errstr);
            if(!$fp) return new Object(-1, 'msg_fail_to_socket_open');
            fputs($fp, "GET {$query_string} HTTP/1.0\r\n");
            fputs($fp, "Host: openapi.naver.com\r\n\r\n");
            $buff = '';
            while(!feof($fp)) {
                $str = fgets($fp, 1024);
                if(trim($str)=='') $start = true;
                if($start) $buff .= trim($str);
            }
            fclose($fp);
            $buff = str_replace('<?xml version="1.0" encoding="UTF-8" ?>', '', $buff);
            $buff = trim(str_replace('<rss version="2.0">', '', $buff));
            $oXmlParser = new XmlParser();
            $xml_doc = $oXmlParser->parse($buff);
            $soo_naver_error_code = trim($xml_doc->error->error_code->body);
            $soo_naver_error_message = trim($xml_doc->error->message->body);
            if($soo_naver_error_message) return new Object(-1, 'Error'."\n".'Naver Open Api Error Code : '.$soo_naver_error_code."\n".'Message : '.$soo_naver_error_message);
            $total_img_no = trim($xml_doc->channel->total->body);
            $soo_img_start = trim($xml_doc->channel->start->body);
            $soo_img_display = trim($xml_doc->channel->display->body);
            $soo_img_start_end = trim($soo_img_start.' - '.($soo_img_start+$soo_img_display-1));
            if($total_img_no >= $soo_img_start+$soo_img_display && $soo_img_start+$soo_img_display <= '1000') {
            $soo_next_page=$soo_img_start+$soo_img_display;
            }
            else {
            $soo_next_page="1";
            }
            if($soo_img_start!='1') {
            $soo_before_page=$soo_img_start-$soo_img_display_set;
            }
            else {
            $soo_before_page="1";
            }
            $nvimgs = $xml_doc->channel->item;
            if(!is_array($nvimgs)) $nvimgs = array($nvimgs);
            $nvimgs_count = count($nvimgs);
            $soo_image_list = array();
            for($i=0;$i<$nvimgs_count;$i++) {
                $item = $nvimgs[$i];
                $soo_img_url = trim($item->link->body);
                $soo_img_thumbnail = trim($item->thumbnail->body);
                $soo_list[] = sprintf("%s,soo,%s,soo,%s,soo,%s,soo,%s", trim(str_replace("'", '', str_replace('"' , '&quot;' , str_replace('>' , '&gt;' , str_replace('<' , '&lt;' , str_replace('^' , '&#94;' , str_replace('\\', '\\\\', $item->title->body))))))), $soo_img_url, $soo_img_thumbnail, $item->sizeheight->body, $item->sizewidth->body);
            }
            $this->add("total_img_no", $total_img_no);
            $this->add("img_start", $soo_img_start);
            $this->add("img_start_end", $soo_img_start_end);
            $this->add("image_list_bfpage", $soo_before_page);
            $this->add("image_list_nextpage", $soo_next_page);
            $this->add("image_list", implode("\n", $soo_list));
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
    /**
     * @brief 에디터 컴포넌트가 별도의 고유 코드를 이용한다면 그 코드를 html로 변경하여 주는 method
     *
     * 이미지나 멀티미디어, 설문등 고유 코드가 필요한 에디터 컴포넌트는 고유코드를 내용에 추가하고 나서
     * DocumentModule::transContent() 에서 해당 컴포넌트의 transHtml() method를 호출하여 고유코드를 html로 변경
     **/
    function transHTML($xml_obj) {
        $width = trim($xml_obj->attrs->width);
        $height = trim($xml_obj->attrs->height);
        $src = trim($xml_obj->attrs->src);
        if(substr($src ,0, 2) == './') {
        $src = str_replace('./', Context::getRequestUri(), $src);
        }
        $alt = trim($xml_obj->attrs->alt);
        $title = trim($xml_obj->attrs->title);
        $id = trim($xml_obj->attrs->id);
        $class = trim($xml_obj->attrs->class);
        $style = trim($xml_obj->attrs->style);
        $code = "";
        $img_copy_right = "";
        if($src) {
        $code = $code.' src="'.$src.'"';
        }
        if($width) {
        $code = $code.' width="'.$width.'"';
        }
        if($height) {
        $code = $code.' height="'.$height.'"';
        }
        if($alt) {
        $code = $code.' alt="'.$alt.'"';
        }
        if($title) {
        $code = $code.' title="'.$title.'"';
        }
        if($id) {
        $code = $code.' id="'.$id.'"';
        }
        if($class) {
        $code = $code.' class="'.$class.'"';
        }
        if($style) {
        $code = $code.' style="'.$style.'"';
        }
        if(trim($this->soo_img_copyright) == "Y") {
        $img_copy_right = '<br />This image : Copyright ⓒ NHN Corp.';
        }

        $view_code = sprintf('<img %s />'
        , trim($code));
        return $view_code.$img_copy_right;
    }
    function view_image() {
    $soo_image_type =  trim(Context::get('type'));
    $soo_image_url = trim(str_replace('%3D', '=', str_replace('%3F', '?', str_replace('%2F', '/', urlencode(str_replace('http://', '', Context::get('url').$soo_image_type))))));
    $soo_image_url_arr = explode("/", $soo_image_url);
    $soo_image_url_get = $soo_image_url_arr[0];
    $soo_image_url_pos = 'http://'.$soo_image_url_arr[0];
    include "http.php";
    $url = 'http://'.trim(str_replace('%3D', '=', str_replace('%3F', '?', str_replace('%2F', '/', urlencode(str_replace('http://', '', Context::get('url').$soo_image_type))))));
    if( $url )
    {
            if( strpos($url, $soo_image_url_pos) === 0 )
            $h = new http($soo_image_url_get, 80);
        $h->setTarget(substr($url, strpos($url, "/", 7)));
        $h->sendData();
        echo $h->getBody();
    }
    
    exit();
    }
}
?>