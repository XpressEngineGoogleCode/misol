// ⓒ 2010 김민수.
// This addon uses MPL License based librarys, Binary Ajax 0.1.7, EXIF Reader 0.1.4, and MIT License based library ImageInfo 0.1.2, which is written by Jacob Seidelin.
function sooExif_printer(i) {
	exif = ImageInfo.getField(sooExif_imgurl[i], "exif");
	if(!exif) {
		return;
	}

	exif_info = '<strong>Exif Image Information</strong>';
    if(exif.ImageDescription) exif_info += "<br />" +"Image Description : " + exif.ImageDescription;
	if(exif.Model || exif.Make) exif_info += "<br />" +"Model : ";
		if(exif.Make) exif_info += exif.Make;
		if(exif.Model) exif_info += exif.Model; 
	if(exif.ExposureTime) exif_info += "<br />" +"Exposure Time : " + exif.ExposureTime;
	if(exif.ISOSpeedRatings) exif_info += "<br />" + "ISO Speed Ratings : " + exif.ISOSpeedRatings;
	if(exif.ExposureProgram) exif_info += "<br />" +"Exposure Program : " + exif.ExposureProgram;
	if(exif.ExposureBias) exif_info += "<br />" +"Exposure Bias : " + exif.ExposureBias;
	if(exif.ExposureBiasValue) exif_info += "<br />" +"Exposure Bias Value : " + exif.ExposureBiasValue;
	if(exif.BrightnessValue) exif_info += "<br />" +"Brightness Value : " + exif.BrightnessValue; 
	if(exif.FocalLength) exif_info += "<br />" +"Focal Length : " + exif.FocalLength;
	if(exif.FocalLengthIn35mmFilm) exif_info += "<br />" +"Focal Length in 35mm Film : " + exif.FocalLengthIn35mmFilm;
	if(exif.FNumber) exif_info += "<br />" +"F-Number : " + exif.FNumber;
	if(exif.MaxApertureValue) exif_info += "<br />" +"Maximum Aperture Value : " + exif.MaxApertureValue;
	if(exif.WhiteBalance) exif_info += "<br />" +"White Balance : " + exif.WhiteBalance;
	if(exif.Flash) exif_info += "<br />" +"Flash : " + exif.Flash;
	if(exif.MeteringMode) exif_info += "<br />" +"Metering Mode : " + exif.MeteringMode;
	if(exif.DateTimeOriginal) exif_info += "<br />" +"Date Time Original : " + exif.DateTimeOriginal;
	if(exif.DateTimeDigitized) exif_info += "<br />" +"Date Time Digitized : " + exif.DateTimeDigitized;
	if(exif.DateTime) exif_info += "<br />" +"Date Time : " + exif.DateTime;
	if(exif.Software) exif_info += "<br />" +"Software : " + exif.Software; 
	if(exif.SceneCaptureType) exif_info += "<br />" +"Scene Capture Type : " + exif.SceneCaptureType;
	if(exif.Contrast) exif_info += "<br />" +"Contrast : " + exif.Contrast;
	if(exif.Saturation) exif_info += "<br />" +"Saturation : " + exif.Saturation;
	if(exif.Sharpness) exif_info += "<br />" +"Sharpness : " + exif.Sharpness;
	if(exif.PixelXDimension) exif_info += "<br />" +"Pixel X-Dimension : " + exif.PixelXDimension;
	if(exif.PixelYDimension) exif_info += "<br />" +"Pixel Y-Dimension : " + exif.PixelYDimension;

	sooExif_position[i] = jQuery("div.xe_content img[src="+sooExif_imgurl[i]+"]").position();

	jQuery("div.xe_content img[src="+sooExif_imgurl[i]+"]")
		.after('<span class=\'soo_exif\' id=\'sooExif'+i+'\' style="display:none; position:absolute; top:'+sooExif_position[i].top+'; left:'+sooExif_position[i].left+'">'+exif_info+'</span>')
		.mouseenter(function(){
			document.getElementById('sooExif'+i).style.display='block';
			document.getElementById('sooExif'+i).style.position='absolute';
			document.getElementById('sooExif'+i).style.top=sooExif_position[i].top+'px';
			document.getElementById('sooExif'+i).style.left=sooExif_position[i].left+'px';
		})
		.mouseleave(function(){
			if(sooExif_layer_mode) return;
			document.getElementById('sooExif'+i).style.display="none";
		});
	jQuery('#sooExif'+i)
		.mouseenter(function(){
			sooExif_layer_mode = true;
			document.getElementById('sooExif'+i).style.display='block';
			document.getElementById('sooExif'+i).style.position='absolute';
			document.getElementById('sooExif'+i).style.top=sooExif_position[i].top+'px';
			document.getElementById('sooExif'+i).style.left=sooExif_position[i].left+'px';
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
	}
}

var sooExif_layer_mode = false;
var sooExif_imgurl = [];
var sooExif_imgload_checker = 0;
var sooExif_imgload_checker_loop_loaded = 0;
var sooExif_position = [];

jQuery(document.body).ready(function () {
	jQuery("div.xe_content img").each(function (i) {
		if(!/modules|addons|classes|common|layouts|libs|widgets|widgetstyles/g.test(this.src)) {
			file = this.src;
			sooExif_imgload_checker++;
			sooExif_imgurl[i] = this.src;
			ImageInfo.loadInfo(file, function() { sooExif_loop_controller(i) } );
		}
	});
});
