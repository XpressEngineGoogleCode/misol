// 요약책갈피 
// (C) 김민수 (twitter @misol221)
var SooLinkerXML = new Array(), SooLinkerWindowSize = new Array();
if(navigator.userAgent.indexOf('Android') != -1) {
	SooLinkerWindowSize = Array('', '', '', '', '');
} else {
	SooLinkerWindowSize = Array('width=990,height=590', 'width=500,height=450', '', 'width=450,height=350', 'width=450,height=410');
}
function soo_Linker(service, id_type, id) {
	if(typeof(SooLinkerXML[id_type]) == "undefined") { SooLinkerXML[id_type] = new Array(); }

	var soo_target;
	if(SooLinkerXML[id_type][id]) {
		rps = SooLinkerXML[id_type][id];

		soo_target = rps.urls[service];
		try {
			window.open(soo_target,'_blank',SooLinkerWindowSize[service]);
		}
		catch(err)
		{}
		return;
	}
	url = soo_GetUrl(id_type, id);
	SooLinkerXML[id_type][id] = soo_GetSnsUrl(url, '');

	rps = SooLinkerXML[id_type][id];

	soo_target = rps.urls[service];

	window.open(soo_target,'_blank',SooLinkerWindowSize[service]);
	return;
}

function soo_GetUrl(id_type, id)
{
	var url;
	if(id_type == 'mid' || id_type == 'document_srl')
	{
		if(soo_isAllowRewrite)
		{
			url = request_uri + id;
		}
		else
		{
			url = request_uri + '?' + id_type + '=' + id;
		}
	}
	else
	{
		url = current_url;
	}
	return url;
}

function soo_GetSnsUrl(url, title)
{
	title = title.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	if(!title) title = document.title.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	var twitter_title = '';

	if((title.length + url.length + soo_snsTag.length + 15) > 140)
	{
		title = title.substring(0, Math.round(125 - (url.length + soo_snsTag.length))) + '…';
	}
	if(soo_snsTag) twitter_title = title + '\#' + soo_snsTag;
	else twitter_title = title;

	var Urls = new Array();;
	Urls.urls = new Array();
	if(soo_isMobile == true) {
		Urls.urls[0] = "https://m.facebook.com/sharer.php?u="+encodeURIComponent(url)+"&t="+encodeURIComponent(title);
		Urls.urls[1] = "https://twitter.com/share?text="+encodeURIComponent(title)+"&url="+encodeURIComponent(url)+"&hashtags="+encodeURIComponent(soo_snsTag);
		Urls.urls[2] = "https://me2day.net/p/posts/new?new_post[body]="+encodeURIComponent('"'+title.replace('"','\\"')+'":'+url)+"&new_post[tags]="+encodeURIComponent(soo_snsTag);
		Urls.urls[3] = "https://yozm.daum.net/api/popup/prePost?sourceid=0&link="+encodeURIComponent(url)+"&prefix="+encodeURIComponent(title);
		Urls.urls[4] = "http://csp.cyworld.com/bi/bi_recommend_pop.php?url="+encodeURIComponent(url);
		Urls.urls[5] = "kakaolink://sendurl?msg="+encodeURIComponent(title)+"&url="+encodeURIComponent(url)+"&appid="+encodeURIComponent(location.host)+"&appver=2.0&appname="+encodeURIComponent('Misol\'s SNS Bookmarker on XpressEngine');
	} else {
		Urls.urls[0] = "https://www.facebook.com/sharer.php?u="+encodeURIComponent(url)+"&t="+encodeURIComponent(title);
		Urls.urls[1] = "https://twitter.com/share?text="+encodeURIComponent(title)+"&url="+encodeURIComponent(url)+"&hashtags="+encodeURIComponent(soo_snsTag);
		Urls.urls[2] = "https://me2day.net/posts/new?new_post[body]="+encodeURIComponent('"'+title.replace('"','\\"')+'":'+url)+"&new_post[tags]="+soo_snsTag;
		Urls.urls[3] = "https://yozm.daum.net/api/popup/prePost?sourceid=0&link="+encodeURIComponent(url)+"&prefix="+encodeURIComponent(title);
		Urls.urls[4] = "http://csp.cyworld.com/bi/bi_recommend_pop.php?url="+encodeURIComponent(url);
		Urls.urls[5] = "kakaolink://sendurl?msg="+encodeURIComponent(title)+"&url="+encodeURIComponent(url)+"&appid="+encodeURIComponent(location.host)+"&appver=2.0&appname="+encodeURIComponent('Misol\'s SNS Bookmarker on XpressEngine');
	}
	return Urls;
}