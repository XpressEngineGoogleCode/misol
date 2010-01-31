/**
 * popup으로 열렸을 경우 부모창의 위지윅에디터에 select된 멀티미디어 컴포넌트 코드를 체크하여
 * 있으면 가져와서 원하는 곳에 삽입
 **/
function getYahooMap() {
    // 부모 위지윅 에디터에서 선택된 영역이 있는지 확인
    if(typeof(opener)=="undefined") return;

    var node = opener.editorPrevNode;
    if(!node || node.nodeName != "IMG") return;

    var x = node.getAttribute("yahoo_map_x");
    var y = node.getAttribute("yahoo_map_y");
    var width = xWidth(node);
    var height = xHeight(node);
    var address = node.getAttribute("address");

    if(x&&y) {
        xGetElementById("yahoo_map_x").value = x;
        xGetElementById("yahoo_map_y").value = y;
        moveMap(x,y,3);
    }
    if(address) {
        xGetElementById("address").value = address;
        search_address(address);
    }

    xGetElementById("width").value = width-4;
    xGetElementById("height").value = height-4;
}

function insertYahooMap(obj) {
    if(typeof(opener)=="undefined") return;
    
    var x = xGetElementById("yahoo_map_x").value;
    var y = xGetElementById("yahoo_map_y").value;
    var marker = xGetElementById("marker").value;
    var address = xGetElementById("address").value;

    var width = xGetElementById("width").value;
    var height = xGetElementById("height").value;

    if(!x || !y) {
    alert(no_xy);
    return;
    }
    var text = "<img src=\"./common/tpl/images/blank.gif\" editor_component=\"yahoo_map\" address=\""+address+"\" yahoo_map_x=\""+x+"\" yahoo_map_y=\""+y+"\" width=\""+width+"\" height=\""+height+"\" style=\"width:"+width+"px;height:"+height+"px;border:2px dotted #FF0033;background:url(./modules/editor/components/yahoo_map/tpl/yahoomap_component.gif) no-repeat center;\" />";

    opener.editorFocus(opener.editorPrevSrl);

    var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl)

    opener.editorReplaceHTML(iframe_obj, text);
    opener.editorFocus(opener.editorPrevSrl);

    window.close();
}

xAddEventListener(window, "load", getYahooMap);

function search_address(selected_address) {
  if(typeof(selected_address)=="undefined") selected_address = null;
  var address = xGetElementById("address").value;
  if(!address) return;
  var params = new Array();
  params['component'] = "yahoo_map";
  params['address'] = address;
  params['method'] = "search_address";

  var response_tags = new Array('error','message','address_list');
  exec_xml('editor', 'procEditorCall', params, complete_search_address, response_tags, selected_address);
}

function moveMap(x,y,scale) {
    if(typeof(scale)=="undefined") scale = 3;
    xGetElementById("yahoo_map_x").value = x;
    xGetElementById("yahoo_map_y").value = y;
    StartYMap(x,y);
}
var yahoo_address_list = new Array();
function complete_search_address(ret_obj, response_tags, selected_address) {
  var address_list = ret_obj['address_list'];
  yahoo_address_list = new Array();

  var html = "";
  if(!address_list) html = no_result;
  else {
      var address_list = address_list.split("\n");
      for(var i=0;i<address_list.length;i++) {
        var item = address_list[i].split("#minsoo,");
        yahoo_address_list[yahoo_address_list.length] = item;
        html += "<a href='#' onclick=\"moveMap('"+item[3]+"','"+item[2]+"', '3');return false;\">"+item[8]+' '+item[7]+' '+item[6]+' '+item[5]+' '+item[4]+"</a><br />";
      }
  }
  var list_zone = xGetElementById("address_list");
  xInnerHtml(list_zone, html);
}

var map;
function StartYMap(x,y) {
    map = new YMap(document.getElementById('map'));
    map.addTypeControl();
    // 지도 확대/축소 콘트롤을 추가합니다.
    map.addZoomLong();
    // 지도 이동 콘트롤을 추가합니다.
    map.addPanControl();
    map.setMapType(YAHOO_MAP_REG);
    var center_point=new YGeoPoint(y,x);
    map.drawZoomAndCenter(center_point,3);
}