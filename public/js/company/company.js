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
        this._ajax_supmit_form();
        this._select_pref_when_edit();
    }

    _on_change_city() {
        $('#city').on('change', function () {
            var data = {
                _token: $('input[name="_token"]').val(),
                city_id: $(this).val()
            };

            $.ajax({
                type:'POST',
                dataType:'json',
                url:'/admin/get-districts',
                data: data,
                success:function(response){
                    $('#pref').html("");
                    $('#pref').append('<option value="">Vui lòng chọn quận huyện</option>')
                    for (const key in response) {
                        if (Object.hasOwnProperty.call(response, key)) {
                            $('#pref').append('<option value="' + key + '">' + response[key] + '</option>')
                        }
                    }
                }
             });
        });
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

    _select_pref_when_edit(){
        if(COMPANY_PREF){
            $('#city').change();
            setTimeout(function(){
                $('#pref').val(COMPANY_PREF).change();
            }, 2000);
        }
    }
}

const company = new Company();
document.addEventListener('DOMContentLoaded', () => {
    company.init();
});