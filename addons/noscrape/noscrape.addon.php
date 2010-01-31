<?php
if(!defined("__ZBXE__")) exit();

/**
 * @file noscrape.addon.php
 * @brief Anti Scrape 애드온
 *
 * 오른쪽 마우스 버튼과 마우스 드래그, 키보드 사용을 막습니다.
 * 소스보기를 막지는 못합니다.
 **/

// called_position이 before_module_init일때만 실행, 글쓰기 모드에서 작동 안하기, 관리자모드에서 작동 안하기
if($called_position != 'after_module_proc' || Context::get('module')=='admin') return;
  if($addon_info->write_select == 'Y') {
    if(Context::get('act')=='dispBoardWrite' || Context::get('module')=='editor') return;
  }
  $soo_header_allow_key = '';
  $Member=&getModel('member');
  $MemberID=$Member->getLoggedUserID();
  // 제외 회원 아이디
  if($addon_info->but_group!='' || $addon_info->but_id!='') {
    $MemberSRL=$Member->getMemberSrlByUserID($MemberID);
    $MemberGroups=$Member->getMemberGroups($MemberSRL);
    $tmp_ids = explode(",",$addon_info->but_id);
    if(in_array($MemberID, $tmp_ids) && $MemberID!='') return;
    // 제외 회원 아이디 끝
    //제외 그룹
    $gpn=0;
    $tmp_groups = explode(",",$addon_info->but_group);
    $MemberGroups = implode("\n",$MemberGroups);
    $MemberGroups = explode("\n",$MemberGroups);
    $countone = count($MemberGroups);
    $counttwo = count($tmp_groups);
    for($i=0;$i<$countone;$i++) {
      $chkgroup=trim($MemberGroups[$i]);
        for($t=0;$t<$counttwo;$t++) {
          $group_name = trim($tmp_groups[$t]);
          if($chkgroup==$group_name && $chkgroup!='') return;
        }
    }
  } //제외 그룹 끝

//자기글 스크랩 허용
  $document_srl = Context::get('document_srl');
  if($document_srl != '' && $addon_info->author_select == 'Y') {
    $document_srl = Context::get('document_srl');
    $oDocumentModel = &getModel('document');
    $oDocument = $oDocumentModel->getDocument($document_srl, $this->grant->manager);
    $doc_author = $oDocument->getUserID();
    if($MemberID == $doc_author && $MemberID != '') return;
  }
  if($addon_info->key_allow=='O' && $addon_info->key_allow!='') {
    $soo_header_allow_key = sprintf('function keypressed(e) {'."\n".
    'if (e == null) {'."\n".
    'if(event.keyCode == 122 || event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 112 || event.keyCode == 25 || event.keyCode == 21|| event.keyCode == 27) {'."\n".
    "alert('%s');"."\n".
    'return false;'."\n".
    '}'."\n".
    '}'."\n".
    'else {'."\n".
    'if(e.keyCode == 122 || e.keyCode == 17 || e.keyCode == 18 || e.keyCode == 112 || e.keyCode == 25 || e.keyCode == 21|| e.keyCode == 27) {'."\n".
    "alert('%s');"."\n".
    'return false;'."\n".
    '}'."\n".
    '}'."\n".
    '}'."\n".
    'document.onkeydown = function (e) {'."\n".
    'if(typeof(e) != "undefined")'."\n".
    'keypressed(e);'."\n".
    'else'."\n".
    'keypressed();'."\n".
    '}'."\n",$addon_info->alert_text,$addon_info->alert_text);
  }
  $header_common = sprintf('<script language="javascript" type="text/javascript">//<![CDATA['."\n".
    '<!--'."\n".
    '%s'.
    'document.oncontextmenu=new Function("return false")'."\n",$soo_header_allow_key
  );

  if($addon_info->drag_select == 'Y') {
    $header_specific = ('document.onselectstart=new Function("return false")'."\n". /* 마우스 select를 막는 부분 by 세이리안 */
      'document.ondragstart=new Function("return false")'."\n" /* 마우스 드래그를 막는 부분 by 세이리안 */
    );
  }
  if($addon_info->ff_select == 'O') {
    $header_specific = $header_specific.('document.onmousedown=new Function("return false")'."\n");
  }
  $header_script=$header_common.$header_specific.'//-->'."\n".'//]]></script>';
  Context::addHtmlHeader($header_script);
?>
