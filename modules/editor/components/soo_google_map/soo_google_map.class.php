<?php
    /**
     * @class  soo_google_map
     * @author 민수 <misol221@paran.com>
     * @brief  구글 맵 추가 컴포넌트
     이 저작물은 크리에이티브 커먼즈 저작자표시-동일조건변경허락 2.0 대한민국 라이선스에 따라 이용할 수 있습니다.
     이용허락조건을 보려면, http://creativecommons.org/licenses/by-sa/2.0/kr/ 을 클릭하거나,
     크리에이티브 커먼즈 코리아에 문의하세요.
     This work is licensed under the Creative Commons Attribution-Share Alike 2.0 Korea License.
     To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/2.0/kr/
      or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.
     **/
class soo_google_map extends EditorHandler {
    var $editor_sequence = '0';
    var $component_path = '';

    // 구글맵 api 키 값 Google Maps Api Key
    var $soo_google_map_api_key = '';
    /**
     * @brief editor_sequence과 컴포넌트의 경로를 받음
     **/
    function soo_google_map($editor_sequence, $component_path) {
        $this->editor_sequence = $editor_sequence;
        $this->component_path = $component_path;
    }
    /** @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다**/
    function getPopupContent() {
        // 템플릿을 미리 컴파일해서 컴파일된 소스를 return. Compile the popup contents and return it.
        $tpl_path = $this->component_path.'tpl';
        $tpl_file = 'popup.html';
        $soo_google_map_api_key=trim($this->soo_google_map_api_key);
        $this->soo_google_lat=trim($this->soo_google_lat);
        $this->soo_google_lng=trim($this->soo_google_lng);
        $this->soo_google_map_mapcontrol=trim($this->soo_google_map_mapcontrol);
        $this->soo_google_remove_normal_map=trim($this->soo_google_remove_normal_map);
        if($this->soo_google_lat=='') {
          if(Context::getLangType() == 'ko') $this->soo_google_lat = 37.56308554496544;
          else $this->soo_google_lat = 51.5001524;
        }
        if($this->soo_google_lng=='') {
          if(Context::getLangType() == 'ko') $this->soo_google_lng = 126.98796272277832;
          else $this->soo_google_lng = -0.1262362;
        }
        if(!$this->soo_google_map_mapcontrol) {$this->soo_google_map_mapcontrol='Small';}
        if(!$soo_google_map_api_key) $tpl_file = 'error.html';

        $region = '';
        if(Context::getLangType() == 'ko') $region = '.co.kr';
        else $region = '.com';

        $soo_google_map_api_header_script=sprintf('<script type="text/javascript" src="http://maps.google%s/maps?file=api&amp;v=2&amp;key=%s"></script>', $region, $soo_google_map_api_key); 
        $soo_google_map_api_header_script .= '<script type="text/javascript">//<![CDATA['."\n";
        $soo_google_map_api_header_script .= sprintf(
                                                     'var insert_lat="%s";'."\n".
                                                     'var insert_lng="%s";'."\n".
                                                     'var defaultlat="%s";'."\n".
                                                     'var defaultlng="%s";'."\n".
                                                     'function soo_map_set() {'."\n".
                                                     '   map.addControl(new G%sMapControl());'."\n"
                                                     ,$this->soo_insert_lat,$this->soo_insert_lng,$this->soo_google_lat,$this->soo_google_lng,$this->soo_google_map_mapcontrol);
        $soo_google_map_api_header_script .= '}
        //]]>';
        $soo_google_map_api_header_script .= '</script>'."\n";
        Context::set("tpl_path", $tpl_path);
        Context::addHtmlHeader($soo_google_map_api_header_script);
        $oTemplate = &TemplateHandler::getInstance();
        return $oTemplate->compile($tpl_path, $tpl_file);
    }
    /**
     * @brief 에디터 컴포넌트가 별도의 고유 코드를 이용한다면 그 코드를 html로 변경하여 주는 method
     * 이미지나 멀티미디어, 설문등 고유 코드가 필요한 에디터 컴포넌트는 고유코드를 내용에 추가하고 나서
     * DocumentModule::transContent() 에서 해당 컴포넌트의 transHtml() method를 호출하여 고유코드를 html로 변경
     * @brief If editor comp. need to translate the code, this func. would translate it to html.
     * DocumentModule::transContent() would call the transHTML() method.
     **/
    function transHTML($xml_obj) {
      //한 페이지 내에 지도 수
      $map_count=Context::get('google_map_count');
      if(!$map_count) {
        $map_count=1;
      }
      else {
        $map_count=$map_count+1;
      }
      Context::set('google_map_count' , $map_count);

      //지도 표시 시작 start viewing the map.
      $this->soo_google_map_mapcontrol=trim($this->soo_google_map_mapcontrol);
      if(!$this->soo_google_map_mapcontrol) {$this->soo_google_map_mapcontrol='Small';}
      $width = $xml_obj->attrs->width;
      settype($width,"int");
      if(!$width) {$width = 600;}
      $height = $xml_obj->attrs->height;
      settype($height,"int");
      if(!$height) {$height = 400;}

        $region = '';
        if(Context::getLangType() == 'ko') $region = '.co.kr';
        else $region = '.com';

      $header_script = '';
      if($map_count==1) {
        $header_script .= '<script src="http://maps.google'.$region.'/maps?file=api&amp;v=2&amp;key='.trim($this->soo_google_map_api_key).'" type="text/javascript"></script>'."\n";
      }
      if(!$xml_obj->attrs->location_no) { // 단일 위치 지도 one pointed map
        $ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),$xml_obj->attrs->ment);
        $ment = htmlspecialchars($ment);
        $ment = preg_replace('/&lt;br([^&]*)&gt;/i','<br />',$ment);
        $ment = preg_replace('/&lt;hr([^&]*)&gt;/i','<hr />',$ment);
        $ment = preg_replace('/&lt;([a-z]*)&gt;/i','<\\1>',$ment);
        $ment = preg_replace('/&lt;\/([a-z]*)&gt;/i','</\\1>',$ment);
        $ment = eregi_replace('<script>','&lt;script&gt;',$ment);
        $ment = eregi_replace('</script>','&lt;/script&gt;',$ment);
        $ment = eregi_replace('<style>','&lt;style&gt;',$ment);
        $ment = eregi_replace('</style>','&lt;/style&gt;',$ment);
        $lat = trim($xml_obj->attrs->map_lat);
        settype($lat,"float");
        $lng = trim($xml_obj->attrs->map_lng);
        settype($lng,"float");
        $marker_lng = trim($xml_obj->attrs->marker_lng);
        settype($marker_lng,"float");
        $marker_lat = trim($xml_obj->attrs->marker_lat);
        settype($marker_lat,"float");
        $zoom = trim($xml_obj->attrs->map_zoom);
        settype($zoom,"int");

