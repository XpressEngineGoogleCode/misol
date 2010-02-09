/**
 * popup으로 열렸을 경우 부모창의 위지윅에디터에 select된 멀티미디어 컴포넌트 코드를 체크하여
 * 있으면 가져와서 원하는 곳에 삽입
 **/
var query="";
var sort="";
var filter="";
var image_list_page="";
function getsoo_naver_image() {
    // 부모 위지윅 에디터에서 선택된 영역이 있는지 확인
    if(typeof(opener)=="undefined") return;

    var node = opener.editorPrevNode;
    if(!node || node.nodeName != "IMG") return;
    query = node.getAttribute("query");
    sort = node.getAttribute("sort");
    filter = node.getAttribute("filter");
    image_list_page =  node.getAttribute("image_list_page");
    if(sort) {
        xGetElementById("sort").value = sort;
    }
    if(filter) {
        xGetElementById("filter").value = filter;
    }
    if(query) {
        xGetElementById("query").value = query;
        search_soo_image(query, image_list_page);
    }
}

function insertSooNaverImg(alt, src, query, w, h) {
    if(typeof(opener)=="undefined") return;

    var text = "<img editor_component=\"soo_naver_image\" src=\""+src+"\" image_list_page=\""+image_list_page+"\" width=\""+w+"\" height=\""+h+"\" sort=\""+sort+"\" filter=\""+filter+"\" alt=\""+alt+"\" query=\""+query+"\" />";

    opener.editorFocus(opener.editorPrevSrl);

    var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl);

    opener.editorReplaceHTML(iframe_obj, text);
    opener.editorFocus(opener.editorPrevSrl);

    if(confirm(soo_msg_close)) {
      window.close();
    }
}

xAddEventListener(window, "load", getsoo_naver_image);

function search_soo_image(selected_image, start_page) {
  if(typeof(selected_image)=="undefined") selected_image = null;
  query = xGetElementById("query").value;
  sort = xGetElementById("sort").value;
  filter = xGetElementById("filter").value;
  if(!query) return;
  var params = new Array();
  if(start_page) params['img_start'] = start_page;
  else {
    params['img_start'] = 1;
  }
  params['component'] = "soo_naver_image";
  params['soo_arr'] = sort;
  params['filter'] = filter;
  params['query'] = query;
  params['method'] = "search_soo_image";

  var response_tags = new Array('error','message','total_img_no','img_start','img_start_end','image_list_bfpage','image_list_nextpage','image_list');
  exec_xml('editor', 'procEditorCall', params, complete_search_image, response_tags, selected_image);
}

var soo_image_list = new Array();
function complete_search_image(ret_obj, response_tags, selected_image) {
  var total_img_no = ret_obj['total_img_no'];
  var img_start_end = ret_obj['img_start_end'];
  var image_list_nextpage = ret_obj['image_list_nextpage'];
  var image_list = ret_obj['image_list'];
  var bfpgno = ret_obj['image_list_bfpage'];
  image_list_page = ret_obj['img_start'];
  soo_image_list = new Array();
  var html = "<a id='page_top'></a>";
  if(total_img_no==0) html = '<span>'+no_result+'</span>';
  else {
      var image_list = image_list.split("\n");
      for(var i=0;i<image_list.length;i++) {
        var item = image_list[i].split(",soo,");
        soo_image_list[soo_image_list.length] = item;
        html += "<div class=\"image_layer\"><p><img class=\"result_image\" onclick=\"insertSooNaverImg('"+item[0]+"', '"+item[1]+"', '"+query+"', '"+item[4]+"', '"+item[3]+"');\" alt=\""+item[0]+"\" src=\""+item[2]+"\" /><br /><a href=\"#\" onkeydown=\"if(event.keyCode==13) {insertSooNaverImg('"+item[0]+"', '"+item[1]+"', '"+query+"', '"+item[4]+"', '"+item[3]+"'); return false;}\" onclick=\"insertSooNaverImg('"+item[0]+"', '"+item[1]+"', '"+query+"', '"+item[4]+"', '"+item[3]+"');\">"+item[0]+"</a></p></div>";
      }
  }
  var nxtpg = "";
  if(image_list_nextpage!=1) {
   nxtpg = "<a class=\"button pagebtn\" href=\"javascript:search_soo_image(query,"+image_list_nextpage+");\">\n<span>"+soo_msg_nextpage+"</span></a>";
  }
  else {
   nxtpg = '';
  }
  
  if(image_list_page!=1) {
   bfpg = "<a class=\"button pagebtn\" href=\"javascript:search_soo_image(query,"+bfpgno+");\">\n<span>"+soo_msg_beforepage+"</span></a>";
  }
  else {
   bfpg = '';
  }
  
  var list_zone = xGetElementById("image_list_layer");
  var result_info_zone = xGetElementById("soo_result_info");

  if(total_img_no==0){
  xInnerHtml(result_info_zone, no_result);
  }
  else {
  xInnerHtml(result_info_zone, soo_msg_total+total_img_no+soo_msg_result_num+img_start_end+bfpg + nxtpg);
  }
  xInnerHtml(list_zone, html);
  location.href = '#page_top';
}