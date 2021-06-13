/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class WorkReview {
    init() {
        this._btn_review();
        this._btn_request();
        this._disabled_custom();
    }

    _btn_review() {
        $('.btn-js-review-work').on('click', function(){
            var confirm = window.confirm("Bạn có muốn đánh giá và kết thúc công việc hiện tại?");
            if(confirm == true) {
                var work_id = $(this).data('id');
                var form = '#home-review-' + work_id;
                var data = {
                    _token: $('input[name="_token"]').val(),
                    work_id : work_id,
                    good_yn1 : $(form + ' input[name="good_yn1"]').val(),
                    good_yn2 : $(form + ' input[name="good_yn2"]').val(),
                    good_yn3 : $(form + ' input[name="good_yn3"]').val(),
                    comment : $(form + ' textarea[name="comment"]').val(),
                };

                $.post('/worker-review', data, function(result){
                    if(result.status == true) {
                        toastr.success(result.message)
                        setTimeout(function(){
                            location.reload();
                        }, 1500);
                    }
                    else {
                        toastr.error(result.message)
                    }
                });
            }
        });
    };

    _btn_request() {
        $('.btn-js-modify-request').on('click', function(){
            var confirm = window.confirm("Bạn có muốn gửi yêu cầu này tới quản lý không?");
            if(confirm == true) {
                var work_id = $(this).data('id');
                var form = '#modify-request-' + work_id;

                var modify_worktime_start_at = $(form + ' input[name="modify_worktime_start_at"]').val();
                var modify_worktime_end_at = $(form + ' input[name="modify_worktime_end_at"]').val();
                var resttime_minutes = $(form + ' input[name="resttime_minutes"]').val();
                var comment = $(form + ' textarea[name="comment"]').val();

                if(modify_worktime_start_at || modify_worktime_end_at || comment){
                    var data = {
                        _token: $('input[name="_token"]').val(),
                        work_id : work_id,
                        modify_worktime_start_at : modify_worktime_start_at,
                        modify_worktime_end_at : modify_worktime_end_at,
                        resttime_minutes : resttime_minutes,
                        comment : comment,
                    };
    
                    $.post('/worker-request', data, function(result){
                        if(result.status == true) {
                            toastr.success(result.message)
                            setTimeout(function(){
                                location.reload();
                            }, 1500);
                        }
                        else {
                            toastr.error(result.message)
                        }
                    });
                }
                else {
                    toastr.error('Gửi yêu cầu thất bại');
                }
            }
        });
    };

    _disabled_custom(){
        if(HOME_IS_REVIEW == 1){
            $('.list-work-review').find('input, textarea').prop('disabled', true);
        }
    }
}

const review = new WorkReview();

document.addEventListener('DOMContentLoaded', () => {
    review.init();
});
