// ⓒ 2010 김민수.
// This addon uses MPL License based librarys, Binary Ajax 0.1.7, EXIF Reader 0.1.4, and MIT License based library ImageInfo 0.1.2, which is written by Jacob Seidelin.
function sooExif_printer(i) {
  if(!sooExif_imgurl[i]) return;
	exif = ImageInfo.getField(sooExif_imgurl[i], "exif");
	if(!exif) {
		return;
	}

	exif_info = '<strong>Exif Image Information</strong>';
    for (var j in sooExif_pointer) {
      if(j == "ExposureTime") {
        if(exif[j]) {
          if(exif[j] < 1) {
            if(parseInt(1/exif[j], 10) == 1/exif[j]) {
              exif[j] = '1/' + 1/exif[j];
            }
          }
          exif_info += "<br />" + sooExif_pointer[j] + " : " + exif[j];
        }
      }
      else {
        if(exif[j]) exif_info += "<br />" + sooExif_pointer[j] + " : " + exif[j];
      }
	}
	sooExif_position[i] = sooExif_jquery_img[i].position();
	imgtop = sooExif_position[i].top + 'px';
	imgleft = sooExif_position[i].left + 'px';

	sooExif_jquery_img[i]
		.after('<span class=\'soo_exif\' id=\'sooExif'+i+'\' style="display:none; position:absolute; top:'+imgtop+'; left:'+imgleft+'">'+exif_info+'</span>')
		.mouseenter(function(){
			document.getElementById('sooExif'+i).style.display='block';
		})
		.mouseleave(function(){
			if(sooExif_layer_mode) return;
			document.getElementById('sooExif'+i).style.display="none";
		});
	jQuery('#sooExif'+i)
		.mouseenter(function(){
			sooExif_layer_mode = true;
			document.getElementById('sooExif'+i).style.display='block';
		})
		.mouseleave(function(){
			sooExif_layer_mode = false;
			document.getElementById('sooExif'+i).style.display="none";
		});
}



function sooExif_loop_controller(i) {
	sooExif_imgload_checker_loop_loaded++;
	if(sooExif_imgload_checker_loop_loaded > sooExif_imgload_checker) {
		ImageInfo = '';
		return;
	}
	sooExif_printer(i);
	if(sooExif_imgload_checker_loop_loaded == sooExif_imgload_checker) {
		ImageInfo = '';
		sooExif_imgurl = '';
    sooExif_jquery_img = '';
    sooExif_position = '';
    file = '';
    EXIF = '';
	}
}

var sooExif_layer_mode = false;
var sooExif_imgurl = [];
var sooExif_jquery_img = [];
var sooExif_imgload_checker = 0;
var sooExif_imgload_checker_loop_loaded = 0;
var sooExif_position = [];
var sooExif_pointer = {
  "ImageDescription" : "Image Description",
  "Make" : "Make",
  "Model" : "Model",
  "ExposureTime" : "Exposure Time",
  "ISOSpeedRatings" : "ISO Speed Ratings",
  "ExposureProgram" : "Exposure Program",
  "ExposureBias" : "Exposure Bias",
  "ExposureBiasValue" : "Exposure Bias Value",
  "BrightnessValue" : "Brightness Value",
  "FocalLength" : "Focal Length",
  "FocalLengthIn35mmFilm" : "Focal Length in 35mm Film",
  "FNumber" : "F-Number",
  "MaxApertureValue" : "Maximum Aperture Value",
  "WhiteBalance" : "White Balance",
  "Flash" : "Flash",
  "MeteringMode" : "Metering Mode",
  "DateTimeOriginal" : "Date Time Original",
  "DateTimeDigitized" : "Date Time Digitized",
  "DateTime" : "Date Time",
  "Software" : "Software",
  "SceneCaptureType" : "Scene Capture Type",
  "Contrast" : "Contrast",
  "Saturation" : "Saturation",
  "Sharpness" : "Sharpness",
  "PixelXDimension" : "Pixel X-Dimension",
  "PixelYDimension" : "Pixel Y-Dimension"
};

jQuery(document).ready(function () {
	if(typeof(soo_exif_StringValues) != "undefined") {
		EXIF.StringValues = soo_exif_StringValues;
		soo_exif_StringValues = '';
	}
	if(typeof(soo_exif_PointerLang) != "undefined") {
		sooExif_pointer = soo_exif_PointerLang;
		soo_exif_PointerLang = '';
	}

	jQuery("div.xe_content img").each(function (i) {
		if(!/modules|addons|classes|common|layouts|libs|widgets|widgetstyles/g.test(this.src)) {
			sooExif_jquery_img[i] = jQuery(this);
			file = this.src;
			sooExif_imgload_checker++;
			sooExif_imgurl[i] = this.src;
			ImageInfo.loadInfo(file, function() { sooExif_loop_controller(i) } );
		}
	});
});
