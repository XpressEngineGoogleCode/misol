var query = "";
var result_list_page = "";
var item = new Array();

function insertSooCom(i, j) {
    if(typeof(opener)=="undefined") return;
    var text = "<a href=\""+item[i][1]+"\" target=\"_blank\">"+item[i][0]+"</a>";
    if(j != 1) {
        if(item[i][3]) {
            text += "<img style=\"float: left\" alt=\""+item[i][0]+"\" src=\""+item[i][3]+"\" \/>";
        }
        text += "<br />"+item[i][2];
    }

    opener.editorFocus(opener.editorPrevSrl);

    var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl)

    opener.editorReplaceHTML(iframe_obj, text);
    opener.editorFocus(opener.editorPrevSrl);

    window.close();
}

function soo_search(start_page) {
  query = xGetElementById("query").value;
  if(!query) return;
  var params = new Array();
  if(start_page) params['soo_result_start'] = start_page;
  else {
    params['soo_result_start'] = 1;
  }
  params['component'] = "soo_ency_dic";
  params['query'] = query;
  params['method'] = "soo_search";

  var response_tags = new Array('error','message','total_result_no','soo_result_start','soo_result_start_end','result_list_bfpage','result_list_nextpage','result_list');
  exec_xml('editor', 'procEditorCall', params, complete_search, response_tags);
}

var soo_result_list = new Array();
function complete_search(ret_obj, response_tags, selected_image) {
  var total_result_no = ret_obj['total_result_no'];
  var soo_result_start_end = ret_obj['soo_result_start_end'];
  var result_list_nextpage = ret_obj['result_list_nextpage'];
  var result_list = ret_obj['result_list'];
  var bfpgno = ret_obj['result_list_bfpage'];
  result_list_page = ret_obj['soo_result_start'];
  soo_result_list = new Array();
  var html = "";
  item = new Array();
  if(!total_result_no || total_result_no==0) html = no_result;
  else {
      var result_list = result_list.split("\n");
      for(var i=0;i<result_list.length;i++) {
        item[i] = result_list[i].split(",soo,");
        soo_result_list[soo_result_list.length] = item[i];
        html += "<div class=\"result_layer\"><p><a href=\""+item[i][1]+"\" target=\"_blank\"><strong>"+item[i][0]+"</strong></a>";
        if(item[i][3]) {
          html += "<img class=\"result_images\" onclick=\"insertSooCom('"+i+"', 0);\" alt=\""+item[i][0]+"\" src=\""+item[i][3]+"\" \/>";
        }
          html += "<br />"+item[i][2]+"</p><p align=\"right\"><a href=\"javascript:insertSooCom('"+i+"', 0);\"><strong>"+soo_msg_insult+"</strong></a> <a href=\"javascript:insertSooCom('"+i+"', 1);\"><strong>"+soo_msg_insult_linkonly+"</strong></a></p></div>";
      }
  }
  var nxtpg = "";
  if(result_list_nextpage!=1) {
   nxtpg = "<a class=\"button\" href=\"javascript:soo_search("+result_list_nextpage+");\"><span>"+soo_msg_nextpage+"</span></a>";
  }
  else {
   nxtpg = '';
  }
  
  if(result_list_page!=1) {
   bfpg = "<a class=\"button\" href=\"javascript:soo_search("+bfpgno+");\"><span>"+soo_msg_beforepage+"</span></a>";
  }
  else {
   bfpg = '';
  }
  
  var list_zone = xGetElementById("result_list_layer");
  var result_info_zone = xGetElementById("soo_result_info");

  if(!total_result_no || total_result_no==0){
  xInnerHtml(result_info_zone, no_result);
  }
  else {
  xInnerHtml(result_info_zone, soo_msg_total+total_result_no+soo_msg_result_num+soo_result_start_end+bfpg + nxtpg);
  }
  xInnerHtml(list_zone, html);
}
