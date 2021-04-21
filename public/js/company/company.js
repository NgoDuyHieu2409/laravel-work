/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class Company {
    init() {
        this._ajax_supmit_form();
    }

    _ajax_supmit_form(){
        $('.form-edit-add').on('submit', function (e) {
            e.preventDefault();
            $('div.validation-invalid-label').text("");
        
            $(this).ajaxSubmit({
                target: '',
                error: function (err) {
                    if (err.status === 422) {
                        Object.keys(err.responseJSON.errors).forEach(key => {
                            $('div.'+ key +'-error').text(err.responseJSON.errors[key][0]);
                        });
                    }
                    toastr.error('Đăng ký không thành công.');
                },
                success: function () {
                    toastr.success('Đăng ký thành công.');
                    setTimeout(function(){
                        window.location.replace('/admin/companies');
                    }, 1000);
                }
            })
        });
    }
}

const company = new Company();
document.addEventListener('DOMContentLoaded', () => {
    company.init();
});