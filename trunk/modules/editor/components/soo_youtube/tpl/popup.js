var sort = "";
var query = "";
var result_list_page = "";
var item = new Array();

function insertSooCom(i, j) {
    if(typeof(opener)=="undefined") return;
    var fo = xGetElementById('form');
    var text = "<p>&nbsp;</p><blockquote style=\"margin:0px; padding:0px;\" cite=\""+item[i][10]+"\"><p><a href=\""+item[i][10]+"\" target=\"_blank\">"+item[i][0]+"</a>";
    if(j != 1) {
      if(fo.size_select) {
        var embed_width = fo.size_select.options[fo.size_select.selectedIndex].value;
      }
      else {
        var embed_width = 480;
      }
      var embed_height = embed_width*(3/4)+25;
      if(item[i][2]) {
        text += '<br /><img src="'+item[i][3]+'" editor_component="soo_youtube" width="'+embed_width+'" height="'+embed_height+'" value="'+item[i][2]+'" />';
      }
    }
    text += '</p></blockquote><p>&nbsp;</p>';
    opener.editorFocus(opener.editorPrevSrl);

    var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl)

    opener.editorReplaceHTML(iframe_obj, text);
    opener.editorFocus(opener.editorPrevSrl);

    window.close();
}

function preview_video(i) {
  var preview_zone = xGetElementById("result_view_layer");
  var html = '<p class="preview_movie"><object width="240" height="205"><param name="movie" value="'+item[i][2]+'"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="'+item[i][2]+'" allowfullscreen="true" width="240" height="205"></embed></object></p>';
  html += '<h2 class="preview_full_title">'+item[i][0]+'</h2>';
  html += '<h3 class="preview_cut_title">'+item[i][11]+'</h3>';
  html += '<ul class="preview_item_infolist">';
        if(item[i][1] != 0 && item[i][1]) {
          html += "<li class=\"author\">"+author+" : "+item[i][1]+"</li>";
        }
        if(item[i][6] || item[i][7] || item[i][8]) {
          html += "<li class=\"playtime\">"+play_time+" : ";
          if(item[i][6] != 0) { html += item[i][6]+':'; }
          if(item[i][7] != 0) { html += item[i][7]+':'; }
          if(item[i][8] != 0) {
            if(item[i][8] < 10) html += '0'+item[i][8];
            else html += item[i][8];
          }
          else { html += '00'; }
          html += "</li>"
        }
        if(item[i][4] != 0 && item[i][4]) {
          html += "<li class=\"regtime\">"+regdate+" : "+item[i][4]+"</li>";
        }
        if(item[i][5] != 0 && item[i][5]) {
          html += "<li class=\"last_update\">"+last_update+" : "+item[i][5]+"</li>";
        }
        if(item[i][9] != 0 && item[i][9]) {
          html += "<li class=\"view_count\">"+soo_view_count+" : "+item[i][9]+"</li>";
        }
        if(item[i][12] != 0 && item[i][12]) {
          html += "<li class=\"score\">"+soo_view_score+" : "+item[i][12]+"</li>";
        }
        if(item[i][14] != 0 && item[i][14]) {
          html += "<li class=\"domain\">"+soo_domain+" : "+item[i][14]+"</li>";
        }
        if(item[i][13] != 0 && item[i][13]) {
          html += "<li class=\"tag\">"+soo_tag+" : "+item[i][13]+"</li>";
        }
        html += '</ul>';
  if(item[i][2]) {
    html += '<div id="option_select"><span id="size_select">'+size_select+'</span><select name="size_select">';
    html += '<option value="240">240x205</option><option value="320">320x265</option><option value="425">425x344</option><option value="480" selected="selected">480x385</option><option value="640">640x505</option>';
    html += '</select>';
    html += '<input type="hidden" name="video_size_type" value="normal" />';
    html += '</div>';
  }
  html += '<p class="preview_controller">'+"<a href=\""+item[i][10]+"\" target=\"_blank\"><strong>"+go_doc+"</strong></a>";
  if(item[i][2]) {
    html += "<a class=\"button\" href=\"javascript:insertSooCom("+i+",0);\"><span>"+soo_msg_insert+"</span></a>";
  }
  html += '</p>';

  xInnerHtml(preview_zone, html);
}

