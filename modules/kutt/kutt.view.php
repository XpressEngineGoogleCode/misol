<?php
/**
  * @class  kuttView
  * @author misol 김민수 (misol@korea.ac.kr)
  * @brief  고려대학교 시간표 모듈의 view class
**/
class kuttView extends kutt {
  function init() {
  //초기화
  }

  function dispTimeTable() {
    $oKuttController = &getController('kutt');
    $data = $oKuttController->doSaveTimeTable(2010, '1R');

        print_r($data);
    //Context::set('contents', $data);

    exit;
  }
  
}
?>
