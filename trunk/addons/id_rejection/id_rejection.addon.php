<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file id_rejection.addon.php
     * @brief 특정 회원 거부 애드온
     *
     **/

    // called_position이 before_module_proc일때만 실행, 관리자모드에서 작동 안하기
    if($called_position != 'before_module_proc' || Context::get('module')=='admin') return;
    
    // 제외 모듈
    $mid_list = explode("|@|",$addon_info->but_list);
      if(in_array(Context::get('mid'),$mid_list) && Context::get('mid')!='') return;
   
  // 제외 회원 아이디
  if($addon_info->but_group!='' || $addon_info->but_id!='') {
      $Member=&getModel('member');
      $MemberID=$Member->getLoggedUserID();
      $MemberSRL=$Member->getMemberSrlByUserID($MemberID);
      $MemberGroups=$Member->getMemberGroups($MemberSRL);
  
      $tmp_ids = explode(",",$addon_info->but_id);
        if(in_array($MemberID, $tmp_ids) && $MemberID!='') {
          echo iconv('UTF-8','EUC-KR', $addon_info->imsoo_id_rej_view);
          exit;
        }
      // 회원 아이디 끝
      
      //그룹
      $gpn=0;
      $tmp_groups = explode(",",$addon_info->but_group);
        $countone = count($MemberGroups);
        $counttwo = count($tmp_groups);
        for($i=0;;$i++) {
        $chkgroup=trim($MemberGroups[$i]);
         if($chkgroup!="") {
          $gpn++;
         
        for($t=0;$t<=$counttwo;$t++) {
        $group_name = trim($tmp_groups[$t]);
         if($chkgroup==$group_name && $chkgroup!='') {
           echo iconv('UTF-8','EUC-KR', $addon_info->imsoo_id_rej_view);
           exit;
         }
        }
       }
         if($countone <= $gpn){
         break;
         }
         }

   }
   //그룹 끝
?>
