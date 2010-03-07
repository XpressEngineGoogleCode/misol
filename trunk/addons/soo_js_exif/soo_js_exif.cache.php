<?php
if(!defined('__ZBXE__')) exit();
// file soo_js_exif.cache.php
// author misol (misol@korea.ac.kr)
// brief Exif���� ĳ������ ����
// �� 2010 ��μ�.
if(md5($_SERVER['REMOTE_ADDR']) == Context::get("pc")) {

  $exif->ImageDescription = htmlspecialchars(strip_tags(Context::get("imagedescription")));// : "����",
  $exif->Make = htmlspecialchars(strip_tags(Context::get("make"))); // : "ī�޶� ������",
  $exif->Model = htmlspecialchars(strip_tags(Context::get("model")));// : "ī�޶� ����",
  $exif->ExposureTime = htmlspecialchars(strip_tags(Context::get("exposuretime")));// "���� �ð� (��)",
  $exif->ISOSpeedRatings = htmlspecialchars(strip_tags(Context::get("isospeedratings")));// "ISO ����",
  $exif->ExposureProgram = htmlspecialchars(strip_tags(Context::get("exposureprogram")));// "���� ���α׷�",
  $exif->ExposureBias = htmlspecialchars(strip_tags(Context::get("exposurebias")));// "���� ���� (EV)",
  $exif->ExposureBiasValue = htmlspecialchars(strip_tags(Context::get("exposurebiasvalue")));// "���� ���� ��",
  $exif->BrightnessValue = htmlspecialchars(strip_tags(Context::get("erightnessvalue")));// "���",
  $exif->FocalLength = htmlspecialchars(strip_tags(Context::get("focallength")));// "���� �Ÿ� (mm)",
  $exif->FocalLengthIn35mmFilm = htmlspecialchars(strip_tags(Context::get("focallengthin35mmfilm")));// "35mm �ʸ� ���� �Ÿ� (mm)",
  $exif->FNumber = htmlspecialchars(strip_tags(Context::get("fnumber")));// "F-���� (F-number)",
  $exif->MaxApertureValue = htmlspecialchars(strip_tags(Context::get("maxaperturevalue")));// "������ �ִ� ����",
  $exif->WhiteBalance = htmlspecialchars(strip_tags(Context::get("whitebalance")));// "ȭ��Ʈ �뷱��",
  $exif->Flash = htmlspecialchars(strip_tags(Context::get("flash")));// "�÷���",
  $exif->MeteringMode = htmlspecialchars(strip_tags(Context::get("meteringmode")));// "���� ���",
  $exif->DateTimeOriginal = htmlspecialchars(strip_tags(Context::get("datetimeoriginal")));// "�Կ���",
  $exif->DateTimeDigitized = htmlspecialchars(strip_tags(Context::get("datetimedigitized")));// "������",
  $exif->DateTime = htmlspecialchars(strip_tags(Context::get("datetime")));// "������",
  $exif->Software = htmlspecialchars(strip_tags(Context::get("software")));// "����Ʈ����",
  $exif->SceneCaptureType = htmlspecialchars(strip_tags(Context::get("scenecapturetype")));// "�Կ� ���",
  $exif->Contrast = htmlspecialchars(strip_tags(Context::get("contrast")));// "���",
  $exif->Saturation = htmlspecialchars(strip_tags(Context::get("saturation")));// "ä��",
  $exif->Sharpness = htmlspecialchars(strip_tags(Context::get("sharpness")));// "����",
  $exif->PixelXDimension = htmlspecialchars(strip_tags(Context::get("pixelxdimension")));// "���� ũ�� (pixel)",
  $exif->PixelYDimension = htmlspecialchars(strip_tags(Context::get("pixelydimension")));// "���� ũ�� (pixel)"
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