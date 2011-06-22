<?php
/**
 * @class	soo_google_map
 * @author 민수 <misol221@paran.com>
 * @brief	구글 맵 추가 컴포넌트
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
	var $mobile_set = false;

	/**
	 * @brief editor_sequence과 컴포넌트의 경로를 받음
	 **/
	function soo_google_map($editor_sequence, $component_path) {
		$this->editor_sequence = $editor_sequence;
		$this->component_path = $component_path;
		Context::loadLang($component_path.'lang');
		if(class_exists('Mobile')) {
			if(Mobile::isFromMobilePhone()) {
				$this->mobile_set = true;
			}
		}
	}

	function xml_api_request($uri, $headers = null) {
		$xml = '';
		$xml = FileHandler::getRemoteResource($uri, null, 3, 'GET', 'application/xml', $headers);

		$xml = preg_replace("/<\?xml([.^>]*)\?>/i", "", $xml);

		$oXmlParser = new XmlParser();
		$xml_doc = $oXmlParser->parse($xml);

		return $xml_doc;
	}

	function search() {
		$address = Context::get('address');
		if(!$address || (!$this->soo_naver_map_api_key && !$this->soo_yahoo_map_api_key)) return;

		if($this->soo_yahoo_map_api_key) {
			$uri = sprintf('http://kr.open.gugi.yahoo.com/service/poi.php?appid=%s&encoding=utf-8&q=%s&results=100',$this->soo_yahoo_map_api_key,urlencode($address));
			$xml_doc = $this->xml_api_request($uri);

			$item = $xml_doc->resultset->locations->item;
			if(!is_array($item)) $item = array($item);
			$item_count = count($item);

			if($item_count) {
				for($i=0;$i<$item_count;$i++) {
					$input_obj = '';
					$input_obj = $item[$i];
					$result[$i]->formatted_address = $input_obj->state->body.' '.$input_obj->county->body.' '.$input_obj->city->body.' '.$input_obj->street->body.' '.$input_obj->name->body;
					$result[$i]->geometry->lat = $input_obj->latitude->body;
					$result[$i]->geometry->lng = $input_obj->longitude->body;
					$result[$i]->result_from = 'Yahoo';
				}

				$this->add("results", $result);
			}
		}
		if($this->soo_naver_map_api_key) {
			$uri = sprintf('http://map.naver.com/api/geocode.php?key=%s&encoding=utf-8&coord=latlng&query=%s',$this->soo_naver_map_api_key,urlencode($address));
			$xml_doc = $this->xml_api_request($uri);

			$item = $xml_doc->geocode->item;
			if(!is_array($item)) $item = array($item);
			$item_count = count($item);

			if($item_count) {
				for($i=0;$i<$item_count;$i++) {
					$input_obj = '';
					$input_obj = $item[$i];
					$result[$i]->formatted_address = $input_obj->address->body;
					$result[$i]->geometry->lng = $input_obj->point->x->body;
					$result[$i]->geometry->lat = $input_obj->point->y->body;
					$result[$i]->result_from = 'Naver';
				}

				$this->add("results", $result);
			}
		}
	}
	/** @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다**/
	function getPopupContent() {
		// 템플릿을 미리 컴파일해서 컴파일된 소스를 return. Compile the popup contents and return it.
		$tpl_path = $this->component_path.'tpl';
		$tpl_file = 'popup.html';
		$this->soo_google_lat=trim($this->soo_google_lat);
		$this->soo_google_lng=trim($this->soo_google_lng);
		$this->soo_google_remove_normal_map=trim($this->soo_google_remove_normal_map);
		if($this->soo_google_lat=='') {
			if(Context::getLangType() == 'ko') $this->soo_google_lat = 37.56308554496544;
			else $this->soo_google_lat = 51.5001524;
		}
		if($this->soo_google_lng=='') {
			if(Context::getLangType() == 'ko') $this->soo_google_lng = 126.98796272277832;
			else $this->soo_google_lng = -0.1262362;
		}
		if($this->soo_naver_map_api_key || $this->soo_yahoo_map_api_key) $naverapi_check = 1;
		else $naverapi_check = 0;

		$region = '';
		if(Context::getLangType() == 'ko') $region = '.co.kr';
		else $region = '.com';

		$soo_google_map_api_header_script = '<script type="text/javascript" src="http://maps.google'.$region.'/maps/api/js?sensor=true"></script>'; 
		$soo_google_map_api_header_script .= '<script type="text/javascript">//<![CDATA['."\n";
		$soo_google_map_api_header_script .= sprintf(
			'var insert_lat="%s";'."\n".
			'var insert_lng="%s";'."\n".
			'var defaultlat="%s";'."\n".
			'var defaultlng="%s";'."\n".
			'var naver_api_check="%s";'."\n".
			'function soo_map_set() {'."\n".
			''."\n"
			,$this->soo_insert_lat,$this->soo_insert_lng,$this->soo_google_lat,$this->soo_google_lng, $naverapi_check);
		$soo_google_map_api_header_script .= '}'."\n".'//]]>';
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
		// RSS, 모바일용 대체링크 문자열
		$altMapLinkParas = Context::getRequestUri().'?module=editor&component=soo_google_map&act=procEditorCall&method=altViewGMap';

		//한 페이지 내에 지도 수
		$map_count=Context::get('google_map_count');
		if(!$map_count) {
			$map_count=1;
		} else {
			$map_count=$map_count+1;
		}
		Context::set('google_map_count' , $map_count);

		//지도 표시 시작 start viewing the map.
		$width = $xml_obj->attrs->width;
		settype($width,"int");
		if(!$width) {$width = 600;}
		$height = $xml_obj->attrs->height;
		settype($height,"int");
		if(!$height) {$height = 400;}

		$region = '';
		if(Context::getLangType() == 'ko') $region = '.co.kr';
		else $region = '.com';

		$header_script = '<style type="text/css"> .soo_google_map_ment { background-color: #ffffff; color: #000000; } </style>';
		if($map_count==1) {
			$header_script .= '<script type="text/javascript" src="http://maps.google'.$region.'/maps/api/js?sensor=true"></script>'."\n";
		}
		if(!$xml_obj->attrs->location_no) { // 단일 위치 지도 one pointed map
			$ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),$xml_obj->attrs->ment);
			if($ment) {
				$ment = htmlspecialchars($ment);
				$ment = preg_replace('/&lt;br([^&]*)&gt;/i','<br />',$ment);
				$ment = preg_replace('/&lt;hr([^&]*)&gt;/i','<hr />',$ment);
				$ment = preg_replace('/&lt;([a-z]*)&gt;/i','<\\1>',$ment);
				$ment = preg_replace('/&lt;\/([a-z]*)&gt;/i','</\\1>',$ment);
				$ment = eregi_replace('<script>','&lt;script&gt;',$ment);
				$ment = eregi_replace('</script>','&lt;/script&gt;',$ment);
				$ment = eregi_replace('<style>','&lt;style&gt;',$ment);
				$ment = eregi_replace('</style>','&lt;/style&gt;',$ment);
			}
			if($ment) $ment = sprintf("<div class=\\\"soo_google_map_ment\\\">%s</div>",$ment);
			
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

			$altMapLinkParas .= sprintf('&amp;location_no=1&amp;ment=%s&amp;map_lat=%s&amp;map_lng=%s&amp;marker_lng=%s&amp;marker_lat=%s&amp;map_zoom=%s',urlencode($xml_obj->attrs->{'ment'}),$lat,$lng,$marker_lng,$marker_lat,$zoom);

			$header_script .= '<script type="text/javascript">//<![CDATA['."\n".
				'<!--'."\n".
				'function ggl_map_init'.$map_count.'() {'."\n".
					'var mapOption = { zoom: '.$zoom.', mapTypeId: google.maps.MapTypeId.ROADMAP }'."\n".
					'var ggl_map'.$map_count.' = new google.maps.Map(document.getElementById("ggl_map_canvas'.$map_count.'"), mapOption);'."\n".
					'ggl_map'.$map_count.'.setCenter(new google.maps.LatLng('.$lat.', '.$lng.'));'."\n".
					'var ggl_markerlatlng'.$map_count.' = new google.maps.LatLng('.$marker_lat.', '.$marker_lng.');'."\n".
					'var ggl_marker'.$map_count.' = new google.maps.Marker({ position: ggl_markerlatlng'.$map_count.', map: ggl_map'.$map_count.', draggable: false});'."\n".
					'ggl_marker'.$map_count.'.setMap(ggl_map'.$map_count.');'."\n";
				if($ment) {
					$header_script .= 'infowindow = new google.maps.InfoWindow({content: "'.$ment.'", disableAutoPan: true}).open(ggl_map'.$map_count.',ggl_marker'.$map_count.')'."\n";
				}
				$header_script .= '}'."\n".'//-->'."\n".'//]]>'."\n".'</script>';
			Context::addHtmlHeader($header_script);
		} else { // 다중 위치 지도 map of numerous point
			settype($xml_obj->attrs->location_no,"int");
			$altMapLinkParas .= '&amp;location_no='.$xml_obj->attrs->location_no;
			$header_script .= '<script type="text/javascript">//<![CDATA['."\n".
				'<!--'."\n".
				'function ggl_map_init'.$map_count.'() {'."\n".
					'var mapOption = { zoom:8, mapTypeId: google.maps.MapTypeId.ROADMAP }'."\n".
					'var infowindow = new google.maps.InfoWindow({content: ""}); var ggl_map'.$map_count.' = new google.maps.Map(document.getElementById("ggl_map_canvas'.$map_count.'"), mapOption);'."\n";
				for($i=0;$i<$xml_obj->attrs->location_no;$i++) {
					$ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),$xml_obj->attrs->{'ment'.$i});
					if($ment) {
						$ment = htmlspecialchars($ment);
						$ment = preg_replace('/&lt;br([^&]*)&gt;/i','<br />',$ment);
						$ment = preg_replace('/&lt;hr([^&]*)&gt;/i','<hr />',$ment);
						$ment = preg_replace('/&lt;([a-z]*)&gt;/i','<\\1>',$ment);
						$ment = preg_replace('/&lt;\/([a-z]*)&gt;/i','</\\1>',$ment);
						$ment = eregi_replace('<script>','&lt;script&gt;',$ment);
						$ment = eregi_replace('</script>','&lt;/script&gt;',$ment);
						$ment = eregi_replace('<style>','&lt;style&gt;',$ment);
						$ment = eregi_replace('</style>','&lt;/style&gt;',$ment);
					}
					if($ment) $ment = sprintf("<div class=\\\"soo_google_map_ment\\\">%s</div>",$ment);
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

					$altMapLinkParas .= sprintf('&amp;ment%d=%s&amp;map_lat%d=%s&amp;map_lng%d=%s&amp;marker_lng%d=%s&amp;marker_lat%d=%s&amp;map_zoom%d=%s',$i,urlencode($xml_obj->attrs->{'ment'.$i}),$i,$lat,$i,$lng,$i,$marker_lng,$i,$marker_lat,$i,$zoom);

					if($i==0) {
						$header_script .= 'ggl_map'.$map_count.'.setCenter(new google.maps.LatLng('.$lat.', '.$lng.'));'."\n".'ggl_map'.$map_count.'.setZoom('.$zoom.');'."\n";
					}
					$header_script .= 'var ggl_markerlatlng'.$map_count.'_'.$i.' = new google.maps.LatLng('.$marker_lat.', '.$marker_lng.');'."\n".
						'var ggl_marker'.$map_count.'_'.$i.' = new google.maps.Marker({ position: ggl_markerlatlng'.$map_count.'_'.$i.', map: ggl_map'.$map_count.', draggable: false});'."\n".
						'ggl_marker'.$map_count.'_'.$i.'.setMap(ggl_map'.$map_count.');'."\n";

					$header_script .= 'google.maps.event.addListener(ggl_marker'.$map_count.'_'.$i.', \'click\', function(){'."\n".
						'ggl_map'.$map_count.'.setZoom('.$zoom.');'."\n".
						'ggl_map'.$map_count.'.panTo(new google.maps.LatLng('.$lat.', '.$lng.'));'."\n".
						'infowindow.close();'."\n";
					if($ment) {
						$header_script .= 'infowindow = new google.maps.InfoWindow({content: "'.$ment.'"});'."\n".
						'infowindow.open(ggl_map'.$map_count.',ggl_marker'.$map_count.'_'.$i.');'."\n";
					}
					$header_script .=  '});'."\n".'ggl_marker'.$map_count.'_'.$i.'.setMap(ggl_map'.$map_count.');'."\n";
				}
				$header_script .= '}'."\n".'//-->'."\n".'//]]>'."\n".'</script>';
			Context::addHtmlHeader($header_script);
		}

		if(Context::getResponseMethod() != 'HTML' || $this->mobile_set == true) {
			$style = 'text-align:center; width: 100%; margin:15px 0px;';
			$view_code = '<div style="'.$style.'"><a href="'.$altMapLinkParas.'" target="_blank"><img src="'.
			$this->getImageMapLink(($lat.','.$lng), ($marker_lat.','.$marker_lng), $zoom, $width, $height).'" /><br />'.Context::getLang('view_map').'</a></div>';
		} else {
			$view_code = '<div id="ggl_map_canvas'.$map_count.'" style="width: '.$width.'px; height: '.$height.'px"></div>'."\n".
				'<script language="javascript" type="text/javascript">//<![CDATA['."\n".
				'<!--'."\n".
				'jQuery(window).load(function() { ggl_map_init'.$map_count.'(); });'."\n".
				'//-->'."\n".'//]]></script>'."\n";
		}
		return $view_code;
	}

	function altViewGMap() {
		$this->mobile_set = false;
		if(class_exists('Mobile')) {
			if(Mobile::isMobileCheckByAgent()) {
				$this->mobile_set = true;
			}
		}

		if($this->mobile_set == true) {
			return $this->viewImageMap();
		} else {
			return $this->viewScriptMap();
		}
	}
	
	function viewScriptMap() {
		// 모바일 및 RSS용 페이지 필요.
		$header_script = '';
		$region = '';
		if(Context::getLangType() == 'ko') $region = '.co.kr';
		else $region = '.com';

		$header_script .= '<script type="text/javascript" src="http://maps.google'.$region.'/maps/api/js?sensor=true"></script>'."\n";
		$location_no = intval(Context::get('location_no'));
		if($location_no>1) {
			$header_script .= '<script type="text/javascript">//<![CDATA['."\n".
				'<!--'."\n".
				'function ggl_map_init() {'."\n".
					'var mapOption = { zoom:8, mapTypeId: google.maps.MapTypeId.ROADMAP }'."\n".
					'var infowindow = new google.maps.InfoWindow({content: ""}); var ggl_map = new google.maps.Map(document.getElementById("ggl_map_canvas"), mapOption);'."\n";
			for($i=0;$i<$location_no;$i++) {
				$ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),Context::get('ment'.$i));
				if($ment) {
					$ment = htmlspecialchars($ment);
					$ment = preg_replace('/&lt;br([^&]*)&gt;/i','<br />',$ment);
					$ment = preg_replace('/&lt;hr([^&]*)&gt;/i','<hr />',$ment);
					$ment = preg_replace('/&lt;([a-z]*)&gt;/i','<\\1>',$ment);
					$ment = preg_replace('/&lt;\/([a-z]*)&gt;/i','</\\1>',$ment);
					$ment = eregi_replace('<script>','&lt;script&gt;',$ment);
					$ment = eregi_replace('</script>','&lt;/script&gt;',$ment);
					$ment = eregi_replace('<style>','&lt;style&gt;',$ment);
					$ment = eregi_replace('</style>','&lt;/style&gt;',$ment);
				}
				if($ment) $ment = sprintf("<div class=\\\"soo_google_map_ment\\\">%s</div>",$ment);
				$lat = trim(Context::get('map_lat'.$i));
				settype($lat,"float");
				$lng = trim(Context::get('map_lng'.$i));
				settype($lng,"float");
				$marker_lng = trim(Context::get('marker_lng'.$i));
				settype($marker_lng,"float");
				$marker_lat = trim(Context::get('marker_lat'.$i));
				settype($marker_lat,"float");
				$zoom = trim(Context::get('map_zoom'.$i));
				settype($zoom,"int");
				if(!$lat || !$lng || !$marker_lng || !$marker_lat || !$zoom) {
					return 'f';
					break;
				}
				if($i==0) {
					$header_script .= 'ggl_map.setCenter(new google.maps.LatLng('.$lat.', '.$lng.'));'."\n".'ggl_map.setZoom('.$zoom.');'."\n";
				}
				$header_script .= 'var ggl_markerlatlng_'.$i.' = new google.maps.LatLng('.$marker_lat.', '.$marker_lng.');'."\n".
					'var ggl_marker_'.$i.' = new google.maps.Marker({ position: ggl_markerlatlng_'.$i.', map: ggl_map, draggable: false});'."\n".
					'ggl_marker_'.$i.'.setMap(ggl_map);'."\n";

				$header_script .= 'google.maps.event.addListener(ggl_marker_'.$i.', \'click\', function(){'."\n".
					'ggl_map.setZoom('.$zoom.');'."\n".
					'ggl_map.panTo(new google.maps.LatLng('.$lat.', '.$lng.'));'."\n".
					'infowindow.close();'."\n";
				if($ment) {
					$header_script .= 'infowindow = new google.maps.InfoWindow({content: "'.$ment.'"});'."\n".
					'infowindow.open(ggl_map,ggl_marker_'.$i.');'."\n";
				}
				$header_script .=  '});'."\n".'ggl_marker_'.$i.'.setMap(ggl_map);'."\n";
			}
			$header_script .= '}'."\n".'//-->'."\n".'//]]>'."\n".'</script>';

		} else {
			$ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),Context::get('ment'));
			if($ment) {
				$ment = htmlspecialchars($ment);
				$ment = preg_replace('/&lt;br([^&]*)&gt;/i','<br />',$ment);
				$ment = preg_replace('/&lt;hr([^&]*)&gt;/i','<hr />',$ment);
				$ment = preg_replace('/&lt;([a-z]*)&gt;/i','<\\1>',$ment);
				$ment = preg_replace('/&lt;\/([a-z]*)&gt;/i','</\\1>',$ment);
				$ment = eregi_replace('<script>','&lt;script&gt;',$ment);
				$ment = eregi_replace('</script>','&lt;/script&gt;',$ment);
				$ment = eregi_replace('<style>','&lt;style&gt;',$ment);
				$ment = eregi_replace('</style>','&lt;/style&gt;',$ment);
			}
			if($ment) $ment = sprintf("<div class=\\\"soo_google_map_ment\\\">%s</div>",$ment);
			$lat = trim(Context::get('map_lat'));
			settype($lat,"float");
			$lng = trim(Context::get('map_lng'));
			settype($lng,"float");
			$marker_lng = trim(Context::get('marker_lng'));
			settype($marker_lng,"float");
			$marker_lat = trim(Context::get('marker_lat'));
			settype($marker_lat,"float");
			$zoom = trim(Context::get('map_zoom'));
			settype($zoom,"int");

			$header_script .= '<script type="text/javascript">//<![CDATA['."\n".
				'//<!--'."\n".
				'function ggl_map_init() {'."\n".
					'var mapOption = { zoom: '.$zoom.', mapTypeId: google.maps.MapTypeId.ROADMAP }'."\n".
					'var ggl_map = new google.maps.Map(document.getElementById("ggl_map_canvas"), mapOption);'."\n".
					'ggl_map.setCenter(new google.maps.LatLng('.$lat.', '.$lng.'));'."\n".
					'var ggl_markerlatlng = new google.maps.LatLng('.$marker_lat.', '.$marker_lng.');'."\n".
					'var ggl_marker = new google.maps.Marker({ position: ggl_markerlatlng, map: ggl_map, draggable: false});'."\n".
					'var infowindow = new google.maps.InfoWindow({ content: \'\' });'."\n".
					'ggl_marker.setMap(ggl_map);'."\n";
				if($ment) {
					$header_script .= 'infowindow = new google.maps.InfoWindow({content: "'.$ment.'", disableAutoPan: true}).open(ggl_map,ggl_marker);'."\n";
				}
				$header_script .= '}'."\n".'//-->'."\n".'//]]>'."\n".'</script>';
		}
		$view_code = '<div id="ggl_map_canvas"></div>'."\n";

		header("Content-Type: text/html; charset=UTF-8");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Set-Cookie: ");
		print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"'."\n".
			'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n".
			'<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">'."\n".
			'<head>'."\n".
			'<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />'."\n".
			'<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>'."\n".
			'<style type="text/css">'."\n".
			'html { height: 100%; }'."\n".
			'body { height: 100%; margin: 0px; padding: 0px }'."\n".
			'#ggl_map_canvas { height: 100% }'."\n".
			'</style>'."\n".
			$header_script."\n".
			'<title></title>'."\n".
			'</head>'."\n".
			'<body onload="ggl_map_init();">'."\n".
			$view_code."\n".
			'</body>'."\n".
			'</html>';
		exit();

	}

	function viewImageMap() {
		header("Content-Type: text/html; charset=UTF-8");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Set-Cookie: ");
		print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"'."\n".
			'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'."\n".
			'<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko"><head><meta http-equiv="Content-type" content="text/html;charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><style type="text/css">'."\n".
			'html { height: 100%; }body { height: 100%; margin: 0px; padding: 0px }</style><title></title></head><body>';

		// 모바일용 페이지 필요.
		$location_no = Context::get('location_no');
		if($location_no>1) {
			for($i=0;$i<$location_no;$i++) {
				$ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),Context::get('ment'.$i));
				if($ment) {
					$ment = htmlspecialchars($ment);
				}

				$lat = trim(Context::get('map_lat'.$i));
				settype($lat,"float");
				$lng = trim(Context::get('map_lng'.$i));
				settype($lng,"float");
				$marker_lng = trim(Context::get('marker_lng'.$i));
				settype($marker_lng,"float");
				$marker_lat = trim(Context::get('marker_lat'.$i));
				settype($marker_lat,"float");
				$zoom = trim(Context::get('map_zoom'.$i));
				settype($zoom,"int");
				if(!$lat || !$lng || !$marker_lng || !$marker_lat || !$zoom) {
					break;
				}

				$image_src = $this->getImageMapLink(($lat.','.$lng), ($marker_lat.','.$marker_lng), $zoom);
				$map_href = $this->getGoogleMapLink(($lat.','.$lng), ($marker_lat.','.$marker_lng), $zoom, $ment);

				if($ment) $ment = sprintf("<h2>%s</h2>",$ment);

				print '<div>'.'<a href="'.$map_href.'">'.$ment.'<img src="'.$image_src.'" alt="Google Maps" /></a></div><hr />';

			}

		} else {
			$lat = trim(Context::get('map_lat'.$i));
			settype($lat,"float");
			$lng = trim(Context::get('map_lng'.$i));
			settype($lng,"float");
			$marker_lng = trim(Context::get('marker_lng'.$i));
			settype($marker_lng,"float");
			$marker_lat = trim(Context::get('marker_lat'.$i));
			settype($marker_lat,"float");
			$zoom = trim(Context::get('map_zoom'.$i));
			settype($zoom,"int");
			$ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),Context::get('ment'));
			if($ment) {
				$ment = htmlspecialchars($ment);
			}
			// 파라미터 입력
			$image_src = $this->getImageMapLink(($lat.','.$lng), ($marker_lat.','.$marker_lng), $zoom);
			$map_href = $this->getGoogleMapLink(($lat.','.$lng), ($marker_lat.','.$marker_lng), $zoom, $ment);

			$ment = str_replace(array('[[STS[[',']]STS]]','[[STS_EQ]]'),array('<','>','='),Context::get('ment'));
			if($ment) {
				$ment = htmlspecialchars($ment);
				$ment = preg_replace('/&lt;br([^&]*)&gt;/i','<br />',$ment);
				$ment = preg_replace('/&lt;hr([^&]*)&gt;/i','<hr />',$ment);
				$ment = preg_replace('/&lt;([a-z]*)&gt;/i','<\\1>',$ment);
				$ment = preg_replace('/&lt;\/([a-z]*)&gt;/i','</\\1>',$ment);
				$ment = eregi_replace('<script>','&lt;script&gt;',$ment);
				$ment = eregi_replace('</script>','&lt;/script&gt;',$ment);
				$ment = eregi_replace('<style>','&lt;style&gt;',$ment);
				$ment = eregi_replace('</style>','&lt;/style&gt;',$ment);
			}
			if($ment) $ment = sprintf("<h2>%s</h2>",$ment);

			print '<div>'.'<a href="'.$map_href.'">'.$ment.'<img src="'.$image_src.'" alt="Google Maps" /></a></div><hr />';

		}

		print '<address><a href="http://www.xpressengine.com/">XE</a> Editor Component by MinSoo Kim(<a href="http://twitter.com/misol221">@misol221</a>) using Google Maps API.</address></body></html>';
		exit();

	}

	function getImageMapLink($center, $marker, $zoom, $width=320, $height=400) {
		return sprintf("http://maps.google.com/maps/api/staticmap?center=%s&zoom=%s&size=%sx%s&markers=size:mid|%s&sensor=false", $center, $zoom, intval($width), intval($height), $marker);
	}

	function getGoogleMapLink($center, $marker, $zoom, $ment = '') {
		return sprintf("http://maps.google.com?ll=%s&z=%s&q=%s@%s&iwloc=A", $center, $zoom, urlencode($ment), $marker);
	}

}
?>