// 요약책갈피 
// (C) 김민수 (twitter @misol221)
var SooLinkerXML = new Array();
function soo_Linker(service, document_srl) {
	if(SooLinkerXML[document_srl]) {
		rps = SooLinkerXML[document_srl];
		if(rps['menus']['item'][service]['target']=='javascript') {
			sooscrap.location.href = rps['menus']['item'][service]['alturl'];
			return false;
		} else {
			sooscrap.location.href = rps['menus']['item'][service]['url'];
			return false;
		}
		return;
	}
	var params = new Array();
	params['mid'] = current_mid;
	params['target_srl'] = document_srl;
	var response_tags = new Array('error','message','menus');
	exec_xml('SooLinkerAddon', 'getSooLinkerAddonMenu', params, function(rps, tags) {
 			if(rps['menus']['item'][service]['target'] == 'javascript') {
				sooscrap.location.href = rps['menus']['item'][service]['alturl'];
			} else {
				sooscrap.location.href = rps['menus']['item'][service]['url'];
			}
			SooLinkerXML[document_srl] = rps;
			return false;
		}, response_tags);
}