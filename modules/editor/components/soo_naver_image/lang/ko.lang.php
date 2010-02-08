<?php
    /**
     * @file   /modules/editor/components/soo_naver_image/lang/ko.lang.php
     * @author 민수 <misol221@paran.com>
     * @brief  위지윅에디터(editor) 모듈 > 네이버 이미지 입력 (soo_naver_image) 글쓰기 도구의 한국어 언어팩
     **/

    // 문구
    $lang->soo_msg_close = "영상을 입력하였습니다. \\n네이버 이미지 입력 글쓰기 도구 창을 닫겠습니까?";
    $lang->about_soo_image_search_sort = "정렬 순서";
    $lang->about_soo_image_search_sort_sim = "유사도 순";
    $lang->about_soo_image_search_sort_date = "날짜 순";
    $lang->about_soo_image_search_filter = "이미지 크기";
    $lang->about_soo_image_search_filter_all = "모든 크기";
    $lang->about_soo_image_search_filter_large = "큰 이미지";
    $lang->about_soo_image_search_filter_midium = "중간 이미지";
    $lang->about_soo_image_search_filter_small = "작은 이미지";

    $lang->soo_msg_total = "총";
    $lang->soo_msg_result_num = "개의 검색 결과 중";
    $lang->soo_msg_beforepage = "이전 페이지";
    $lang->soo_msg_nextpage = "다음 페이지";
    $lang->about_soo_image_search = "예) 네이버, 이미지, 민수";
    $lang->about_soo_image_result_info = "<img alt=\"Powered by NAVER OpenAPI\" src=\"http://openapi.naver.com/logo/logo01_1.gif\" width=\"200\" height=\"20\" />";
    $lang->about_soo_image_use = "검색 후 원하는 이미지를 클릭하세요.";

    // 에러 메세지들
    $lang->msg_not_exists_addr = "검색하려는 대상이 없습니다";
    $lang->msg_fail_to_socket_open = "서버 접속이 실패하였습니다";
    $lang->msg_no_result = "검색 결과가 없습니다";

    $lang->soo_image_msg_no_apikey = "네이버 오픈 API Key가 있어야 합니다.\n API Key를 관리자 >  위지윅에디터 > <a href=\"#\" onclick=\"popopen('./?module=editor&amp;act=dispEditorAdminSetupComponent&amp;component_name=soo_naver_image','SetupComponent');return false;\">네이버 이미지 글쓰기 도구 설정</a>을 선택한 후 입력하여 주세요.\n API Key 는 <a href=\"http://dev.naver.com/openapi/register\" onclick=\"window.open(this.href); return false;\">http://dev.naver.com/openapi/register</a>에서 받을 수 있습니다.";
?>
