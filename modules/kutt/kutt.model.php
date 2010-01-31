<?php
/**
  * @class  kuttModel 
  * @author misol 김민수 (misol@korea.ac.kr)
  * @brief  고려대학교 시간표 모듈의 model class
**/
class kuttModel extends kutt {
  var $term_code = array(
      '1R' => '1학기',
      '1S' => '여름학기',
      '2R' => '2학기',
      '2W' => '겨울학기',
      'SC' => '국제하계대학'
    );
  var $col_code = array(
         '0137' => '법과대학',
         '0140' => '경영대학',
         '0143' => '문과대학',
         '4652' => '생명과학대학',
         '0197' => '정경대학',
         '0209' => '이과대학',
         '0217' => '공과대학',
         '0226' => '의과대학',
         '0234' => '사범대학',
         '0231' => '간호대학',
         '3930' => '정보통신대학',
         //'3447' => '미술학부',
         '4888' => '조형학부',
         '3928' => '국제학부',
         //'3929' => '언론학부',
         '5256' => '미디어학부',
         '5138' => '자유전공학부',
         '0251' => '인문대학',
         '4460' => '과학기술대학',
         '0293' => '경상대학',
         '4463' => '공공행정학부',
         '4669' => '보건과학대학'
      );

  var $dept_code = array(
         '0137' => // 법과대학
          array('0139' => '법학과'),
         '0140' =>// 경영대학
          array('0142' => '경영학과'),
         '0143' =>// 문과대학
          array(
            '0145' => '국어국문학과',
            '0146' => '영어영문학과',
            '0147' => '철학과',
            '0148' => '한국사학과',
            '0803' => '사학과',
            '0151' => '심리학과',
            '0152' => '사회학과',
            '4601' => '과학기술학연계전공',
            '0153' => '독어독문학과',
            '0154' => '불어불문학과',
            '0155' => '중어중문학과',
            '0156' => '노어노문학과',
            '0157' => '일어일문학과',
            '0158' => '서어서문학과',
            '0159' => '한문학과',
            '4391' => '언어학과',
            '5016' => '인문학과법연계전공'
          ),
         '4652' => //생명과학대학
          array(
            '4653' => '생명과학부',
            '4654' => '생명공학부',
            '4656' => '환경생태공학부',
            '4657' => '식품자원경제학과',
            '4661' => '환경디자인연계',
            '4719' => '생명과학대학',
            '4820' => '식품공학부',
            '5186' => '기후변화연계전공',
            '5019' => '의과학연계전공'
          ),
         '0197' =>//정경대학
          array(
            '4062' => '정경대학',
            '0199' => '정치외교학과',
            '0200' => '경제학과',
            '0203' => '행정학과',
            '0201' => '통계학과',
            '5015' => 'PEL연계전공'
          ),
         '0209' =>//이과대학
          array(
            '0211' => '수학과',
            '0212' => '물리학과',
            '0213' => '화학과',
            '0215' => '지구환경과학과',
            '4603' => '암호학연계전공'
          ),
         '0217' =>//공과대학
          array(
            '4065' => '공과대학',
            '4743' => '전기전자전파공학부',
            '4084' => '화공생명공학과',
            '4630' => '신소재공학부',
            '4825' => '정보경영공학부',
            '4952' => '기계공학부',
            '4887' => '건축학과',
            '5204' => '건축사회환경공학부'
          ),
         '0226' =>//의과대학
          array(
            '0228' => '의예과',
            '0229' => '의학과'
          ),
         '0234' =>//사범대학
          array(
            '0236' => '교육학과',
            '0237' => '체육교육과',
            '0238' => '가정교육과',
            '0239' => '수학교육과',
            '0240' => '국어교육과',
            '0241' => '영어교육과',
            '0242' => '지리교육과',
            '0243' => '역사교육과',
            '0245' => '컴퓨터교육과',
            '4638' => '패션디자인및머천다이징연계전공'
          ),
         '0231' =>//간호대학
          array('0233' => '간호학과'),
         '3930' =>//정보통신대학
          array(
            '4728' => '컴퓨터·통신공학부',
            '5188' => '뇌및인지과학연계전공'
          ),
         //'3447' => '미술학부',
         '4888' =>//조형학부
          array('4889' => '조형학부'),
         '3928' =>//국제학부
          array('3931' => '국제학부'),
         //'3929' => '언론학부',
         '5256' =>//미디어학부
          array('5257' => '미디어학부'),
         //'5138' => '자유전공학부',
         '0251' =>//인문대학
          array(
            '0253' => '국어국문학과',
            '0255' => '영어영문학과',
            '0257' => '사회학과',
            '4061' => '독일문화정보학과',
            '0259' => '북한학과',
            '4427' => '사회복지연계전공',
            '0258' => '고고미술사학과',
            '4459' => '중국학부',
            '4824' => '미디어문예창작학과',
            '5017' => '문화콘텐츠연계전공'
          ),
         '4460' =>//과학기술대학
          array(
            '4589' => '과학기술대학',
            '4462' => '디스플레이·반도체물리학과',
            '4536' => '사회체육학과',
            '4546' => '정보수학과',
            '4547' => '신소재화학과',
            '4548' => '컴퓨터정보학과',
            '4549' => '정보통계학과',
            '4550' => '전자및정보공학부',
            '4551' => '제어계측공학과',
            '4552' => '환경시스템공학과',
            '4553' => '생명정보공학과',
            '4554' => '식품생명공학과'
          ),
         '0293' =>//경상대학
          array(
            '4059' => '경상대학',
            '0295' => '경제학과',
            '0300' => '경영정보학과',
            '4633' => '경영학부'
          ),
         '4463' =>//공공행정학부
          array('4464' => '공공행정학부'),
         '4669' =>//보건과학대학
          array(
            '4893' => '보건과학대학',
            '4670' => '임상병리학과',
            '4671' => '방사선학과',
            '4672' => '물리치료학과',
            '4673' => '치기공학과',
            '4674' => '보건행정학과',
            '4676' => '식품영양학과',
            '4677' => '환경보건학과',
            '5037' => '생체의공학과'
          )
      );