function soo_search(start_page) {
  query = xGetElementById("query").value;
  q_site = xGetElementById("site").value;
  q_sort = xGetElementById("sort").value;
  if(!query) return;
  var params = new Array();
  if(start_page) params['soo_result_start'] = start_page;
  else {
    params['soo_result_start'] = 1;
  }
  if(q_site) params['q_site'] = q_site;
  else params['q_site'] = '';
  params['component'] = "soo_youtube";
  params['q_sort'] = q_sort;
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
  var html = "<a id='page_top'></a>";

  soo_result_start_end = '<span id="start_end">'+soo_result_start_end+'</span>';
  item = new Array();
  if(!total_result_no || total_result_no==0) html = no_result;
  else {
      var result_list = result_list.split("\n");
      xInnerHtml(list_zone, 'Result Loading...<br />Wait...');
      for(var i=0;i<result_list.length;i++) {
        item[i] = result_list[i].split(",[[soo]],");
        soo_result_list[soo_result_list.length] = item[i];
        if(!item[i][2]) html += "<div class=\"result_layer_cannotuse\"><div class=\"item_infos\"><h2 class=\"full_title\"><a href=\""+item[i][10]+"\" target=\"_blank\">"+item[i][0]+" [Can not use this contents.]</a></h2><h3 class=\"cut_title\"><a href=\""+item[i][10]+"\" target=\"_blank\">"+item[i][11]+" [Can not use this contents.]</a></h3>";
        else html += "<div class=\"result_layer\" onclick=\"preview_video('"+i+"');\"><div class=\"item_infos\"><h2 class=\"full_title\"><a href=\"javascript:insertSooCom('"+i+"', 0);\">"+item[i][0]+"</a></h2><h3 class=\"cut_title\"><a href=\"javascript:insertSooCom('"+i+"', 0);\">"+item[i][11]+"</a></h3>";
        if(item[i][3]) {
          html += "<img class=\"result_images\" onclick=\"preview_video('"+i+"');\" alt=\""+item[i][0]+"\" src=\""+item[i][3]+"\" \/>";
        }
        html += '<ul class="item_infolist">';
        if(item[i][1] != 0 && item[i][1]) {
          html += "<li class=\"author\">"+author+" : "+item[i][1]+"</li>";
        }
        if(item[i][6] || item[i][7] || item[i][8]) {
          html += "<li class=\"playtime\">"+play_time+" : ";
          if(item[i][6] != 0) { html += item[i][6]+':'; }
          if(item[i][7] != 0) { html += item[i][7]+':'; }
          if(item[i][8] != 0) {
            if(item[i][8] < 10) html += '0'+item[i][8];
            else html += item[i][8];
          }
          else { html += '00'; }
          html += "</li>"
        }
        if(item[i][4] != 0 && item[i][4]) {
          html += "<li class=\"regtime\">"+regdate+" : "+item[i][4]+"</li>";
        }
        if(item[i][5] != 0 && item[i][5]) {
          html += "<li class=\"last_update\">"+last_update+" : "+item[i][5]+"</li>";
        }
        if(item[i][9] != 0 && item[i][9]) {
          html += "<li class=\"view_count\">"+soo_view_count+" : "+item[i][9]+"</li>";
        }
        if(item[i][12] != 0 && item[i][12]) {
          html += "<li class=\"score\">"+soo_view_score+" : "+item[i][12]+"</li>";
        }
        if(item[i][14] != 0 && item[i][14]) {
          html += "<li class=\"domain\">"+soo_domain+" : "+item[i][14]+"</li>";
        }
        if(item[i][13] != 0 && item[i][13]) {
          html += "<li class=\"tag\">"+soo_tag+" : "+item[i][13]+"</li>";
        }
        html += '</ul>';
        html += "</div><p class=\"item_controller\"><span class=\"preview_video_link\"><a href=\"javascript:preview_video('"+i+"');\"><strong>"+pre_view+"</strong></a></span><span class=\"insert_video_link\"><a href=\"javascript:insertSooCom('"+i+"', 0);\"><strong>"+soo_msg_insert+"</strong></a></span><span class=\"insert_link_link\"><a href=\"javascript:insertSooCom('"+i+"', 1);\"><strong>"+soo_msg_insert_linkonly+"</strong></a></span></p></div>";
      }
  }
  var nxtpg = "";
  if(result_list_nextpage!=1) {
   nxtpg = "<a class=\"button nextpagebtn pagebtn\" href=\"javascript:soo_search("+result_list_nextpage+");\"><span>"+soo_msg_nextpage+"</span></a>";
  }
  else {
   nxtpg = '';
  }
  
  if(result_list_page!=1) {
   bfpg = "<a class=\"button beforepagebtn pagebtn\" href=\"javascript:soo_search("+bfpgno+");\"><span>"+soo_msg_beforepage+"</span></a>";
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
  xInnerHtml(result_info_zone, '<span id="bottom_info">'+'<span id="bottom_info_total">'+soo_msg_total+total_result_no+soo_msg_result_num+'</span>'+soo_result_start_end+'</span>'+bfpg + nxtpg);
  }
  xInnerHtml(list_zone, html);
  window.location.href('#page_top');
}
