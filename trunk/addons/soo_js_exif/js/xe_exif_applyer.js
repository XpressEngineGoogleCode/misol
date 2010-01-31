    jQuery(document.body).ready(function () {
      jQuery("div.xe_content img").each(function () {
        if(!/modules|addons|classes|common|layouts|libs|widgets|widgetstyles/g.test(this.src)) {
          alert(EXIF.pretty(this));
        }
      });
    });