  function init() { 
  //초기화
  }

  //저장된 또는 새 시간표를 받아옴. - 학부 전공 과목용
  function getMajorTimeTable($year, $term, $campus, $col, $dept) {
    $host = 'sugang.korea.ac.kr';
    $service_uri = '/lecture/LecMajorSub.jsp';
    $vars ='yy='.$year.'&tm='.$term.'&campus='.$campus.'&col='.$col.'&dept='.$dept;
    $headers =
      array(
        //'User-Agent' => 'KUTABLE Korea University Timetable Downloader by misol',
        //'Connection' => 'Keep-Alive'
      );

    $buff = FileHandler::getRemoteResource('http://sugang.korea.ac.kr:7080/lecture/LecMajorSub.jsp', $vars, 3, 'POST', 'application/x-www-form-urlencoded', $headers);

    $buff = Context::convertEncodingStr($buff);
    $buff = explode("\n", $buff);
    $buff_refined = '';
    foreach($buff as $key) {
      $buff_refined .= trim($key);
    }
    $buff = $buff_refined;
    unset($buff_refined);

    if(strpos($buff,'검색결과가 존재 하지 않습니다') !== false) return null;
    $buff = explode('</tr>--></tr><tr><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td></tr>', $buff);
    $buff = $buff[1];
    $buff = str_replace('</table></td></tr></table></td></tr><tr><td height="70">&nbsp;</td></tr></table></td><td height="6" bgcolor="D0D0D0" width="1"></td></tr></table></BODY></FORM></HTML>', '' , $buff);
    $buff = explode('<tr><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td></tr>', $buff);

    $time_table_array = array();
    foreach($buff as $key => $var) {
      if($var) {
        $var = strstr($var, '<td class="teble02_">');
        $var = explode('[[[({/td)}]]]', strip_tags(str_replace(array('</td>','&nbsp;'),array('[[[({/td)}]]]',' '),$var),'<BR>'));
        foreach($var as $sm_key => $sm_var) {
          if($sm_key%2 == 0) {
            if($sm_key == 12) { //학점, 시간 구분
              $sm_var = explode('(', $sm_var);
              $sm_var[0] = trim($sm_var[0]);
              $sm_var[1] = trim(str_replace(')','',$sm_var[1]));
              $time_table_array[$key][6] = $sm_var;
            }
            elseif($sm_key == 14) {
              $time_table_array[$key][7] = preg_split("/<(B|b)(R|r)([.^>]*)>/",$sm_var);
              foreach($time_table_array[$key][7] as $tpvar) {
                $tpvar_arr = explode(') ',$tpvar);
                $time_table_array[$key]['place'][] = $tpvar_arr[1];
                $tpvar_arr[0] = explode('(',$tpvar_arr[0]);
                if(trim($tpvar_arr[0][0]) == '미정') {
                  $tpvar_arr[0][0] = '';
                  $tpvar_arr[0][1] = '';
                }
                $tpvar_arr[0][1] = explode('-',$tpvar_arr[0][1]);
                $tpvar_date = '';
                if(isset($tpvar_arr[0][1][1])) {
                  for($i=$tpvar_arr[0][1][0]; $i <= $tpvar_arr[0][1][1]; $i++) {
                    $tpvar_date .= $tpvar_arr[0][0].'.'.$i."\n";
                  }
                }
                $time_table_array[$key]['time'][] = max($tpvar_date,$tpvar_arr[0][0].'.'.$tpvar_arr[0][1][0]);
              }
              $time_table_array[$key]['time'] = implode("\n",$time_table_array[$key]['time']);
              $time_table_array[$key]['place'] = array_unique($time_table_array[$key]['place']);
            }
            else { $time_table_array[$key][] = trim(preg_replace("/<(B|b)(R|r)([.^>]*)>/", "\n",$sm_var));
            }
          }
        }
      }
    }

  return $time_table_array;
  }


