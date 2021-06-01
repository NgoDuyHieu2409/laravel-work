/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class Cusstom {
    init() {
        this._set_up_firebase();
        this._count_message();
        this._set_up_defaut();
        this._on_change_city();
        this._select_pref_when_edit();
        this._btn_like();
        this._btn_unlike();
    }

    _set_up_defaut() {
        $('.select-search').select2();
        // inputmask phone
        $(".phone-number").inputmask({"mask": "99-9999-9999"});
    }

    _on_change_city() {
        $('.city-js').on('change', function () {
            var data = {
                _token: $('input[name="_token"]').val(),
                city_id: $(this).val()
            };

            $.ajax({
                type:'POST',
                dataType:'json',
                url:'/get-districts',
                data: data,
                success:function(response){
                    $('.pref-js').html("");
                    $('.pref-js').append('<option value="">Vui lòng chọn quận huyện</option>')
                    for (const key in response) {
                        if (Object.hasOwnProperty.call(response, key)) {
                            $('.pref-js').append('<option value="' + key + '">' + response[key] + '</option>')
                        }
                    }
                }
             });
        });
    }

    _select_pref_when_edit(){
        if(typeof(COMPANY_PREF) != "undefined" && COMPANY_PREF !== null){
            $('.city-js').change();
            setTimeout(function(){
                $('.pref-js').val(COMPANY_PREF).change();
            }, 2000);
        }
    }

    _btn_like() {
        $('.js-btn-like').on('click', function(){
            // Apply animation once per click
            var animation = $(this).data('animation');
            $(this).parents('.save_work').addClass('animated ' + animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated ' + animation);
            });

            $(this).css('display', 'none');
            $(this).parents('.save_work').find('.js-btn-dislike').css('display', 'unset');
            $(this).find('.input-checker-like').trigger('click');
        });

        $('.input-checker-like').on('click', function (e) {
            e.stopPropagation();
        });
    };

    _btn_unlike() {
        $('.js-btn-dislike').on('click', function(){
            // Apply animation once per click
            var animation = $(this).data('animation');
            $(this).parents('.save_work').addClass('animated ' + animation).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('animated ' + animation);
            });

            $(this).css('display', 'none');
            $(this).parents('.save_work').find('.js-btn-like').css('display', 'unset');
            $(this).find('.input-checker-dislike').trigger('click');
        });

        $('.input-checker-dislike').on('click', function (e) {
            e.stopPropagation();
        });
    };

    _set_up_firebase(){
        var firebaseConfig = {
            apiKey: "AIzaSyCjqCdfPCvS0AM5poJclu1pnN_o_SgF_uA",
            authDomain: "laravel-work.firebaseapp.com",
            projectId: "laravel-work",
            storageBucket: "laravel-work.appspot.com",
            messagingSenderId: "760758693441",
            appId: "1:760758693441:web:08b725af7b254a31b760c0",
            measurementId: "G-PX8LS1QQES"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();
    }


    _count_message(){
        // users/{uid}/contracts/{romm_id}/messages
        var database = firebase.firestore();
        var total_messsage = {};
        var _this = this;
        $.ajax({
            url: '/admin/messages/worker-ids',
            type: "GET",
            dataType: 'json',
        }).done(function(room_works) {
            for (const work_id in room_works){
                for (const worker_uid in room_works[work_id]) {
                    database.collection('users/' + worker_uid + '/contracts').onSnapshot(function(querySnapshot) {
                        querySnapshot.docChanges().forEach(function(contract) {
                            if(contract.doc.id == room_works[work_id][worker_uid]){
                                var time_read_at = contract.doc.data().read_last;
                                var url = 'users/' + worker_uid + '/contracts/' + room_works[work_id][worker_uid] + '/messages';
                                if(time_read_at === undefined){
                                    time_read_at = 1;
                                }

                                if(url){
                                    database.collection(url).orderBy('created_at').startAt(time_read_at).onSnapshot(function(snapshot) {
                                        var index = 0;
                                        snapshot.docChanges().forEach(function(message) {
                                            if(message.doc.data().is_admin === false){
                                                index +=1;
                                            }                                            
                                        });

                                        total_messsage[room_works[work_id][worker_uid]] = index;

                                        _this._total_message(total_messsage);
                                    });   
                                }
                            }
                        });
                    });
                }
            }
        });
    }

    _total_message(total){
        var count_total = 0;
        for (const key in total) {
            if (total.hasOwnProperty(key)) {
                count_total += total[key];
            }
        }
        $('#js-total-message').html(count_total);
    }
}

const cusstom = new Cusstom();

document.addEventListener('DOMContentLoaded', () => {
    cusstom.init();
});
