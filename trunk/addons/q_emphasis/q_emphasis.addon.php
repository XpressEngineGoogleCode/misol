<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file q_emphasis.addon.php
     * @brief 통합 검색 검색어 강조 표시 애드온
     *
     * 검색어에 표시를 합니다.
     **/
    if($called_position == 'before_module_proc') {
    $is_keyword=Context::get('is_keyword');
    if($is_keyword!='') {
    Context::addCSSFile($addon_path.'css/default.css');
    return;
    }
    }
    if($called_position != 'before_display_content' || Context::get('module')=='admin') return; 
    $is_keyword=Context::get('is_keyword');

    if($is_keyword!='') {
    //요약 내용 하이라이트
    $imsoo_content_array = explode('<dd>', $output);
    $imsoo_content_count_one = count($imsoo_content_array);
    for($imsoo_i=1; ; $imsoo_i++) {
    $imsoo_content_array[$imsoo_i]=explode('</dd>', $imsoo_content_array[$imsoo_i]);
    $imsoo_content_array[$imsoo_i][0]=str_replace( $is_keyword , '<span class="search_keyword">'.$is_keyword.'</span>' , $imsoo_content_array[$imsoo_i][0]);
    $imsoo_content_array[$imsoo_i]=implode('</dd>', $imsoo_content_array[$imsoo_i]);
    if($imsoo_content_array[$imsoo_i] == ''){
    break;
    }
    }
    $imsoo_text=implode('<dd>', $imsoo_content_array);
    //제목 하이라이트
    $imsoo_content_array = explode('<dt>', $imsoo_text);
        for($imsoo_n=1; ; $imsoo_n++) {
        $imsoo_content_array[$imsoo_n] = explode('" onclick="window.open(this.href);return false;">', $imsoo_content_array[$imsoo_n]);
            $imsoo_content_array[$imsoo_n][1]=explode('</a>', $imsoo_content_array[$imsoo_n][1]);
            $imsoo_content_array[$imsoo_n][1][0]=str_replace( $is_keyword , '<span class="search_keyword">'.$is_keyword.'</span>' , $imsoo_content_array[$imsoo_n][1][0]);
            $imsoo_content_array[$imsoo_n][1]=implode('</a>', $imsoo_content_array[$imsoo_n][1]);
        if($imsoo_content_array[$imsoo_n][1] == ''){
        $imsoo_content_array[$imsoo_n] = implode('" onclick="window.open(this.href);return false;">', $imsoo_content_array[$imsoo_n]);
        break;
        }
        $imsoo_content_array[$imsoo_n] = implode('" onclick="window.open(this.href);return false;">', $imsoo_content_array[$imsoo_n]);
        }
    $imsoo_text=implode('<dt>', $imsoo_content_array);
    //작성자 하이라이트
    $imsoo_content_array = explode('<address><strong>', $imsoo_text);
    $imsoo_content_count_one = count($imsoo_content_array);
    for($imsoo_i=1; ; $imsoo_i++) {
    $imsoo_content_array[$imsoo_i]=explode('</strong> | <span class="time">', $imsoo_content_array[$imsoo_i]);
    $imsoo_content_array[$imsoo_i][0]=str_replace( $is_keyword , '<span class="search_keyword">'.$is_keyword.'</span>' , $imsoo_content_array[$imsoo_i][0]);
    $imsoo_content_array[$imsoo_i]=implode('</strong> | <span class="time">', $imsoo_content_array[$imsoo_i]);
    if($imsoo_content_array[$imsoo_i] == ''){
    break;
    }
    }
    $output=implode('<address><strong>', $imsoo_content_array);
    $output=str_replace('<dd><dt>" onclick="window.open(this.href);return false;"><address><strong>' , '' , $output);
    }
?>
