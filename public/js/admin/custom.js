/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class AdminCusstom {
    init() {
        this._set_up_tinymce();
        this._fix_min_height_content();
    }

    _set_up_tinymce() {
        var additionalConfig = {
            selector: 'textarea.richTextBox',
            branding: false,
            height: '100',
        }
        tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
    }

    _fix_min_height_content() {
        var h = window.innerHeight;
        var nav_top = $('nav.navbar-fixed-top').height();
        var footer = $('.app-footer').height();

        var content = (h - nav_top - footer) + 10;
        $('.side-body.padding-top').css('min-height', content);
    }
}

const adminCusstom = new AdminCusstom();

document.addEventListener('DOMContentLoaded', () => {
    adminCusstom.init();
});
