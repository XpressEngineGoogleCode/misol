<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file openid_delegation_id_for_myid.addon.php
     * @brief OpenID Delegation ID for Myid.net 애드온
     *
     * 오픈아이디를 자신의 홈페이지나 블로그 주소로 이용할 수 있도록 해줍니다.
     * myid.net사용자만 이용 가능합니다.
     **/

    // called_position이 before_module_init일때만 실행
    if($called_position != 'before_module_init') return;

    // openid_delegation_id 애드온 설정 정보를 가져옴
    if(!$addon_info->myid) return;

    $header_script = sprintf(
        '<link rel="openid.server" href="http://server.myid.net/server" />'."\n".
        '<link rel="openid.delegate" href="http://%s.myid.net/" />'."\n".
        '<meta http-equiv="X-XRDS-Location" content="http://%s.myid.net/xrds" />',
        $addon_info->myid,
        $addon_info->myid
    );

    Context::addHtmlHeader($header_script);
?>
