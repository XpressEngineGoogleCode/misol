<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file openid_delegation_id_for_claimid.addon.php
     * @brief OpenID Delegation ID for claimid.com 애드온
     *
     * 오픈아이디를 자신의 홈페이지나 블로그 주소로 이용할 수 있도록 해줍니다.
     * claimid.com사용자만 이용 가능합니다.
     **/

    // called_position이 before_module_init일때만 실행
    if($called_position != 'before_module_init') return;

    // openid_delegation_id 애드온 설정 정보를 가져옴
    if(!$addon_info->claimid) return;

    $header_script = sprintf(
        '<link rel="openid.server" href="https://openid.claimid.com/server" />'."\n".
        '<link rel="openid.delegate" href="https://openid.claimid.com/%s" />'."\n".
        '<meta http-equiv="X-XRDS-Location" content="http://claimid.com/%s/xrds" />',
        $addon_info->claimid,
        $addon_info->claimid
    );

    Context::addHtmlHeader($header_script);
?>
