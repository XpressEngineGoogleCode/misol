<?php
if(!defined("__ZBXE__")) exit();
// soo_autolang.addon.php 언어 자동 선택 애드온
// written by Kim, Min-Soo. 김민수에 의해 작성되었습니다.
// misol is Min-Soo's ID on the internet. 김민수와 misol은 동일인입니다.
// This code is distributed under Creative Commons Attribution 2.0 Korea license, http://creativecommons.org/licenses/by/2.0/kr/ .
// 이 코드는 크리에이티브 커먼즈 저작자표시 2.0 대한민국 사용권에 따르는 조건으로 배포합니다. 자세한 내용은 http://creativecommons.org/licenses/by/2.0/kr/ 를 참조하세요.

if($_COOKIE['lang_type']) return;
if($called_position != 'before_module_init' || Context::get('module')=='admin') return;

// 언어-지역 코드 변환용 배열. XE 언어 코드와 같은 것은 제외. 파이어폭스 브라우저에서 추출;; (인터넷은 뒤져도 못찾음 ㅠ)
$xe_langtype = array(
	'ko','ko',
	'en','en','en','en','en','en','en','en','en','en','en','en',
	'zh-CN','zh-CN','zh-CN','zh-CN',
	'jp',
	'es','es','es','es','es','es','es','es','es','es','es','es','es','es',
	'es','es','es','es','es',
	'fr','fr','fr','fr','fr','fr',
	'zh-TW'
);
$http_langtype = array(
	'ko-kr','ko-kp',
	'en-gb','en-us','en-au','en-ie','en-ca','en-nz','en-za','en-bz','en-jm','en-zw','en-tt','en-ph',
	'zh','zh-cn','zh-sg','zh-hk',
	'ja',
	'es-es','es-ar','es-mx','es-cl','es-gt','es-ni','es-do','es-ve','es-bo','es-ec','es-sv','es-hn','es-uy','es-cr',
	'es-co','es-pa','es-py','es-pe','es-pr',
	'fr-fr','fr-ca','fr-lu','fr-mc','fr-be','fr-ch',
	'zh-tw'
);

$http_user_lang = str_replace($http_langtype, $xe_langtype, strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));

$lang_supported = Context::get('lang_supported');

$http_user_lang = explode(',', $http_user_lang);

if(is_array($lang_supported)) {
	$auto_selected = -2;
	foreach($http_user_lang as $user_key => $user_val) {
		if($auto_selected == -1) break;
		foreach($lang_supported as $key => $val) {
			$user_val_arr = explode(';', $user_val);
			if(!isset($user_val_arr[1])) $user_val_arr[1] = 'q=1';
			if(trim($key) == trim($user_val_arr[0]) && trim($user_val_arr[1]) != 'q=0') {
				if($auto_selected != -1) {
					if(Context::getLangType() != $key) {
						$_COOKIE['lang_type'] = $key;
						setcookie('lang_type',$key);
						Context::set('l',$key);
						Context::setLangType($key);

						// XE가 모듈 클래스를 불러오면서(XE Core ModuleHandler::getModuleInstance() 개선 필요!) 불러와버리는 언어 파일들 다시 읽기. (-_-; 왜 벌써 불러와버리는데;)
						Context::loadLang(_XE_PATH_.'modules/member/lang');
						Context::loadLang(_XE_PATH_.'modules/module/lang');
						Context::loadLang(_XE_PATH_.'modules/addon/lang');
						Context::loadLang(_XE_PATH_.'modules/counter/lang');
						// 공통 언어 파일 다시 읽기
						Context::loadLang(_XE_PATH_.'common/lang/');
					}
					$auto_selected = -1;
					break;

				}
				else break;
			}
		}
	}
}
?>
