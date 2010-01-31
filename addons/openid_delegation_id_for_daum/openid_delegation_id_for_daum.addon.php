<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file openid_delegation_id_for_daum.addon.php
     * @brief OpenID Delegation ID for Daum.net 애드온
     *
     * 오픈아이디를 자신의 홈페이지나 블로그 주소로 이용할 수 있도록 해줍니다.
     * Daum의 OpenID사용자만 이용 가능합니다.
     **/

    // called_position이 before_module_init일때만 실행
    if($called_position != 'before_module_init') return;

    // openid_delegation_id 애드온 설정 정보를 가져옴
    if(!$addon_info->daum) return;

    $header_script = sprintf(
        '<link rel="openid.server" href="http://openid.daum.net/server" />'."\n".
        '<link rel="openid.delegate" href="http://openid.daum.net/%s" />'."\n".
        '<meta http-equiv="X-XRDS-Location" content="http://openid.daum.net/%s/xrds" />',
        $addon_info->daum,
        $addon_info->daum
    );

    Context::addHtmlHeader($header_script);
?>
