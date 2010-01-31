<?php
    /**
     * @class  anti_scrape
     * @author 민수 <misol221@paran.com>
     * @brief  스크랩 거부 컴포넌트
     **/

    class anti_scrape extends EditorHandler {

    // editor_sequence 는 에디터에서 필수로 달고 다녀야 함
    var $editor_sequence = 0;
    var $component_path = '';

    /**
     * @brief editor_sequence과 컴포넌트의 경로를 받음
     **/
    function anti_scrape($editor_sequence, $component_path) {
    $this->editor_sequence = $editor_sequence;
    $this->component_path = $component_path;
    }

    /**
     * @brief popup window요청시 popup window에 출력할 내용을 추가하면 된다
     **/
    function getPopupContent() {
    // 템플릿을 미리 컴파일해서 컴파일된 소스를 return
    $tpl_path = $this->component_path.'tpl';
    $tpl_file = 'popup.html';

    Context::set("tpl_path", $tpl_path);

    $oTemplate = &TemplateHandler::getInstance();
    return $oTemplate->compile($tpl_path, $tpl_file);
    }

    /**
     * @brief 에디터 컴포넌트가 별도의 고유 코드를 이용한다면 그 코드를 html로 변경하여 주는 method
     *
     * 이미지나 멀티미디어, 설문등 고유 코드가 필요한 에디터 컴포넌트는 고유코드를 내용에 추가하고 나서
     * DocumentModule::transContent() 에서 해당 컴포넌트의 transHtml() method를 호출하여 고유코드를 html로 변경
     **/
    function transHTML($xml_obj) {
    // 지정된 옵션을 구함
    $anti_scrape_drag = $xml_obj->attrs->anti_scrape_drag;

    // 가로/ 세로 크기를 구함
    preg_match_all('/(width|height)([^[:digit:]]+)([^;^"^\']*)/i',$xml_obj->attrs->style,$matches);
    $width = trim($matches[3][0]);
    if(!$width) $width = "90%";
    $height = trim($matches[3][1]);
    if(!$height) $height = "50";

    // 언어파일을 읽음
    Context::loadLang($this->component_path.'/lang');
    $default_title = Context::getLang('anti_scrape_default_title');
    if(!$anti_scrape_title) $anti_scrape_title = $default_title;

    $default_message = Context::getLang('anti_scrape_default_message');

    $option = Context::getLang('anti_scrape_options');


    if($anti_scrape_drag == "N") {
    $header_script = (
    '<script language="javascript" type="text/javascript">//<![CDATA['."\n".
    '<!--'."\n".
    'function keypressed(e) {'."\n".
    '  if (e == null) {'."\n".
    '    if(event.keyCode == 122 || event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 112 || event.keyCode == 25 || event.keyCode == 21  || event.keyCode == 27) {'."\n".
    "      alert('%s');"."\n".
    '      return false;'."\n".
    '    }'."\n".
    '  }'."\n".
    '  else {'."\n".
    '    if(e.keyCode == 122 || e.keyCode == 17 || e.keyCode == 18 || e.keyCode == 112 || e.keyCode == 25 || e.keyCode == 21  || e.keyCode == 27) {'."\n".
    "      alert('%s');"."\n".
    '      return false;'."\n".
    '    }'."\n".
    '  }'."\n".
    '}'."\n".
    'document.onkeydown = function (e) {'."\n".
    '  if(typeof(e) != "undefined")'."\n".
    '    keypressed(e);'."\n".
    '  else'."\n".
    '    keypressed();'."\n".
    '}'."\n".
    'document.oncontextmenu=new Function("return false")'."\n".
    'document.onselectstart=new Function("return false")'."\n". /* 마우스 select를 막는 부분 by 세이리안 */  
    'document.ondragstart=new Function("return false")'."\n". /* 마우스 드래그를 막는 부분 by 세이리안 */  
    '//-->'."\n".'//]]></script>'
    );
    
    $header_script=sprintf($header_script,$this->alert_text,$this->alert_text);
    }
    
    // 결과물 생성
    $text = sprintf($default_message);

    $style = sprintf('<style type="text/css">.anti_scrape { clear:both; margin:20px auto 20px auto; padding:8px; width:%s;border:1px solid #c0c0c0; color:#808080; text-align:center; } .anti_scrape legend { font-weight:bold; } .anti_scrape a { color:#404040; text-decoration:none; } .anti_scrape a:hover { text-decoration:underline; </style>', $width);

    $output = sprintf('%s<fieldset class="anti_scrape"><legend>%s</legend>%s</fieldset>', $style, $anti_scrape_title, $text);
    if($anti_scrape_drag == "N") {
  if($this->but_group!='' || $this->but_id!='') {
    // 제외 회원 아이디
    $Member=&getModel('member');
    $MemberID=$Member->getLoggedUserID();
    $MemberSRL=$Member->getMemberSrlByUserID($MemberID);
    $MemberGroups=$Member->getMemberGroups($MemberSRL);
    
    $tmp_ids = explode(",",$this->but_id);
    $countid = count($tmp_ids);
      for($n=0;$n<=$countid;$n++) {
       $but_ids = trim($tmp_ids[$n]);
       if($but_ids==$MemberID && $MemberID!='') {
           $header_script='';
           break;
       }
      }
    // 제외 회원 아이디 끝
      
    //제외 그룹
    $gpn=0;
    $tmp_groups = explode(",",$this->but_group);
    $countone = count($MemberGroups);
    $counttwo = count($tmp_groups);
      for($i=0;;$i++) {
	       $chkgroup=trim($MemberGroups[$i]);
	       if($chkgroup!="") {
	       	 $gpn++;
	       	for($t=0;$t<=$counttwo;$t++) {
	       	  $group_name = trim($tmp_groups[$t]);
	       	  if($chkgroup==$group_name && $chkgroup!='')
	       	  {
	       	  $header_script='';
	       	  break;
	       	  }
	     	  }
	      }
	      if($countone <= $gpn){
	      	 break;
	      }
      }
  }
       //제외 그룹 끝

    Context::addHtmlHeader($header_script);
    }
    return $output;
    }
    }

?>
