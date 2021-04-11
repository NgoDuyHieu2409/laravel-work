/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class Company {
    init() {
        this._on_change_city();
    }

    _on_change_city() {
       
    }
}

const company = new Company();
document.addEventListener('DOMContentLoaded', () => {
    company.init();
});



