<?php
/**
 * @class  kuttController
 * @author misol (misol@korea.ac.kr)
 * @brief  kutt 모듈의 controller 클래스
 **/

class kuttController extends kutt {
  /**
   * @brief 초기화
   **/
  function init() {
  }

  function doSaveTimeTable($year, $term) {
    $oKuttModel = &getModel('kutt');
    $dept_code = $oKuttModel->dept_code;
    unset($loader);
    $loader->year = $year;
    $loader->term = $term;
    $loader->campus = 1;
    $output = executeQuery("kutt.deleteLecturesYearTerm", $loader);
    if($output->error != 0) return $output;

    foreach($oKuttModel->col_code as $col_key => $col_val) {
      $col_dept_code = $dept_code[$col_key];
      if(!is_array($col_dept_code)) continue;
      foreach($col_dept_code as $dept_key => $dept_val) {
        $data = $oKuttModel->getMajorTimeTable($year, $term, 1, $col_key, $dept_key);
        if(!$data) continue;
        foreach($data as $lecture) {
          unset($loader);
          $loader->year = $year;
          $loader->term = $term;
          $loader->campus = $lecture[0] == '안암' ? 1 : 2;
          //학수번호
          $loader->lecture_code = $lecture[1];
          //분반
          $loader->class_code = $lecture[2];
          //단과대학, 학과 코드
          $loader->lecture_type = $col_key.'.'.$dept_key;
          //전공필수, 전공선택
          $loader->lecture_type2 = $lecture[3];
          //교과목명
          $loader->lecture_title = $lecture[4];
          //담당교수
          $loader->lecturer = $lecture[5];
          //학점
          $loader->lecture_unit = $lecture[6][0];
          //시간
          $loader->lecture_hours = $lecture[6][1];
          //강의시간
          $loader->lecture_time = $lecture['time'];
          //강의장소
          $loader->lecture_place = $lecture['place'][0];
          //상대평가
          $loader->lecture_rating = trim($lecture[8])==''?0:1;
          //인원제한
          $loader->lecture_persons = trim($lecture[9])==''?0:1;
          //대기
          $loader->lecture_waits = trim($lecture[10])==''?0:1;
          //교환학생
          $loader->lecture_inter_student = trim($lecture[11])==''?0:1;
          $loader->lecture_etc = '';
          $output = executeQuery("kutt.insertLectures", $loader);
          if($output->error != 0) return $output;
        }
      }
    }


  }
}
?>
