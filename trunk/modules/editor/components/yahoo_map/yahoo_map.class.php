<?php
    /**
     * @class  yahoo_map
     * @author 민수 <misol221@paran.com>
     * @brief  야후 맵 추가 컴포넌트
     **/

class yahoo_map extends EditorHandler {

    // editor_sequence 는 에디터에서 필수로 달고 다녀야 함
    var $editor_sequence = '0';
    var $component_path = '';
    
    // 야후맵 openapi 키 값
    var $yahoo_api_key = '';
    /**
     * @brief editor_sequence과 컴포넌트의 경로를 받음
     **/
    function yahoo_map($editor_sequence, $component_path) {
        $this->editor_sequence = $editor_sequence;
        $this->component_path = $component_path;
    }
    
    function search_address() {
        $yahoo_api_key=trim($this->yahoo_api_key);
        $address = Context::get('address');
        if(!$address) return new Object(-1,'msg_not_exists_addr');
    
        Context::loadLang($this->component_path."lang");
    
        // 지정된 서버에 요청을 시도한다
        $query_string = sprintf('/service/poi.php?appid=%s&q=%s&encoding=utf-8&results=100&output=php', $yahoo_api_key, $address);
    
        $fp = fsockopen('kr.open.gugi.yahoo.com', 80, $errno, $errstr);
        if(!$fp) return new Object(-1, 'msg_fail_to_socket_open');
    
        fputs($fp, "GET {$query_string} HTTP/1.0\r\n");
        fputs($fp, "Host: kr.open.gugi.yahoo.com\r\n\r\n");
    
        $buff = '';
        while(!feof($fp)) {
            $str = fgets($fp, 1024);
            if(trim($str)=='') $start = true;
            if($start) $buff .= trim($str);
        }
    
        fclose($fp);
    
        $buff = trim($buff);
        
        $search_list=unserialize($buff);
        $location_item=$search_list[ResultSet][locations][item];
        $n1='0';
        $n2='0';
        foreach ($location_item as $v1) {
            foreach ($v1 as $v2) {
                $address_list[$n1]=$address_list[$n1].'#minsoo,'.$v2;
            $n2=$n2+1;
            }
        $n1=$n1+1;
        }
        $this->add("address_list", implode("\n", $address_list));
    }
    
    /** @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다**/
    function getPopupContent() {
        // 템플릿을 미리 컴파일해서 컴파일된 소스를 return
        $tpl_path = $this->component_path.'tpl';
        $tpl_file = 'popup.html';
        $yahoo_api_key=trim($this->yahoo_api_key);
        if(!$yahoo_api_key) $tpl_file = 'error.html';
        $yahoo_map_api_base_script=sprintf('<script type="text/javascript" src="http://kr.open.gugi.yahoo.com/Client/AjaxMap.php?v=3.7&appid=%s"></script>', $yahoo_api_key); 
    
        Context::set("tpl_path", $tpl_path);
        Context::addHtmlHeader($yahoo_map_api_base_script);
    
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
        $yahoo_api_key=trim($this->yahoo_api_key);
        if(!$yahoo_api_key) $yahoo_api_key = 'YahooDemo';
        $width = $xml_obj->attrs->width;
        if($width=='') {
         $width = '640px';
        }
        else {
         $width=$width.'px';
        }
        $height = $xml_obj->attrs->height;
        if($height=='') {
         $height = "300px";
        }
        else {
         $height=$height.'px';
        }
        $yahoo_api_key=trim($this->yahoo_api_key);
        // 언어파일을 읽음
        Context::loadLang($this->component_path.'/lang');
        $yahoo_map_count=Context::get('yahoo_map_count');
        if($yahoo_map_count=='') {
        $yahoo_map_count='1';
        }
        else {
        $yahoo_map_count=$yahoo_map_count+'1';
        }
        
        $div_code = sprintf('<div id="yahoo_map%s"></div>'."\n".
        '<script language="javascript" type="text/javascript">//<![CDATA['."\n".
        '<!--'."\n".
        'StartYMap%s();'."\n".
        '//-->'."\n".'//]]></script>'
        , $yahoo_map_count, $yahoo_map_count);
        
        if($yahoo_map_count=='1') {
        $api_script_code=sprintf('<script type="text/javascript" src="http://kr.open.gugi.yahoo.com/Client/AjaxMap.php?v=3.7&appid=%s"></script>',  $yahoo_api_key);        }
        else {
        $api_script_code='';
        }

        $header_script = sprintf(
        '<style type="text/css">'."\n".
        '#yahoo_map%s {'."\n".
        '    width:%s;'."\n".
        '    height:%s;'."\n".
        '}'."\n".
        '</style>'."\n"."\n".
        '<script language="javascript" type="text/javascript">//<![CDATA['."\n".
        '<!--'."\n".
        'var yahoo_map%s;'."\n".
        'function StartYMap%s()'."\n".
        '{'."\n".
        '    // 지도 오브젝트를 생성 합니다.'."\n".
        "    yahoo_map%s = new YMap(document.getElementById('yahoo_map%s'));"."\n"."\n".
        '    yahoo_map%s.addTypeControl();'."\n".
        '    // 지도 확대/축소 콘트롤을 추가합니다.'."\n".
        '    yahoo_map%s.addZoomLong();'."\n".
        '    // 지도 이동 콘트롤을 추가합니다.'."\n".
        '    yahoo_map%s.addPanControl();'."\n"."\n".
        '    // YAHOO_MAP_SAT: 위성지도'."\n".
        '    // YAHOO_MAP_HYB: 하이브리드 지도'."\n".
        '    // YAHOO_MAP_REG: 일반지도'."\n".
        '    yahoo_map%s.setMapType(YAHOO_MAP_REG);'."\n"."\n".
        '    var center_point = new YGeoPoint(%s,%s);'."\n".
        '    yahoo_map%s.drawZoomAndCenter(center_point,3);'."\n".
        '}'."\n".
        '//-->'."\n".'//]]></script>'
        , $yahoo_map_count, $width,  $height, $yahoo_map_count, $yahoo_map_count, $yahoo_map_count, $yahoo_map_count, $yahoo_map_count, $yahoo_map_count, $yahoo_map_count, $yahoo_map_count, $xml_obj->attrs->yahoo_map_y, $xml_obj->attrs->yahoo_map_x, $yahoo_map_count);
    
        $header_script = sprintf('%s'."\n".'%s', $api_script_code, $header_script);
    
        // 결과물 생성
        Context::addHtmlHeader($header_script);
        Context::set('yahoo_map_count' , $yahoo_map_count, $set_to_get_vars=true);
        return $div_code;
    }
}
?>