  function getEtcTimeTable($year, $term, $campus, $col, $dept) {
    $host = 'sugang.korea.ac.kr';
    $service_uri = '/lecture/LecEtcSub.jsp';
    $vars ='yy='.$year.'&tm='.$term.'&campus='.$campus.'&col='.$col.'&dept='.$dept;

    $buff = FileHandler::getRemoteResource('http://sugang.korea.ac.kr:7080/lecture/LecEtcSub.jsp', $vars, 3, 'POST', 'application/x-www-form-urlencoded', $headers);

    $buff = Context::convertEncodingStr($buff);
    $buff = explode("\n", $buff);
    $buff_refined = '';
    foreach($buff as $key) {
      $buff_refined .= trim($key);
    }
    $buff = $buff_refined;
    unset($buff_refined);

    $buff = explode('교환<br>학생</td></tr><tr><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td></tr>', $buff);
    $buff = $buff[1];
    $buff = str_replace('</table></td></tr></table></td></tr><tr><td height="70">&nbsp;</td></tr></table></td><td height="6" bgcolor="D0D0D0" width="1"></td></tr></table></BODY></FORM></HTML>', '' , $buff);
    $buff = explode('<tr><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF" width="1"><img src="../images/comm/blank.gif" width="1" height="1"></td><td bgcolor="DFDFDF"><img src="../images/comm/blank.gif" width="1" height="1"></td></tr>', $buff);

    $time_table_array = array();
    foreach($buff as $key => $var) {
      if($var) {
        $var = strstr($var, '<td class="teble02_" height="25"><a href="http://infodepot.korea.ac.kr/lecture1/lecsubjectPlanView.jsp?');
        $var = '<test s="'.strstr($var, '" >');
        $var = explode('[[[({/td)}]]]', strip_tags(str_replace(array('</td>','&nbsp;'),array('[[[({/td)}]]]',' '),$var),'<BR>'));
        foreach($var as $sm_key => $sm_var) {
          if($sm_key == 0) {
            if($campus == 2) $time_table_array[$key][0] = '세종';
            else $time_table_array[$key][0] = '안암';
          }
          if($sm_key%2 == 0) {
            if($sm_key == 10) { //학점, 시간 구분
              $sm_var = explode('(', $sm_var);
              $sm_var[0] = trim($sm_var[0]);
              $sm_var[1] = trim(str_replace(')','',$sm_var[1]));
              $time_table_array[$key][5] = $sm_var;
            }
            elseif($sm_key == 12) {
              $time_table_array[$key][6] = preg_split("/<(B|b)(R|r)([.^>]*)>/",$sm_var);
              foreach($time_table_array[$key][6] as $tpvar) {
                $tpvar_arr = explode(') ',$tpvar);
                $time_table_array[$key]['place'][] = $tpvar_arr[1];
                $tpvar_arr[0] = explode('(',$tpvar_arr[0]);
                if(trim($tpvar_arr[0][0]) == '미정') {
                  $tpvar_arr[0][0] = '';
                  $tpvar_arr[0][1] = '';
                }
                $tpvar_arr[0][1] = explode('-',$tpvar_arr[0][1]);
                $tpvar_date = '';
                if(isset($tpvar_arr[0][1][1])) {
                  for($i=$tpvar_arr[0][1][0]; $i <= $tpvar_arr[0][1][1]; $i++) {
                    $tpvar_date .= $tpvar_arr[0][0].'.'.$i."\n";
                  }
                }
                $time_table_array[$key]['time'][] = max($tpvar_date,$tpvar_arr[0][0].'.'.$tpvar_arr[0][1][0]);
              }
              $time_table_array[$key]['time'] = implode("\n",$time_table_array[$key]['time']);
              $time_table_array[$key]['place'] = array_unique($time_table_array[$key]['place']);
            }
            else { $time_table_array[$key][] = trim(preg_replace("/<(B|b)(R|r)([.^>]*)>/", "\n",$sm_var));
            }
          }
        }
      }
    }
  return $time_table_array;
  }


}
?>
