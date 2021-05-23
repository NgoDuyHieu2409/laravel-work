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
    }

    _btn_review() {
        $('.btn-js-review-work').on('click', function(){
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
        });
    };
}

const review = new WorkReview();

document.addEventListener('DOMContentLoaded', () => {
    review.init();
});