        $header_script .= '<script type="text/javascript">//<![CDATA['."\n".
          '<!--'."\n".
          'function ggl_map_init'.$map_count.'() {'."\n".
          'if (GBrowserIsCompatible()) {'."\n".
          'var ggl_map'.$map_count.' = new GMap2(document.getElementById("ggl_map_canvas'.$map_count.'"))'."\n".
          'ggl_map'.$map_count.'.setCenter(new GLatLng('.$lat.', '.$lng.'), '.$zoom.');'."\n".
          'ggl_map'.$map_count.'.addControl(new G'.$this->soo_google_map_mapcontrol.'MapControl());'."\n".
          'ggl_map'.$map_count.'.addControl(new GMapTypeControl());'."\n".
          'var ggl_markerlatlng'.$map_count.' = new GLatLng('.$marker_lat.', '.$marker_lng.');'."\n".
          'var ggl_marker'.$map_count.' = new GMarker(ggl_markerlatlng'.$map_count.', {draggable: false});'."\n".
          'ggl_map'.$map_count.'.addOverlay(ggl_marker'.$map_count.');'."\n".
          'var ggl_bounds'.$map_count.' = ggl_map'.$map_count.'.getBounds();'."\n".
          'var ggl_southWest'.$map_count.' = ggl_bounds'.$map_count.'.getSouthWest();'."\n".
          'var ggl_northEast'.$map_count.' = ggl_bounds'.$map_count.'.getNorthEast();'."\n".
          'if(ggl_markerlatlng'.$map_count.'.lng()<ggl_southWest'.$map_count.'.lng() || ggl_northEast'.$map_count.'.lng()<ggl_markerlatlng'.$map_count.'.lng()) {'."\n".
          'ggl_map'.$map_count.'.removeOverlay(ggl_marker'.$map_count.');'."\n".
          'ggl_markerlatlng'.$map_count.' = ggl_map'.$map_count.'.getCenter();'."\n".
          'ggl_marker'.$map_count.' = new GMarker(ggl_markerlatlng'.$map_count.', {draggable: false});'."\n".
          'ggl_map'.$map_count.'.addOverlay(ggl_marker'.$map_count.');'."\n".
          '}'."\n".
          'if(ggl_markerlatlng'.$map_count.'.lat()<ggl_southWest'.$map_count.'.lat() || ggl_northEast'.$map_count.'.lat()<ggl_markerlatlng'.$map_count.'.lat()) {'."\n".
          'ggl_map'.$map_count.'.removeOverlay(ggl_marker'.$map_count.');'."\n".
          'ggl_markerlatlng'.$map_count.' = ggl_map'.$map_count.'.getCenter();'."\n".
          'ggl_marker'.$map_count.' = new GMarker(ggl_markerlatlng'.$map_count.', {draggable: false});'."\n".
          'ggl_map'.$map_count.'.addOverlay(ggl_marker'.$map_count.');'."\n".
          '}'."\n";
        if($ment) {
          $header_script .= 'ggl_marker'.$map_count.'.openInfoWindowHtml("'.$ment.'");'."\n";
        }
        $header_script .= '}'."\n".'}'."\n".'//-->'."\n".'//]]>'."\n".'</script>';
        Context::addHtmlHeader($header_script);
      }
      else { // 다중 위치 지도 map of numerous point
        settype($xml_obj->attrs->location_no,"int");
        $header_script .= '<script type="text/javascript">//<![CDATA['."\n".
          '<!--'."\n".
          'function ggl_map_init'.$map_count.'() {'."\n".
          'if (GBrowserIsCompatible()) {'."\n".
          'var ggl_map'.$map_count.' = new GMap2(document.getElementById("ggl_map_canvas'.$map_count.'"))'."\n".
          'ggl_map'.$map_count.'.addControl(new G'.$this->soo_google_map_mapcontrol.'MapControl());'."\n".
          'ggl_map'.$map_count.'.addControl(new GMapTypeControl());'."\n";
        for($i=0;$i<$xml_obj->attrs->location_no;$i++) {
          $ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),$xml_obj->attrs->{'ment'.$i});
          $ment = htmlspecialchars($ment);
          $ment = preg_replace('/&lt;br([^&]*)&gt;/i','<br />',$ment);
          $ment = preg_replace('/&lt;hr([^&]*)&gt;/i','<hr />',$ment);
          $ment = preg_replace('/&lt;([a-z]*)&gt;/i','<\\1>',$ment);
          $ment = preg_replace('/&lt;\/([a-z]*)&gt;/i','</\\1>',$ment);
          $ment = eregi_replace('<script>','&lt;script&gt;',$ment);
          $ment = eregi_replace('</script>','&lt;/script&gt;',$ment);
          $ment = eregi_replace('<style>','&lt;style&gt;',$ment);
          $ment = eregi_replace('</style>','&lt;/style&gt;',$ment);
          $lat = trim($xml_obj->attrs->{'map_lat'.$i});
          settype($lat,"float");
          $lng = trim($xml_obj->attrs->{'map_lng'.$i});
          settype($lng,"float");
          $marker_lng = trim($xml_obj->attrs->{'marker_lng'.$i});
          settype($marker_lng,"float");
          $marker_lat = trim($xml_obj->attrs->{'marker_lat'.$i});
          settype($marker_lat,"float");
          $zoom = trim($xml_obj->attrs->{'map_zoom'.$i});
          settype($zoom,"int");
          if(!$lat || !$lng || !$marker_lng || !$marker_lat || !$zoom) {
            return 'f';
            break;
          }
          if($i==0) {
            $header_script .= 'ggl_map'.$map_count.'.setCenter(new GLatLng('.$lat.', '.$lng.'), '.$zoom.');'."\n";
          }
          $header_script .= 'var ggl_markerlatlng'.$map_count.'_'.$i.' = new GLatLng('.$marker_lat.', '.$marker_lng.');'."\n".
            'var ggl_marker'.$map_count.'_'.$i.' = new GMarker(ggl_markerlatlng'.$map_count.'_'.$i.', {draggable: false});'."\n".
            'ggl_map'.$map_count.'.addOverlay(ggl_marker'.$map_count.'_'.$i.');'."\n";
          if($ment) {
            $header_script .= 'GEvent.addListener(ggl_marker'.$map_count.'_'.$i.', \'click\', function(){'."\n".
            'ggl_map'.$map_count.'.setZoom('.$zoom.');'."\n".
            'ggl_map'.$map_count.'. panTo(new GLatLng('.$lat.', '.$lng.'));'."\n".
            'ggl_marker'.$map_count.'_'.$i.'.openInfoWindowHtml("'.$ment.'"); '."\n".
            '});'."\n".
            'ggl_map'.$map_count.'.addOverlay(ggl_marker'.$map_count.'_'.$i.');'."\n";
          }
        }
        $header_script .= '}'."\n".'}'."\n".'//-->'."\n".'//]]>'."\n".'</script>';
        Context::addHtmlHeader($header_script);
      }
        $view_code = '<div id="ggl_map_canvas'.$map_count.'" style="width: '.$width.'px; height: '.$height.'px"></div>'."\n".
            '<script language="javascript" type="text/javascript">//<![CDATA['."\n".
            '<!--'."\n".
            'xAddEventListener(window, "load", ggl_map_init'.$map_count.');'."\n".
            'xAddEventListener(window, "load", function() { setTimeout(ggl_map_init'.$map_count.',1501); });'."\n".
            '//-->'."\n".'//]]></script>';
        return $view_code;
    }
}
?>
