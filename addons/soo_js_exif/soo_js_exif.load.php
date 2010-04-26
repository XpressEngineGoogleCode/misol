<?php
if(!defined('__ZBXE__')) exit();
// file soo_js_exif.load.php
// author misol (misol@korea.ac.kr)
// brief Exif정보 캐시파일 생성
// ⓒ 2010 김민수.
$exif->image_file = htmlspecialchars(strip_tags(Context::get('image_file')));
$exif->pointer = htmlspecialchars(strip_tags(Context::get('i')));

$exif_index = FileHandler::readFile('./files/cache/addons/soo_js_exif/index/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
if(!$exif_index || ($_SERVER['REMOTE_ADDR'] != $exif_index && $exif_index != 'saved')) {
  header("Content-Type: text/xml; charset=UTF-8");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  $xmlDoc  = "<response><error>0</error><message>success</message><i>".$exif->pointer."</i>\n<pc>".md5($_SERVER['REMOTE_ADDR'])."</pc></response>";
  echo $xmlDoc;
  exit();
}

if($exif_index == $_SERVER['REMOTE_ADDR']) {
  $cand_exif = FileHandler::readFile('./files/cache/addons/soo_js_exif/cand_data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');

  $cd_exif = unserialize($cand_exif);
  
  header("Content-Type: text/xml; charset=UTF-8");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  $xmlDoc  = "<response>\n<error>0</error>\n<message>success</message>\n<i>".$exif->pointer."</i>\n<exif>\n".oldXEMakeXmlDoc($cd_exif)."\n</exif>\n</response>";
  echo $xmlDoc;
  exit();
}
else if($exif_index == 'saved') {
  $exif_data = FileHandler::readFile('./files/cache/addons/soo_js_exif/data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
  $buff_exif = unserialize($exif_data);

  header("Content-Type: text/xml; charset=UTF-8");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  $xmlDoc  = "<response>\n<error>0</error>\n<message>success</message>\n<i>".$exif->pointer."</i>\n<exif>\n".oldXEMakeXmlDoc($buff_exif)."\n</exif>\n</response>";
  echo $xmlDoc;
  exit();

}
else {
  header("Content-Type: text/xml; charset=UTF-8");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  $xmlDoc  = "<response>\n<error>0</error>\n<message>success</message>\n<i>".$exif->pointer."</i>\n<pc>".md5($_SERVER['REMOTE_ADDR'])."</pc>\n</response>";
  echo $xmlDoc;
  exit();

}


?>