<?php
  if(!defined("__ZBXE__")) exit();

   /**
   * @file additional_mid.addon.php
   * @brief 입력된 게시글에 추가적인 주소 부여. by민수(imsoo.net)
   *
   **/
  // called_position이 before_module_init일때만 실행, 글쓰기 모드에서 작동 안하기, 관리자모드에서 작동 안하기
  if($called_position != 'before_module_init' || Context::get('module') == 'admin') return;

  $mid = trim(Context::get('mid'));

  $document_srl_to_mid = $addon_info->document_srl_to_mid;
  $mid_to_mid = $addon_info->mid_to_mid;
  $category_to_mid = $addon_info->category_to_mid;

  if($document_srl_to_mid != '') {
  $document_srl_to_mid = explode("\n", $document_srl_to_mid);
    for($imsoo_i=0; ; $imsoo_i++) {
    $document_srl_to_mid[$imsoo_i] = explode(',', $document_srl_to_mid[$imsoo_i]);
    if(trim($document_srl_to_mid[$imsoo_i][0]) == $mid && $mid!='') {
      Context::set('document_srl', trim($document_srl_to_mid[$imsoo_i][1]));
      Context::set('mid', '');
      $this->document_srl = trim($document_srl_to_mid[$imsoo_i][1]);
      $this->mid = '';
      break;
    }
    if(trim($document_srl_to_mid[$imsoo_i][0]) == ''){
      break;
    }
    }
  }

  if($mid_to_mid != '') {
  $mid_to_mid = explode("\n", $mid_to_mid);
    for($imsoo_i=0; ; $imsoo_i++) {
    $mid_to_mid[$imsoo_i] = explode(',', $mid_to_mid[$imsoo_i]);
    if(trim($mid_to_mid[$imsoo_i][0]) == $mid && $mid!='') {
      Context::set('mid', trim($mid_to_mid[$imsoo_i][1]));
      $this->mid = trim($mid_to_mid[$imsoo_i][1]);
      break;
    }
    if(trim($mid_to_mid[$imsoo_i][0]) == ''){
      break;
    }
    }
  }

  if($category_to_mid != '') {
  $category_to_mid = explode("\n", $category_to_mid);
    for($imsoo_i=0; ; $imsoo_i++) {
    $category_to_mid[$imsoo_i] = explode(',', $category_to_mid[$imsoo_i]);
      if(trim($category_to_mid[$imsoo_i][0]) == $mid && $mid!='') {
        Context::set('mid', trim($category_to_mid[$imsoo_i][1]));
        if(trim(Context::get('category'))=='') {
        Context::set('category', trim($category_to_mid[$imsoo_i][2]));
        }
        $this->mid = trim($category_to_mid[$imsoo_i][1]);
        break;
        }
      if(trim($category_to_mid[$imsoo_i][0]) == ''){
        break;
      }
    }
  }
?>
