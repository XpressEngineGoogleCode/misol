// 요약책갈피 
// (C) 김민수 (twitter @misol221)
var SooLinkerXML = new Array();
var SooLinkerWindowSize = new Array();
if(navigator.userAgent.indexOf('Android') != -1) {
	SooLinkerWindowSize = Array('', '', '', '', '');
} else {
	SooLinkerWindowSize = Array('width=770,height=450', 'width=500,height=450', '', 'width=450,height=350', 'width=450,height=410');
}
function soo_Linker(service, id_type, id) {
	var sooscrap;
	var soo_target;
	if(typeof(SooLinkerXML[id_type]) == "undefined") { SooLinkerXML[id_type] = new Array(); }

	sooscrap = window.open('','_blank',SooLinkerWindowSize[service]);
	sooscrap.document.write("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en\" xml:lang=\"en\"><head><title>SNS Bookmarker Loading</title><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" /></head><body><h1>Wait a moment...</h1><p id=\"state\">Searching the SNS page...</p><address style=\"display:none;\">misol@korea.ac.kr<br /><a href=\"http://twitter.com/misol221\" onclick=\"window.open(this.href);return false;\">http://twitter.com/misol221</a></address></body></html>");
	if(SooLinkerXML[id_type][id]) {
		rps = SooLinkerXML[id_type][id];

		soo_target = rps['urls']['url'][service];

		sooscrap.location.href = soo_target;
		sooscrap.document.getElementById('state').innerHTML = "Redirecting...<br /><a href=\""+soo_target+"\">"+soo_target+"</a>";
		return false;
	}
	var params = new Array();
	params['mid'] = current_mid;
	params[id_type] = id;
	params['soo_url'] = current_url;
	params['doc_title'] = document.title;
	var response_tags = new Array('error','message','urls');
	exec_xml('SooLinkerAddon', 'getSooLinkerAddonUrls', params, function(rps, tags) {
			soo_target = rps['urls']['url'][service];

			sooscrap.location.href = soo_target;
			sooscrap.document.getElementById('state').innerHTML = "Redirecting...<br /><a href=\""+soo_target+"\">"+soo_target+"</a>";
			SooLinkerXML[id_type][id] = rps;
			return false;
		}, response_tags);
}
