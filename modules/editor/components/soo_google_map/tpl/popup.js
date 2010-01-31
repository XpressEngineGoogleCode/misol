var map_zoom = 13;
var map_lat = '';
var map_lng = '';
var marker_latlng = '';
var marker_ment = '';
var map = '';
var marker = '';
var saved_location = new Array();
var result_array = new Array();
function soo_save_location(i,j) { //위치 정보를 배열로 저장
  i = parseInt(i,10);
  if(j!=1) {
    saved_location[i] = new Array();
    saved_location[i][0] = xGetElementById("map_zoom").value;
    saved_location[i][1] = xGetElementById("lat").value;
    saved_location[i][2] = xGetElementById("lng").value;
    saved_location[i][3] = marker_latlng.lat()
    saved_location[i][4] = marker_latlng.lng()
    saved_location[i][5] = xGetElementById("ment").value;
    alert(soo_saved);
  }
  var html = '<form action="#" onsubmit="soo_save_location(this.locations.value); return false" id="form_to_save"><select size="1" id="locations">';
  var n=0;
  for(n=0;n<saved_location.length;n++) {
    if(n==i) {
      html += "<option value=\""+n+"\" selected=\"selected\">"+(n+1)+'['+soo_editing+']'+"</option>";
    }
    else {
      html += "<option value=\""+n+"\">"+(n+1)+"</option>";
    }
  }
  html += "<option value=\""+n+"\">"+(n+1)+"</option>";
  html += '</select><br /><span class="button red"><input id="save_btn" type="submit" value="'+soo_save+'" /></span> <span class="button"><button type="button" onclick="soo_load_location();">'+soo_edit+'</button></span></form>';
  var save_form_zone = xGetElementById("save_form");
  xInnerHtml(save_form_zone, html);
}
function soo_load_location() { //위치 정보를 배열에서 로드
  var form = xGetElementById('form_to_save');
  var i = form.locations.selectedIndex;
  i = parseInt(i,10);
  if(!saved_location[i]) {alert(soo_nulledit); return;}
  xGetElementById("map_zoom").value = saved_location[i][0];
  xGetElementById("lat").value = saved_location[i][1];
  xGetElementById("lng").value = saved_location[i][2];
  xGetElementById("ment").value = saved_location[i][5];
  map_zoom = xGetElementById("map_zoom").value;
  center = new GLatLng(saved_location[i][1], saved_location[i][2]);
  map.setCenter(center, map_zoom);
  map.removeOverlay(marker);
  marker_latlng = new GLatLng(saved_location[i][3], saved_location[i][4]);
  marker = new GMarker(marker_latlng, {draggable: true});
  soo_marker_event();
  map.addOverlay(marker);
  marker.openInfoWindowHtml(dragmarkertext + "<br /><strong>" + saved_location[i][5] + "</strong>");
  soo_save_location(i,1);
}
function map_point(i) { //검색된 위치 정보를 배열에서 로드
  map_zoom = xGetElementById("map_zoom").value;
  center = new GLatLng(result_array[i].Point.coordinates[1], result_array[i].Point.coordinates[0]);
  map.setCenter(center, map_zoom);
  map.removeOverlay(marker);
  marker_latlng = new GLatLng(result_array[i].Point.coordinates[1], result_array[i].Point.coordinates[0]);
  marker = new GMarker(marker_latlng, {draggable: true});
  soo_marker_event();
  map.addOverlay(marker);
  marker.openInfoWindowHtml(dragmarkertext + "<br /><strong>" + result_array[i].address + "</strong>");
}
function view_list() { //검색된 위치 정보를 배열에서 리스트로 뿌림
  var html = '';
  for(var i=0;i<result_array.length;i++) {
    if(i==0) {
      html += '<ul>';
    }
    if(result_array.length==1) { map_point('0'); }
    html += "<li class=\"result_lists\"><a href=\"javascript:map_point('"+i+"');\">"+result_array[i].address +"</a></li>";
  }
  html += '</ul>';
  var list_zone = xGetElementById("result_list_layer");
  xInnerHtml(list_zone, html);
}
function addAddressToMap(response) {
  var waiting_obj = xGetElementById("waitingforserverresponse");
  if(waiting_obj) waiting_obj.style.visibility = "hidden";


  if (!response || response.Status.code != 200) {
    alert(no_result + "\nGoogle Error Code : "+response.Status.code);
  } else {
    result_array = new Array();
    result_array = response.Placemark;
    view_list();
  }
}
function showLocation(address) {
  var waiting_obj = xGetElementById("waitingforserverresponse");
  if(show_waiting_message && waiting_obj) {
    xInnerHtml(waiting_obj, waiting_message);
    xTop(waiting_obj, xScrollTop()+20);
    xLeft(waiting_obj, xScrollLeft()+20);
    waiting_obj.style.visibility = "visible";
  }
  geocoder.getLocations(address, addAddressToMap);
}
function soo_marker_event() {
  GEvent.addListener(marker, "dragstart", function() {
    map.closeInfoWindow();
  });
  GEvent.addListener(marker, "dragend", function(latlng) {
    if(latlng) {
      marker.openInfoWindowHtml(dragmarkertext);
      marker_latlng = latlng;
    }
  });
}
function getGoogleMap() {
  if (GBrowserIsCompatible()) {
    map = new GMap2(document.getElementById("map_canvas"))
    map.addControl(new GMapTypeControl());
    if(typeof(opener)=="undefined") return;
    var node = opener.editorPrevNode;
    if(node && node.nodeName == "IMG") {
      xGetElementById("width").value = xWidth(node)-4;
      xGetElementById("height").value = xHeight(node)-4;
      if(!node.getAttribute("location_no")) {
        map_lat = node.getAttribute("map_lat");
        map_lng = node.getAttribute("map_lng");
        marker_lat = node.getAttribute("marker_lat");
        marker_lng = node.getAttribute("marker_lng");
        marker_latlng = new GLatLng(marker_lat, marker_lng);
        latlng = marker_latlng;
        map_zoom = parseInt(node.getAttribute("map_zoom"),10);
        marker_ment = node.getAttribute("ment");
        marker_ment = marker_ment.replace(/\[\[STS\[\[/g,'<'); //태그 구분자 치환
        marker_ment = marker_ment.replace(/\]\]STS\]\]/g,'>'); //태그 구분자 치환
        marker_ment = marker_ment.replace(/\[\[STS_EQ\]\]/g,'=');
        if(marker_latlng) {
          latlng = marker_latlng
        }
        if(map_zoom) {
          xGetElementById("map_zoom").value = map_zoom;
        }
        if(map_lat) {
          xGetElementById("lat").value  = map_lat;
        }
        if(map_lng) {
          xGetElementById("lng").value  = map_lng;
        }
        if(marker_ment) {
          xGetElementById("ment").value  = marker_ment;
        }
      }
      else {
        var location_no = parseInt(node.getAttribute("location_no"),10);
        var html = '<form action="#" onsubmit="soo_save_location(this.locations.value); return false" id="form_to_save"><select size="1" id="locations">';
        var i=0;
        for(i=0;i<location_no;i++) {
          saved_location[i] = new Array();
          saved_location[i][0] = node.getAttribute("map_zoom"+i);
          saved_location[i][1] = node.getAttribute("map_lat"+i);
          saved_location[i][2] = node.getAttribute("map_lng"+i);
          saved_location[i][3] = node.getAttribute("marker_lat"+i);
          saved_location[i][4] = node.getAttribute("marker_lng"+i);
          saved_location[i][5] = node.getAttribute("ment"+i);
          saved_location[i][5] = saved_location[i][5].replace(/\[\[STS\[\[/g,'<'); //태그 구분자 치환
          saved_location[i][5] = saved_location[i][5].replace(/\]\]STS\]\]/g,'>'); //태그 구분자 치환
          saved_location[i][5] = saved_location[i][5].replace(/\[\[STS_EQ\]\]/g,'=');
          if(i==0) {
            html += "<option value=\""+i+"\" selected=\"selected\">"+(i+1)+'['+soo_editing+']'+"</option>";
          }
          else {
            html += "<option value=\""+i+"\">"+(i+1)+"</option>";
          }
        }
        html += "<option value=\""+i+"\">"+(i+1)+"</option>";
        html += '</select><br /><span class="button red"><input id="save_btn" type="submit" value="'+soo_save+'" /></span> <span class="button"><button type="button" onclick="soo_load_location();">'+soo_edit+'</button></span></form>';
        var save_form_zone = xGetElementById("save_form");
        xInnerHtml(save_form_zone, html);
        map_lat = saved_location[0][1];
        map_lng = saved_location[0][2];
        marker_lat = saved_location[0][3];
        marker_lng = saved_location[0][4];
        marker_latlng = new GLatLng(marker_lat, marker_lng);
        latlng = marker_latlng;
        map_zoom = parseInt(saved_location[0][0],10);
        marker_ment = saved_location[0][5];
        if(marker_latlng) {
          latlng = marker_latlng
        }
        if(map_zoom) {
          xGetElementById("map_zoom").value = map_zoom;
        }
        if(map_lat) {
          xGetElementById("lat").value  = map_lat;
        }
        if(map_lng) {
          xGetElementById("lng").value  = map_lng;
        }
        if(marker_ment) {
          xGetElementById("ment").value  = marker_ment;
        }
      }
      map.setCenter(new GLatLng(map_lat, map_lng), map_zoom);
      var center = map.getCenter();
    }
    else {
      xGetElementById("lat").value = defaultlat;
      map_lat = defaultlat;
      xGetElementById("lng").value = defaultlng;
      map_lng = defaultlng;
      map.setCenter(new GLatLng(map_lat, map_lng), 13);
      var center = map.getCenter();
      marker_latlng = center;
      xGetElementById("width").value = '600';
      xGetElementById("height").value = '400';
      xGetElementById("ment").value = '';
      latlng = center;
    }
    soo_map_set();
    map.setZoom(map_zoom);
    GEvent.addListener(map, "moveend", function() {
      center = map.getCenter();
      xGetElementById("lng").value = center.lng();
      xGetElementById("lat").value = center.lat();
      xGetElementById("map_zoom").value = map.getZoom();
      var bounds = map.getBounds();
      var southWest = bounds.getSouthWest();
      var northEast = bounds.getNorthEast();
      if(latlng.lng()<southWest.lng() || northEast.lng()<latlng.lng()) {
        map.removeOverlay(marker);
        latlng = center;
        marker_latlng = latlng;
        marker = new GMarker(center, {draggable: true});
        map.addOverlay(marker);
        soo_marker_event();
      }
      if(latlng.lat()<southWest.lat() || northEast.lat()<latlng.lat()) {
        map.removeOverlay(marker);
        latlng = center;
        marker_latlng = latlng;
        marker = new GMarker(center, {draggable: true});
        map.addOverlay(marker);
        soo_marker_event();
      }
    });
    marker = new GMarker(latlng, {draggable: true});
    soo_marker_event();
    map.addOverlay(marker);
    geocoder = new GClientGeocoder();
    xGetElementById("lng").value = center.lng();
    xGetElementById("lat").value = center.lat();
    xGetElementById("map_zoom").value = map.getZoom();
    marker_latlng = latlng;
    if(marker_ment) {
      marker.openInfoWindowHtml(marker_ment);
    }
    }
}
function insertMap(obj) {
    if(typeof(opener)=="undefined") return;
    var width = xGetElementById("width").value;
    var height = xGetElementById("height").value;
    if(saved_location.length == 0 || saved_location.length == 1) {

      
      var ment = xGetElementById("ment").value;
      ment = ment.replace(/</g,'[[STS[['); //태그 구분자 치환
      ment = ment.replace(/>/g,']]STS]]'); //태그 구분자 치환
      ment = ment.replace(/=/g,'[[STS_EQ]]');
      ment=ment.replace('ment','');
      map_zoom = xGetElementById("map_zoom").value;
      map_lat = xGetElementById("lat").value;
      map_lng = xGetElementById("lng").value;
      if(!width) {width = '600'}
      if(!height) {height = '400'}
      if(!map_zoom) {map_zoom = '13'}

      if(insert_lat || insert_lng) {
        if(insert_lat) {
          if(typeof(opener.document.getElementsByName(insert_lat)[0]) != "undefined") {
            var val = opener.document.getElementsByName(insert_lat)[0].value;
            if(typeof(val)=="string") opener.document.getElementsByName(insert_lat)[0].value = map_lat;
          }
        }
        if(insert_lng) {
          if(typeof(opener.document.getElementsByName(insert_lng)[0]) != "undefined") {
            var val = opener.document.getElementsByName(insert_lng)[0].value;
            if(typeof(val)=="string") opener.document.getElementsByName(insert_lng)[0].value = map_lng;
          }
        }
      }
      var text = "<img src=\"./common/tpl/images/blank.gif\" editor_component=\"soo_google_map\" ment=\""+ment+"\" map_lat=\""+map_lat+"\" map_lng=\""+map_lng+"\" marker_lng=\""+marker_latlng.lng()+"\" marker_lat=\""+marker_latlng.lat()+"\" map_zoom=\""+map_zoom+"\" width=\""+width+"\" height=\""+height+"\" style=\"width:"+width+"px;height:"+height+"px;border:2px dotted #FF0033;background:url(./modules/editor/components/soo_google_map/tpl/component.gif) no-repeat center;\" />";
    }
    else {
      var text = "<img src=\"./common/tpl/images/blank.gif\" editor_component=\"soo_google_map\" width=\""+width+"\" height=\""+height+"\" style=\"width:"+width+"px;height:"+height+"px;border:2px dotted #FF0033;background:url(./modules/editor/components/soo_google_map/tpl/component.gif) no-repeat center;\"";
      text += ' location_no="' + saved_location.length + '"';
      for(var i=0;i<saved_location.length;i++) {
        text += ' map_zoom' + i + '="' + saved_location[i][0] + '"';
        text += ' map_lat' + i + '="' + saved_location[i][1] + '"';
        text += ' map_lng' + i + '="' + saved_location[i][2] + '"';
        text += ' marker_lat' + i + '="' + saved_location[i][3] + '"';
        text += ' marker_lng' + i + '="' + saved_location[i][4] + '"';
        saved_location[i][5] = saved_location[i][5].replace(/</g,'[[STS[['); //태그 구분자 치환
        saved_location[i][5] = saved_location[i][5].replace(/>/g,']]STS]]'); //태그 구분자 치환
        saved_location[i][5] = saved_location[i][5].replace(/=/g,'[[STS_EQ]]');
        text += ' ment' + i + '="' + saved_location[i][5] + '"';
      }
      text += " />";
    }
    opener.editorFocus(opener.editorPrevSrl);
    var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl)
    opener.editorReplaceHTML(iframe_obj, text);
    opener.editorFocus(opener.editorPrevSrl);
    window.close();
    
}
xAddEventListener(window, "load", getGoogleMap);
