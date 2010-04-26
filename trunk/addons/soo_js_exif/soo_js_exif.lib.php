<?php
if(!defined('__ZBXE__')) exit();
// file soo_js_exif.load.php
// author misol (misol@korea.ac.kr)
// brief Exif정보 함수파일 생성
// ⓒ 2010 김민수.
function oldXEMakeXmlDoc($obj) {
            if(!count($obj)) return;

            $xmlDoc = '';

            foreach($obj as $key => $val) {
                if(is_numeric($key)) $key = 'item';

                if(is_string($val)) $xmlDoc .= sprintf('<%s><![CDATA[%s]]></%s>%s', $key, $val, $key,"\n");
                else if(!is_array($val) && !is_object($val)) $xmlDoc .= sprintf('<%s>%s</%s>%s', $key, $val, $key,"\n");
                else $xmlDoc .= sprintf('<%s>%s%s</%s>%s',$key, "\n", $this->_makeXmlDoc($val), $key, "\n");
            }

            return $xmlDoc;

}
?>