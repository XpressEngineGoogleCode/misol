// 책검색 컴포넌트 컴포넌트 동작 스크립트.
// ⓒ 김민수 2009.
var query = "";
var result_list_page = "";
var where = "";
var cata = "";
var item = new Array();

function insertSooCom(i) {
    if(typeof(opener)=="undefined") return;
    var html = " <br /> <div style=\"background-color:#ffffff; color:#111111; border:#888888 1px solid; margin:3px; padding:5px; text-align:left;\">";
    if(item['item_'+i]['image']) html += "<img style=\"float: left; margin:0px 5px 0px 0px;\" alt=\""+item['item_'+i]['title']+"\" src=\"http\://"+item['item_'+i]['image']+"\" \/>"
         html += "<h3 style=\"display:inline;\">"+item['item_'+i]['title']+"</h3>"
              +'<ul style="list-style:none; margin:0px; padding:0px; display:block;">';
        if(item['item_'+i]['author']) html += '<li>'+soo_book_author+" : "+item['item_'+i]['author']+"</li>";
        if(item['item_'+i]['price'] || item['item_'+i]['discount']) {
           html += '<li>';
          if(item['item_'+i]['price']) html += soo_book_price+" : "+item['item_'+i]['price']+soo_book_price_unit;
          if(item['item_'+i]['discount']) html += " ("+soo_book_price_discount+" : "+item['item_'+i]['discount']+soo_book_price_unit+")";
          html += "</li>";
        }
        if(item['item_'+i]['publisher']) html += '<li>'+soo_book_publisher+" : "+item['item_'+i]['publisher']+"</li>";
        if(item['item_'+i]['pubdate']) html += '<li>'+soo_book_pdate+" : "+item['item_'+i]['pubdate'].substring(0, 4)+". "+item['item_'+i]['pubdate'].substring(4, 6)+". "+item['item_'+i]['pubdate'].substring(6, 8)+"</li>";
        if(item['item_'+i]['isbn']) html += '<li>'+soo_book_isbn+" : "+item['item_'+i]['isbn']+"</li>"
        if(item['item_'+i]['description']) html += '<li>'+soo_book_description+" : "+item['item_'+i]['description']+"</li>";
        if(item['item_'+i]['link']) html += "<li style=\"text-align:right;\"><a href=\""+item['item_'+i]['link']+"\" target=\"_blank\">"+soo_book_link+"</a>"+"</li>"
        html += "</ul></div><br />&nbsp;"

    opener.editorFocus(opener.editorPrevSrl);

    var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl)

    opener.editorReplaceHTML(iframe_obj, html);
    opener.editorFocus(opener.editorPrevSrl);

    if(confirm(soo_msg_close)) {
      window.close();
    }
}

function doSearch(query, where, cata, page) {
  if(!where) where = 'any';
  if(!cata) cata = 'all';
  xGetElementById("cata").value = cata;
  xGetElementById("where").value = where;
  xGetElementById("query").value = query;
  var params = new Array();
  if(page) params['bookinfo_start'] = page;
  else {
    params['bookinfo_start'] = 1;
  }
  params['component'] = "soo_naver_bookinfo";
  params['query'] = query;
  params['where'] = where;
  params['cata'] = cata;
  params['method'] = "search_soo_bookinfo";

  var response_tags = new Array('error','message','total_bookinfo_no','bookinfo_start','bookinfo_start_end','result_list_bfpage','result_list_nextpage','item','num');
  exec_xml('editor', 'procEditorCall', params, complete_search_bookinfo, response_tags);
}

function search_soo_bookinfo(start_page) {
  query = xGetElementById("query").value;
  where = xGetElementById("where").value;
  cata = xGetElementById("cata").value;
  if(!query) return;
  if(cata != "all" && where =="any") {
  alert (soo_msg_cata_anywhere);
  xGetElementById("where").value = "titl";
  where = xGetElementById("where").value;
  }
  doSearch(query, where, cata, start_page);
}

