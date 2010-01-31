<?php
    /**
     * @class  freeuse_license
     * @author 민수 <misol221@paran.com>
     * @brief  정보공유라이선스 출력 에디터 컴포넌트
     **/

    class freeuse_license extends EditorHandler {

        // editor_sequence 는 에디터에서 필수로 달고 다녀야 함
        var $editor_sequence = 0;
        var $component_path = '';

        /**
         * @brief editor_sequence과 컴포넌트의 경로를 받음
         **/
        function freeuse_license($editor_sequence, $component_path) {
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
            $freeuse_title = $xml_obj->attrs->freeuse_title;
            $freeuse_use_mark = $xml_obj->attrs->freeuse_use_mark;
            $freeuse_allow_commercial = $xml_obj->attrs->freeuse_allow_commercial;
            $freeuse_allow_modification = $xml_obj->attrs->freeuse_allow_modification;
            $freeuse_author = $xml_obj->attrs->freeuse_author;

            // 가로/ 세로 크기를 구함
            preg_match_all('/(width|height)([^[:digit:]]+)([^;^"^\']*)/i',$xml_obj->attrs->style,$matches);
            $width = trim($matches[3][0]);
            if(!$width) $width = "90%";
            $height = trim($matches[3][1]);
            if(!$height) $height = "50";

            // 언어파일을 읽음
            Context::loadLang($this->component_path.'/lang');
            $default_title = Context::getLang('freeuse_default_title');
            if(!$freeuse_title) $freeuse_title = $default_title;

            $default_message = Context::getLang('freeuse_default_message');

            $option = Context::getLang('freeuse_options');

            // 영리 이용 체크
            if($freeuse_allow_commercial == 'N') {
            $opt1 = 'yg';
            $prohibit_commercial='<prohibits rdf:resource="http://freeuse.or.kr/rdf/oal/CommercialUse" />';
            }
            else {
            $opt1 = '';
            $prohibit_commercial='';
            }

            // 수정 표시 체크
            if($freeuse_allow_modification == 'N') {
            $opt2 = 'gg';
            $prohibit_mod='<prohibits rdf:resource="http://freeuse.or.kr/rdf/oal/DerivativeWorks" />';
            }
            else {
            $opt2 = '';
            $prohibit_mod='';
            }
            
            if($freeuse_allow_modification == 'Y' && $freeuse_allow_commercial == 'Y') {
            $opt1 = 'hy';
            $option['freeuse_allow_commercial'][$freeuse_allow_commercial]=' 허용';
            }
            
            if($freeuse_allow_modification == 'N' && $freeuse_allow_commercial == 'N') {
            $space1 = '_';
            }
            else {
            $space1 = '';
            }

            // 버전
            $version = '2.0';


            // 마크 이용시
            $freeuse_image = '';
            if($freeuse_use_mark == "Y") {
                $freeuse_image = sprintf('
                        <a href="http://freeuse.or.kr/license/%s/%s%s%s/" target="_blank"><img src="http://freeuse.or.kr/image/banner0.gif" alt="정보공유라이선스" style="border:0;" /></a><br />',
                        $version, $opt1, $space1, $opt2
                );
            }
            $freeuse_image = $freeuse_image . sprintf('
            <!--
<rdf:RDF xmlns="http://freeuse.or.kr/rdf/oal/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">

  <Work rdf:about="">
    <dc:type rdf:resource="http://purl.org/dc/dcmitype/" />
    <dc:creator>%s</dc:creator>

    <license rdf:resource="http://freeuse.or.kr/license/%s/%s%s%s/" />
  </Work>

  <License rdf:about="http://freeuse.or.kr/license/2.0/gg/">
    %s
    %s
    <version rdf:resource="http://freeuse.or.kr/license/%s/%s%s%s/" />
  </License>

</rdf:RDF>
-->',
                                    $freeuse_author,
                        $version, $opt1, $space1, $opt2,
                        $prohibit_mod,
                        $prohibit_commercial,
                        $version, $opt1, $space1, $opt2);

            // 결과물 생성
            $text = $freeuse_image . sprintf($default_message, $version, $opt1, $space1, $opt2, $freeuse_title, $version, $option['freeuse_allow_commercial'][$freeuse_allow_commercial], $option['freeuse_allow_modification'][$freeuse_allow_modification]);

            $style = sprintf('<style type="text/css">.freeuse_license { clear:both; margin:20px auto 20px auto; padding:8px; width:%s;border:1px solid #c0c0c0; color:#808080; text-align:center; } .freeuse_license legend { font-weight:bold; } .freeuse_license a { color:#404040; text-decoration:none; } .freeuse_license a:hover { text-decoration:underline; </style>', $width);

            $output = sprintf('%s<fieldset class="freeuse_license"><legend>%s</legend>%s</fieldset>', $style, $freeuse_title, $text);

            return $output;
        }
    }

?>
