/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class FavoriteWork {
    init() {
        this._btn_like();
        this._btn_unlike();
    }

    _btn_like() {
        var _this = this;
        $('.js-btn-like').on('click', function(){
            var data = {
                _token: $('input[name="_token"]').val(),
                work_id : $(this).data('work'),
            } 
            // Apply animation once per click
            var animation = $(this).data('animation');
            $(this).parents('.save_work').addClass('animated ' + animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated ' + animation);
            });

            $(this).css('display', 'none');
            $(this).parents('.save_work').find('.js-btn-dislike').css('display', 'unset');
            $(this).find('.input-checker-like').trigger('click');
            
            _this._ajax_favorite_work('/favorite', data);
        });

        $('.input-checker-like').on('click', function (e) {
            e.stopPropagation();
        });
    };

    _btn_unlike() {
        var _this = this;
        $('.js-btn-dislike').on('click', function(){
            var data = {
                _token: $('input[name="_token"]').val(),
                work_id : $(this).data('work'),
            };
            // Apply animation once per click
            var animation = $(this).data('animation');
            $(this).parents('.save_work').addClass('animated ' + animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated ' + animation);
            });

            $(this).css('display', 'none');
            $(this).parents('.save_work').find('.js-btn-like').css('display', 'unset');
            $(this).find('.input-checker-dislike').trigger('click');

            _this._ajax_favorite_work('/unfavorite', data);
        });

        $('.input-checker-dislike').on('click', function (e) {
            e.stopPropagation();
        });

    };

    _ajax_favorite_work(url, data){
        $.post(url, data,
            function(data){
                if(data.status == true) {
                    toastr.success(data.message)
                }
                else {
                    toastr.error(data.message)
                }
            }
        );
    };
}

const favorite = new FavoriteWork();

document.addEventListener('DOMContentLoaded', () => {
    favorite.init();
});
