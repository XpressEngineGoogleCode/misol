<?php
    /**
     * @file   /modules/editor/components/soo_google_map/lang/jp.lang.php
     * @author Min-Soo (misol)
     * @brief  エディタ(editor) モジュール > Google地図挿入 (soo_google_map) コンポネントの日本語パック
     *  - 新しい言語で言語パックを作ったら製作者にもくださればありがたいです.
     **/
    //문구들
    $lang->soo_width = '横';
    $lang->soo_height = '縦';
    $lang->soo_ment = 'メント';
    $lang->soo_locations = '多重位置';
    $lang->soo_locations_save = '保存';
    $lang->soo_locations_edit = '呼び出し';
    $lang->soo_locations_editing = '呼び出す';
    $lang->soo_locations_saved = '保存されました.';
    $lang->soo_locations_nulledit = '情報なし';
    $lang->soo_search = '住所検索';
    $lang->soo_result = '検索結果';
    $lang->soo_drag_marker_text = 'この地点にマーカーが生成されます.';
    $lang->about_soo_result_use = '検索を利用しないで <strong>ドラッグだけでも</strong> 位置設定が可能です.<br />検索窓に捜す地域名を検索すれば, こちらに検索結果が現われます. 検索結果が 1個である場合すぐ移動し, 2個以上場合は選択しなければなりません.';
	$lang->view_map = 'A map is located here.<br />Click here to view the map.';

    // 에러 메세지들
    $lang->msg_no_result = '検索結果がありません';

    $lang->msg_no_apikey = "Google地図を使用のためにはGoogle API Keyが取得しなければなりません.\n API KEYを管理者 >  エディタ > <a href=\"#\" onclick=\"popopen('./?module=editor&amp;act=dispEditorAdminSetupComponent&amp;component_name=soo_google_map','SetupComponent');return false;\">Google地図入力コンポネント設定</a>を選択入力してください";
?>
