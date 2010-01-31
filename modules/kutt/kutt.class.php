<?php
/**
  * @class  kutt
  * @author misol 김민수 (misol@korea.ac.kr)
  * @brief  고려대학교 시간표 모듈의 클래스
**/

class kutt extends ModuleObject {
  function moduleInstall() {
    $oModuleController = &getController('module');
    $oDB = &DB::getInstance();
    return new Object();
  }

  // @brief 설치가 이상이 없는지 체크하는 method
  function checkUpdate() {
    $oModuleController = &getController('module');
    $oDB = &DB::getInstance();

    if(!$oDB->isIndexExists("kutt_lectures","idx_lecture_code_class")) return true;

    return false;
  }

  // @brief 업데이트 실행
  function moduleUpdate() {
    $oModuleController = &getController('module');
    $oDB = &DB::getInstance();

    if(!$oDB->isIndexExists("kutt_lectures","idx_lecture_code_class")) {
      $oDB->addIndex("kutt_lectures","idx_lecture_code_class", array("lecture_code","class_code"));
    }

    return new Object(0,'success_updated');
  }

  // @brief 캐시 파일 재생성
  function recompileCache() {
  }
}

?>
