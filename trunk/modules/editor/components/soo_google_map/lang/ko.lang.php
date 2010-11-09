<?php
    /**
     * @file   /modules/editor/components/soo_google_map/lang/ko.lang.php
     * @author 민수 (misol)
     * @brief  위지윅에디터(editor) 모듈 > 구글 지도 삽입 (soo_google_map) 컴포넌트의 한국어팩
     *  - 새 언어로 언어팩을 만드시면 제작자에게도 주시면 감사하겠습니다.
     **/
    //문구들
    $lang->soo_width = '가로 (픽셀)';
    $lang->soo_height = '세로 (픽셀)';
    $lang->soo_ment = '멘트';
    $lang->soo_locations = '다중 위치';
    $lang->soo_locations_save = '저장';
    $lang->soo_locations_edit = '불러오기';
    $lang->soo_locations_editing = '불러옴';
    $lang->soo_locations_saved = '저장되었습니다.';
    $lang->soo_locations_nulledit = '정보 없음';
    $lang->soo_search = '주소 검색';
    $lang->soo_result = '검색 결과';
    $lang->soo_drag_marker_text = '이 지점에 마커가 생성됩니다.';
    $lang->about_soo_result_use = '검색을 이용하지 않고 <strong>드래그 만으로도</strong> 위치 설정이 가능합니다.<br />검색창에 찾을 지역명을 검색 하시면, 이곳에 검색 결과가 나타납니다. 검색 결과가 1개 일 경우 바로 이동하며, 2개 이상일 경우는 선택해 주셔야 합니다.';
	$lang->view_map = '지도가 입력되어 있습니다.<br />지도를 보시려면 여기를 클릭하세요.';

    // 에러 메세지들
    $lang->msg_no_result = '검색 결과가 없습니다';

    $lang->msg_no_apikey = "구글 지도 사용을 위해서는 구글 API Key가 있어야 합니다.\n API KEY를 관리자 >  위지윅에디터 > <a href=\"#\" onclick=\"popopen('./?module=editor&amp;act=dispEditorAdminSetupComponent&amp;component_name=soo_google_map','SetupComponent');return false;\">구글 지도 입력 컴포넌트 설정</a>을 선택한 후 입력하여 주세요";
?>
