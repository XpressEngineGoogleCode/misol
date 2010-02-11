<?php
// @file ./addons/soo_js_exif/lang/ko.lang.php
// @author 민수 <misol@korea.ac.kr>
// @brief  Exif 정보 출력 한국어 파일
$lang->soo_exif_point = '
  "ImageDescription" : "주제",
  "Make" : "카메라 제조사",
  "Model" : "카메라 기종",
  "ExposureTime" : "노출 시간 (초)",
  "ISOSpeedRatings" : "ISO 감도",
  "ExposureProgram" : "노출 프로그램",
  "ExposureBias" : "노출 보정 (EV)",
  "ExposureBiasValue" : "노출 보정 값",
  "BrightnessValue" : "밝기",
  "FocalLength" : "초점 거리 (mm)",
  "FocalLengthIn35mmFilm" : "35mm 필름 초점 거리 (mm)",
  "FNumber" : "F-스톱 (F-number)",
  "MaxApertureValue" : "조리개 최대 개방",
  "WhiteBalance" : "화이트 밸런스",
  "Flash" : "플래시",
  "MeteringMode" : "측광 모드",
  "DateTimeOriginal" : "촬영일",
  "DateTimeDigitized" : "저장일",
  "DateTime" : "수정일",
  "Software" : "소프트웨어",
  "SceneCaptureType" : "촬영 모드",
  "Contrast" : "대비",
  "Saturation" : "채도",
  "Sharpness" : "선명도",
  "PixelXDimension" : "가로 크기 (pixel)",
  "PixelYDimension" : "세로 크기 (pixel)"
';


$lang->soo_exif_string = '
	ExposureProgram : {
		0 : "알 수 없음",
		1 : "수동",
		2 : "표준",
		3 : "조리개 우선",
		4 : "셔터 우선",
		5 : "창작 프로그램(필드의 깊이 중심)",
		6 : "동작 프로그램(셔터 속도 중심)",
		7 : "인물 모드",
		8 : "풍경 모드"
	},
	MeteringMode : {
		0 : "알 수 없음",
		1 : "평균",
		2 : "중앙",
		3 : "지점(Spot)",
		4 : "다중지점(MultiSpot)",
		5 : "패턴",
		6 : "부분",
		255 : "기타"
	},
	LightSource : {
		0 : "알 수 없음",
		1 : "햇빛",
		2 : "형광등",
		3 : "텅스텐(백열등)",
		4 : "플래시",
		9 : "맑은 날",
		10 : "흐린 날",
		11 : "그늘",
		12 : "주광색 형광등 (D 5700 - 7100K)",
		13 : "주백색 형광등 (N 4600 - 5400K)",
		14 : "냉백색 형광등 (W 3900 - 4500K)",
		15 : "백색 형광등 (WW 3200 - 3700K)",
		17 : "표준 광 A",
		18 : "표준 광 B",
		19 : "표준 광 C",
		20 : "D55",
		21 : "D65",
		22 : "D75",
		23 : "D50",
		24 : "ISO studio tungsten",
		255 : "기타"
	},
	Flash : {
		0x0000 : "플래시 끔",
		0x0001 : "플래시 켬",
		0x0005 : "섬광 반환하지 않는 플래시",
		0x0007 : "섬광 반환하는 플래시",
		0x0009 : "강제 플래시",
		0x000D : "섬광 반환하지 않는 강제 플래시",
		0x000F : "섬광 반환하는 강제 플래시",
		0x0010 : "강제 플래시 끔",
		0x0018 : "자동 플래시 끔",
		0x0019 : "자동 플래시",
		0x001D : "섬광 반환하지 않는 자동 플래시",
		0x001F : "섬광 반환하는 자동 플래시",
		0x0020 : "플래시 동작 안함",
		0x0041 : "적목 현상 플래시",
		0x0045 : "섬광 반환하지 않는 적목 현상 플래시",
		0x0047 : "섬광 반환하는 적목 현상 플래시",
		0x0049 : "적목 현상 강제 플래시",
		0x004D : "섬광 반환하지 않는 적목 현상 강제 플래시",
		0x004F : "섬광 반환하는 적목 현상 강제 플래시",
		0x0059 : "적목 현상 자동 플래시",
		0x005D : "섬광 반환하지 않는 적목 현상 자동 플래시",
		0x005F : "섬광 반환하는 적목 현상 자동 플래시"
	},
	SensingMethod : {
		1 : "Not defined",
		2 : "One-chip color area sensor",
		3 : "Two-chip color area sensor",
		4 : "Three-chip color area sensor",
		5 : "Color sequential area sensor",
		7 : "Trilinear sensor",
		8 : "Color sequential linear sensor"
	},
	SceneCaptureType : {
		0 : "일반",
		1 : "풍경",
		2 : "인물",
		3 : "야간 인물/야경"
	},
	SceneType : {
		1 : "Directly photographed"
	},
	CustomRendered : {
		0 : "Normal process",
		1 : "Custom process"
	},
	WhiteBalance : {
		0 : "자동",
		1 : "수동"
	},
	GainControl : {
		0 : "None",
		1 : "Low gain up",
		2 : "High gain up",
		3 : "Low gain down",
		4 : "High gain down"
	},
	Contrast : {
		0 : "보통",
		1 : "낮음",
		2 : "높음"
	},
	Saturation : {
		0 : "보통",
		1 : "낮은 채도",
		2 : "높은 채도"
	},
	Sharpness : {
		0 : "보통",
		1 : "낮음",
		2 : "높음"
	},
	SubjectDistanceRange : {
		0 : "알 수 없음",
		1 : "Macro",
		2 : "Close view",
		3 : "Distant view"
	},
	FileSource : {
		1 : "필름 스캐너",
    2 : "Reflection Print Scanner",
    3 : "디지털 카메라"
	},

	Components : {
		0 : "",
		1 : "Y",
		2 : "Cb",
		3 : "Cr",
		4 : "R",
		5 : "G",
		6 : "B"
	}
';
?>