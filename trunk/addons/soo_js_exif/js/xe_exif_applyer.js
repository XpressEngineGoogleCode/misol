// ⓒ 2010 김민수.
// This addon uses MPL License based librarys, Binary Ajax 0.1.7, EXIF Reader 0.1.4, and MIT License based library ImageInfo 0.1.2, which is written by Jacob Seidelin.
function SooExifInfoViewer(i) {

  if(!sooExif_imgurl[i]) return;

  if(sooExif_info_loader2[i]) {
    sooExif_printer(i);
  }
  else {
    if(!sooExif_info_loader2[i]) sooExif_info_loader2[i] = true;

  	sooExif_position[i] = sooExif_jquery_img[i].position();
	  imgtop = sooExif_position[i].top + 'px';
	  imgleft = sooExif_position[i].left + 'px';
    document.getElementById('sooExif'+i).style.display = 'block';
    document.getElementById('sooExif'+i).style.left = imgleft;
    document.getElementById('sooExif'+i).style.top = imgtop;

    var param = new Array();
	  param['image_file'] = sooExif_imgurl[i];
	  param['mid'] = current_mid;
    param['do'] = 'load';
    param['i'] = i;
    var response_tags = new Array('error','message','i','exif','pc');
    exec_xml('addon', 'soo_js_exif', param, SooExifLoader, response_tags);
  }


}

function SooExifLoader(ret_obj, response_tags) {
  var i = ret_obj['i'];
  if(!sooExif_pc) sooExif_pc = ret_obj['pc'];

  if(!ret_obj['exif']) {
	  file = sooExif_imgurl[i];
    ImageInfo.loadInfo(file, function() { sooExif_loop_controller(i) } )
  }
  else {
    sooExif_xml[i] = ret_obj['exif'];
    sooExif_loop_controller(i);
  }
}

function sooExif_printer(i) {
  if(!sooExif_imgurl[i]) return;
	if(typeof(sooExif_xml[i]) != "undefined") {
	  exif = sooExif_xml[i];
	}
	else {
	  exif = ImageInfo.getField(sooExif_imgurl[i], "exif");
	}
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

	if(typeof(sooExif_xml[i]) == "undefined") {
    sooExif_xml[i] = exif;
	  exif['pointer'] = i;
	  exif['image_file'] = sooExif_imgurl[i];
	  exif['mid'] = current_mid;
    exif['do'] = 'cache';
    exif['pc'] = sooExif_pc;
    var response_tags = new Array('error','message');
    exec_xml('addon', 'soo_js_exif', exif);
  }

	sooExif_position[i] = sooExif_jquery_img[i].position();
	imgtop = sooExif_position[i].top + 'px';
	imgleft = sooExif_position[i].left + 'px';

  if(!sooExif_layer_mode2[i]) {
    document.getElementById('sooExif'+i).style.display = 'block';
  }
  document.getElementById('sooExif'+i).style.left = imgleft;
  document.getElementById('sooExif'+i).style.top = imgtop;
  document.getElementById('sooExif'+i).innerHTML = exif_info;

  if(sooExif_info_loader[i]) return;

	jQuery('#sooExif'+i)
		.mouseenter(function(){
			sooExif_layer_mode = true;
			document.getElementById('sooExif'+i).style.display='block';
		})
		.mouseleave(function(){
			sooExif_layer_mode = false;
			document.getElementById('sooExif'+i).style.display="none";
		});
  sooExif_info_loader[i] = true;
}



function sooExif_loop_controller(i) {
	if(sooExif_info_loader[i]) {
	}
	else {
	  sooExif_imgload_checker_loop_loaded++;
	  if(sooExif_imgload_checker_loop_loaded > sooExif_imgload_checker) {
	  	ImageInfo = '';
	  	return;
	  }
	}

	sooExif_printer(i);
}
var sooExif_pc = '';
var sooExif_layer_mode = false;
var sooExif_layer_mode2 = [];
var sooExif_imgurl = [];
var sooExif_jquery_img = [];
var sooExif_xml = []
var sooExif_imgload_checker = 0;
var sooExif_imgload_checker_loop_loaded = 0;
var sooExif_position = [];
var sooExif_info_loader = [];
var sooExif_info_loader2 = [];
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
			sooExif_imgload_checker++;
			sooExif_imgurl[i] = this.src;
			sooExif_layer_mode2[i] = false;
			sooExif_info_loader[i] = false;
			sooExif_info_loader2[i] = false;
			sooExif_jquery_img[i]
			  .after('<span class=\'soo_exif\' id=\'sooExif'+i+'\' style="display:none; position:absolute;"><strong>Exif information loading...</strong><br /><img src="'+request_uri+'addons/soo_js_exif/image/loader.gif" alt="Now Loading..." /></span>')
			  .mouseenter(function(){
			    sooExif_layer_mode2[i] = false;
			    SooExifInfoViewer(i);
		    })
		    .mouseleave(function(){
		      sooExif_layer_mode2[i] = true;
		      if(sooExif_layer_mode) return;
			    document.getElementById('sooExif'+i).style.display="none";
		    });
		}
	});
});
