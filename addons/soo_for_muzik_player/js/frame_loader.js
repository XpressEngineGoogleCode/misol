jQuery(document.body).ready(function () {
  if(SooFrameChecker !== false) return;

  var params = new Array();

  params['do'] = "doFrameSessionStart";
  params['soo_frame'] = "addon";


  var response_tags = new Array('error','message');
  exec_xml('', 'soo_for_muzik_player', params, SooFrameSetterSessionCheck, response_tags);
});

function SooFrameSetterSessionCheck(ret_obj, response_tags) {
  var params = new Array();
  params['do'] = "doFrameSessionChecker";
  params['soo_frame'] = "addon";


  var response_tags = new Array('error','message');
  exec_xml('', 'soo_for_muzik_player', params, SooFrameSetter, response_tags);
}

function SooFrameSetter(ret_obj, response_tags) {
  if(ret_obj['message'] == 'FrameSessionStartSuccess') {
    if(typeof(_isPoped) == "undefined") {
      location.reload();
    } 
  }
}

if(typeof(window.parent.is_sooframe) == "undefined") {
  var SooFrameChecker = false;
}
parent.document.title = document.title;
var is_sooframe = true;
