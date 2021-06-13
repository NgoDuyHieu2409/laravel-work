class ModifyRequests {
    init() {
        this._btn_refuse_modify_request();
        this._btn_approve_modify_request();
    }

    _btn_refuse_modify_request() {
        var _this = this;
        $('.js-btn-refuse-request:not(.disabled)').on('click', function () {
            $(this).addClass('disabled');
            var modify_id = $(this).data('id');
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: '/admin/modify_requests/refuse',
                data: {
                    '_token': _token,
                    'modify_id': modify_id
                },
                type: "POST",
                dataType: 'json',

            }).done(function (data) {
                if(data.status == "success"){
                    toastr.success(data.flash_message);
                }
                else{
                    toastr.error(data.flash_message);
                }
                setTimeout(function () {
                    location.reload();
                }, 2000);
            });
        })
    };

    _btn_approve_modify_request() {
        $('.js-btn-approve-request:not(.disabled)').on('click', function () {
            $(this).addClass('disabled');
            var modify_id = $(this).data('id');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: '/admin/modify_requests/approve',
                data: {
                    '_token': _token,
                    'modify_id': modify_id
                },
                type: "POST",
                dataType: 'json',

            })
            .done(function (data) {
                if(data.status == "success"){
                    toastr.success(data.flash_message);
                }
                else{
                    toastr.error(data.flash_message);
                }
                setTimeout(function () {
                    location.reload();
                }, 2000);
            });
        })
    }
}

const modifyRequests = new ModifyRequests();

document.addEventListener('DOMContentLoaded', () => {
    modifyRequests.init();
});
