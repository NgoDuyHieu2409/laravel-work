class ReviewDetail {
    init() {
        this._btn_like();
        this._btn_unlike();
        this._btn_favorite();
        this._componentAnimationCSS();
        this._componentLadda();
    }

    _btn_like() {
        $('.js-btn-like-worker').on('click', function(){
            $(this).parent().find('span.js-btn-dislike-worker').removeClass('kaigo-like');

            this.classList.toggle('kaigo-like');
            $(this).find('.input-checker-like-worker').trigger('click');
        });

        $('.input-checker-like-worker').on('click', function (e) {
            e.stopPropagation();
        });
    };

    _btn_unlike() {
        $('.js-btn-dislike-worker').on('click', function(){
            $(this).parent().find('span.js-btn-like-worker').removeClass('kaigo-like');

            this.classList.toggle('kaigo-like');
            $(this).find('.input-checker-dislike-worker').trigger('click');
        });

        $('.input-checker-dislike-worker').on('click', function (e) {
            e.stopPropagation();
        });
    };

    _btn_favorite() {
        $('.js-btn-favorite').on('click', function(){
            var $this = $(this);
            var data = {
                'worker_id': $(this).data('id'),
                '_token': $('input[name="_token"]').val()
            }

            $.post("/admin/favorite-worker/add-favorite", data, function (response) {})
            .done(function (data) {
                toastr.success(data.flash_message);
                $this.remove();
            });
        });
    };

    _componentAnimationCSS(){
        $('body').on('click', '.animation', function (e) {

            // Get animation class from 'data' attribute
            var animation = $(this).data('animation');

            // Apply animation once per click
            $(this).addClass('animated ' + animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated ' + animation);
            });
            e.preventDefault();
        });
    };

    _componentLadda() {
        if (typeof Ladda == 'undefined') {
            console.warn('Warning - ladda.min.js is not loaded.');
            return;
        }

        Ladda.bind('.btn-ladda-spinner', {
            dataSpinnerSize: 25,
            timeout: 2000
        });
    };
}

const review_detail = new ReviewDetail();

document.addEventListener('DOMContentLoaded', () => {
    review_detail.init();
});
