<?php
    /**
     * @file   /modules/editor/components/soo_naver_image/lang/jp.lang.php
     * @author ミンス <misol221@paran.com>翻訳：hiro
     * @brief  ウィジウィグエディター(editor) モジュール > ネイバーイメージ入力 (soo_naver_image) コンポネント言語パッケージ
     **/

    // 문구
    $lang->soo_msg_close = "映像を入力しました. \\nネイバーイメージ入力 書き込み道具窓を閉じますか?";
    $lang->about_soo_image_search_sort = "整列順";
    $lang->about_soo_image_search_sort_sim = "類似度順";
    $lang->about_soo_image_search_sort_date = "日付順";
    $lang->about_soo_image_search_filter = "イメージ大きさ";
    $lang->about_soo_image_search_filter_all = "すべての大きさ";
    $lang->about_soo_image_search_filter_large = "大きいイメージ";
    $lang->about_soo_image_search_filter_midium = "中間イメージ";
    $lang->about_soo_image_search_filter_small = "小さなイメージ";

    $lang->soo_msg_total = "すべて";
    $lang->soo_msg_result_num = "個の検索結果中";
    $lang->soo_msg_beforepage = "以前ページ";
    $lang->soo_msg_nextpage = "次のページ";
    $lang->about_soo_image_search = "例) ネイバー, イメージ, ミンス";
    $lang->about_soo_image_result_info = "<img alt=\"Powered by NAVER OpenAPI\" src=\"http://openapi.naver.com/logo/logo01_1.gif\" width=\"200\" height=\"20\" />";
    $lang->about_soo_image_use = "検索後イメージをクリックしてください.";

    // 에러 메세지들
    $lang->msg_not_exists_addr = "検索しようとする対象がありません";
    $lang->msg_fail_to_socket_open = "サーバー接続に失敗しました";
    $lang->msg_no_result = "検索結果がありません";

    $lang->soo_image_msg_no_apikey = "ネイバーオープン API Keyが必要です.\n API Keyを管理者 > ウィジウィグエディター > <a href=\"#\" onclick=\"popopen('./?module=editor&amp;act=dispEditorAdminSetupComponent&amp;component_name=soo_naver_image','SetupComponent');return false;\">ネイバーイメージ書き込み道具設定</a>を選択後入力してください.\n API Key は <a href=\"http://dev.naver.com/openapi/register\" onclick=\"window.open(this.href); return false;\">http://dev.naver.com/openapi/register</a>で取得できます.";
?>
