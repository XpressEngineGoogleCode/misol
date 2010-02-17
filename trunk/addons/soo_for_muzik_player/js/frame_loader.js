jQuery(document.body).ready(function () {
  if(typeof(window.parent.is_sooframe) == "undefined" && typeof(_isPoped) == "undefined") {

    var params = new Array();

    params['do'] = "doFrameSessionStart";
    params['soo_frame'] = "addon";


    var response_tags = new Array('error','message');
    exec_xml('', 'soo_for_muzik_player', params, SooFrameSetterSessionCheck, response_tags);
  }
  is_sooframe = true;
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
    location.reload(); 
  }
}
parent.document.title = document.title;
var is_sooframe;