var soo_result_list = new Array();
function complete_search_bookinfo(ret_obj, response_tags, selected_image) {
  var total_bookinfo_no = ret_obj['total_bookinfo_no'];
  var bookinfo_start_end = ret_obj['bookinfo_start_end'];
  var result_list_nextpage = ret_obj['result_list_nextpage'];
  item = ret_obj['item'];
  var numb = ret_obj['num']
  var bfpgno = ret_obj['result_list_bfpage'];
  result_list_page = ret_obj['bookinfo_start'];

  soo_result_list = new Array();
  var html = "<a id='page_top'></a>";
  if(total_bookinfo_no==0 || numb ==0) html = no_result;
  else {
      for(var i=0;i<numb;i++) {
        html += "<div class=\"result_layer\">";
        if(item['item_'+i]['image']) html += "<img class=\"result_images\" onclick=\"insertSooCom('"+i+"');\" alt=\""+item['item_'+i]['title']+"\" src=\"http\://"+item['item_'+i]['image']+"\" \/>"
         html += soo_book_title+" : <h2 class=\"book_title\"> "+item['item_'+i]['title']+"</h2><br />"
              +'<ul class="book_info">';
        if(item['item_'+i]['author']) html += '<li>'+soo_book_author+" : "+item['item_'+i]['author']+"</li>";
        if(item['item_'+i]['price'] || item['item_'+i]['discount']) {
           html += '<li>';
          if(item['item_'+i]['price']) html += soo_book_price+" : "+item['item_'+i]['price']+soo_book_price_unit;
          if(item['item_'+i]['discount']) html += " ("+soo_book_price_discount+" : "+item['item_'+i]['discount']+soo_book_price_unit+")";
          html += "</li>";
        }
        if(item['item_'+i]['publisher']) html += '<li>'+soo_book_publisher+" : "+item['item_'+i]['publisher']+"</li>";
        if(item['item_'+i]['pubdate']) html += '<li>'+soo_book_pdate+" : "+item['item_'+i]['pubdate'].substring(0, 4)+". "+item['item_'+i]['pubdate'].substring(4, 6)+". "+item['item_'+i]['pubdate'].substring(6, 8)+"</li>";
        if(item['item_'+i]['isbn']) html += '<li>'+soo_book_isbn+" : "+item['item_'+i]['isbn']+"</li>"
        if(item['item_'+i]['description']) html += '<li>'+soo_book_description+" : "+item['item_'+i]['description']+"</li>";
        if(item['item_'+i]['link']) html += "<li class=\"book_link\"><a href=\""+item['item_'+i]['link']+"\" target=\"_blank\">"+soo_book_link+"</a>"+"</li>"
        html += "</ul>"
             +"<p align=\"right\"><a href=\"javascript:insertSooCom('"+i+"');\"><strong>"+soo_msg_insert+"</strong></a></p></div>";
      }
  }
  var nxtpg = "";
  if(result_list_nextpage!=1) {
   nxtpg = "<a class=\"button pagebtn\" href=\"javascript:search_soo_bookinfo("+result_list_nextpage+");\"><span>"+soo_msg_nextpage+"</span></a>";
  }
  else {
   nxtpg = '';
  }
  
  if(result_list_page!=1) {
   bfpg = "<a class=\"button pagebtn\" href=\"javascript:search_soo_bookinfo("+bfpgno+");\"><span>"+soo_msg_beforepage+"</span></a>";
  }
  else {
   bfpg = '';
  }
  
  var list_zone = xGetElementById("result_list_layer");
  var result_info_zone = xGetElementById("soo_result_info");

  if(total_bookinfo_no==0){
  xInnerHtml(result_info_zone, no_result);
  }
  else {
  xInnerHtml(result_info_zone, soo_msg_total+total_bookinfo_no+soo_msg_result_num+bookinfo_start_end+bfpg + nxtpg);
  }
  xInnerHtml(list_zone, html);
  location.href = '#page_top';
}