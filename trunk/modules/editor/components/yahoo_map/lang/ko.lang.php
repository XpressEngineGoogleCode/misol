<?php
    /**
     * @file   /modules/editor/components/yahoo_map/lang/ko.lang.php
     * @author 
     * @brief  위지윅에디터(editor) 모듈 > 멀티미디어 링크 (yahoo_map) 컴포넌트의 언어팩
     **/

    $lang->width = "가로크기";
    $lang->height = "세로크기";
    $lang->about_width = "픽셀(px)이 적용됩니다.<br />*숫자만 입력";
    $lang->about_height = "픽셀(px)이 적용됩니다.<br />*숫자만 입력";
    $lang->yahoo_map_preview = "지도 미리보기";


    // 문구
    $lang->about_address = "예) 정자동, 역삼동, 코엑스, 청와대";
    $lang->about_address_use = "원하는 주소를 검색하신후 출력된 결과물을 선택하시고 [추가] 버튼을 눌러주시면 글에 지도가 추가가 됩니다";

    // 에러 메세지들
    $lang->msg_not_exists_addr = "검색하려는 대상이 없습니다";
    $lang->msg_fail_to_socket_open = "서버 접속이 실패하였습니다";
    $lang->msg_no_result = "검색 결과가 없습니다";
    $lang->msg_no_yahoo_xy = "검색결과 중 원하는 검색 결과를 선택해 주세요.";

    $lang->msg_no_apikey = "야후 거기 맵 사용을 위해서는 야후 api key가 있어야 합니다.\n api key를 관리자 >  위지윅에디터 > <a href=\"#\" onclick=\"popopen('./?module=editor&amp;act=dispEditorAdminSetupComponent&amp;component_name=yahoo_map','SetupComponent');return false;\">야후 거기 지도 입력 컴포넌트 설정</a>을 선택한 후 입력하여 주세요";
?>
