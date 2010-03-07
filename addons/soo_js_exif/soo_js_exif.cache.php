<?php
if(!defined('__ZBXE__')) exit();
// file soo_js_exif.cache.php
// author misol (misol@korea.ac.kr)
// brief Exif정보 캐시파일 생성
// ⓒ 2010 김민수.
if(md5($_SERVER['REMOTE_ADDR']) == Context::get("pc")) {

  $exif->ImageDescription = htmlspecialchars(strip_tags(Context::get("imagedescription")));// : "주제",
  $exif->Make = htmlspecialchars(strip_tags(Context::get("make"))); // : "카메라 제조사",
  $exif->Model = htmlspecialchars(strip_tags(Context::get("model")));// : "카메라 기종",
  $exif->ExposureTime = htmlspecialchars(strip_tags(Context::get("exposuretime")));// "노출 시간 (초)",
  $exif->ISOSpeedRatings = htmlspecialchars(strip_tags(Context::get("isospeedratings")));// "ISO 감도",
  $exif->ExposureProgram = htmlspecialchars(strip_tags(Context::get("exposureprogram")));// "노출 프로그램",
  $exif->ExposureBias = htmlspecialchars(strip_tags(Context::get("exposurebias")));// "노출 보정 (EV)",
  $exif->ExposureBiasValue = htmlspecialchars(strip_tags(Context::get("exposurebiasvalue")));// "노출 보정 값",
  $exif->BrightnessValue = htmlspecialchars(strip_tags(Context::get("erightnessvalue")));// "밝기",
  $exif->FocalLength = htmlspecialchars(strip_tags(Context::get("focallength")));// "초점 거리 (mm)",
  $exif->FocalLengthIn35mmFilm = htmlspecialchars(strip_tags(Context::get("focallengthin35mmfilm")));// "35mm 필름 초점 거리 (mm)",
  $exif->FNumber = htmlspecialchars(strip_tags(Context::get("fnumber")));// "F-스톱 (F-number)",
  $exif->MaxApertureValue = htmlspecialchars(strip_tags(Context::get("maxaperturevalue")));// "조리개 최대 개방",
  $exif->WhiteBalance = htmlspecialchars(strip_tags(Context::get("whitebalance")));// "화이트 밸런스",
  $exif->Flash = htmlspecialchars(strip_tags(Context::get("flash")));// "플래시",
  $exif->MeteringMode = htmlspecialchars(strip_tags(Context::get("meteringmode")));// "측광 모드",
  $exif->DateTimeOriginal = htmlspecialchars(strip_tags(Context::get("datetimeoriginal")));// "촬영일",
  $exif->DateTimeDigitized = htmlspecialchars(strip_tags(Context::get("datetimedigitized")));// "저장일",
  $exif->DateTime = htmlspecialchars(strip_tags(Context::get("datetime")));// "수정일",
  $exif->Software = htmlspecialchars(strip_tags(Context::get("software")));// "소프트웨어",
  $exif->SceneCaptureType = htmlspecialchars(strip_tags(Context::get("scenecapturetype")));// "촬영 모드",
  $exif->Contrast = htmlspecialchars(strip_tags(Context::get("contrast")));// "대비",
  $exif->Saturation = htmlspecialchars(strip_tags(Context::get("saturation")));// "채도",
  $exif->Sharpness = htmlspecialchars(strip_tags(Context::get("sharpness")));// "선명도",
  $exif->PixelXDimension = htmlspecialchars(strip_tags(Context::get("pixelxdimension")));// "가로 크기 (pixel)",
  $exif->PixelYDimension = htmlspecialchars(strip_tags(Context::get("pixelydimension")));// "세로 크기 (pixel)"
  $exif->image_file = htmlspecialchars(strip_tags(Context::get('image_file')));

  $exif_buff = serialize($exif);

  $exif_index = FileHandler::readFile('./files/cache/addons/soo_js_exif/index/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
  if($exif_index) {
    if($exif_index == $_SERVER['REMOTE_ADDR']) return;
    if($exif_index == 'saved') return;
    $cand_exif = FileHandler::readFile('./files/cache/addons/soo_js_exif/cand_data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
    if($cand_exif) {
      if($cand_exif == $exif_buff) {
        FileHandler::writeFile('./files/cache/addons/soo_js_exif/data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt', $exif_buff);
        FileHandler::writeFile('./files/cache/addons/soo_js_exif/index/'.Context::getLangType().'/'.md5($exif->image_file).'.txt', 'saved');
        FileHandler::removeFile('./files/cache/addons/soo_js_exif/cand_data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
      }
      else {
        FileHandler::removeFile('./files/cache/addons/soo_js_exif/index/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
        FileHandler::removeFile('./files/cache/addons/soo_js_exif/cand_data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
      }
    }
    else {
      FileHandler::removeFile('./files/cache/addons/soo_js_exif/index/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
      FileHandler::removeFile('./files/cache/addons/soo_js_exif/cand_data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt');
    }
  }
  else {
    FileHandler::writeFile('./files/cache/addons/soo_js_exif/cand_data/'.Context::getLangType().'/'.md5($exif->image_file).'.txt', $exif_buff);
    FileHandler::writeFile('./files/cache/addons/soo_js_exif/index/'.Context::getLangType().'/'.md5($exif->image_file).'.txt', $_SERVER['REMOTE_ADDR']);
  }
}
header("Content-Type: text/xml; charset=UTF-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$xmlDoc  = "<response>\n<error>0</error>\n<message>success</message>\n</response>";
echo $xmlDoc;
exit();
?>