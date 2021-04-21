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
    }

    _set_up_tinymce() {
        var additionalConfig = {
            selector: 'textarea.richTextBox',
            branding: false,
            height: '100',
        }
        tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
    }
}

const adminCusstom = new AdminCusstom();

document.addEventListener('DOMContentLoaded', () => {
    adminCusstom.init();
});
