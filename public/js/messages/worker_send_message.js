class WorkerSendMessages {
    init() {
        this._auto_scroll_top();
        this._event_keypress_send_message();
        this._get_message_from_firebase();
        this._btn_send_message();
        this._get_list_message_from_worker_firebase();
        this._count_message_read_at();
    }

    _auto_scroll_top(){
        $('.js-list-messages').animate({
            scrollTop: $('.js-list-messages')[0].scrollHeight
        }, 0);
    }

    _event_keypress_send_message(){
        $("textarea.js-input-comment").keypress(function(event){
            if(event.keycode || event.which == 13){
                if(!event.shiftKey){
                    $('.js-btn-send-message').trigger('click');
                    event.preventDefault();
                }
            }
        });
    }

    _btn_send_message(){
        var _this = this;
        $('.js-btn-send-message').on('click', function(){
            var comment = $('textarea[name="comment"]').val();
            if(comment.match(/[^\n]/)){
                var data = {
                    comment: comment,
                    created_at: new Date(),
                    is_admin: false,
                };

                $('textarea.js-input-comment').val('');
                _this._set_data_from_firebase(data);
                _this._auto_scroll_top();
            }
        });
    }

    _get_message_from_firebase(){
        var _this = this;
        var url_database = 'users/' + KAIGO_WORKER_UID + '/contracts/' + KAIGO_ROOM_ID + '/messages';
        firebase.firestore().collection(url_database).orderBy('created_at')
        .onSnapshot(function(snapshot) {
            snapshot.docChanges().forEach(function(message) {
                var data = message.doc.data();

                var date = data.created_at.toDate();
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                if(month < 10){
                    month = '0' + month;
                }
                var day = date.getDate();
                if(day < 10){
                    day = '0' + day;
                }

                var hours = date.getHours();
                if(hours < 10){
                    hours = '0' + hours;
                }

                var minutes = date.getMinutes();
                if(minutes < 10){
                    minutes = '0' + minutes;
                }

                if(data.is_admin == false){
                    var html = '<li class="media media-chat-item-reverse">'
                            + '<div class="media-body">'
                            + '<div class="media-chat-item">' + data.comment + '</div>'
                            + '<div class="font-size-sm text-muted mt-2">' + day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + '</div>'
                            + '</div>'
                            + '<div class="ml-3">'
                            + '<a href="' + KAIGO_WORKER_AVATAR + '">'
                            + '<img src="' + KAIGO_WORKER_AVATAR + '" class="rounded-circle elevation-2" width="40" height="40" alt="">'
                            + '</a>'
                            + '</div>'
                            + '</li>';
                }
                else{
                    var html = '<li class="media">'
							+ '<div class="mr-3">'
							+ '<a href="' + KAIGO_USER_AVATAR + '">'
							+ '<img src="' + KAIGO_USER_AVATAR + '" class="rounded-circle elevation-2" width="40" height="40" alt="">'
							+ '</a>'
							+ '</div>'
						    + '<div class="media-body">'
							+ '<div class="media-chat-item">' + data.comment + '</div>'
							+ '<div class="font-size-sm text-muted mt-2">' + day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + '</div>'
							+ '</div>'
							+ '</li>';
                }

                $('.js-list-messages').append(html);
                _this._count_message_read_at();
                _this._get_list_message_from_worker_firebase();
                _this._auto_scroll_top();
            });
        });

    }

    _set_data_from_firebase(data) {
        var database = firebase.firestore();
        database.collection('users/' + KAIGO_WORKER_UID + '/contracts/').doc(KAIGO_ROOM_ID).set({
            work_last: new Date(),
        }, { merge: true });    

        var url_database = 'users/' + KAIGO_WORKER_UID + '/contracts/' + KAIGO_ROOM_ID + '/messages';
        database.collection(url_database).doc().set(data);
    }

    _get_list_message_from_worker_firebase(){
        // users/{uid}/contracts/{romm_id}/messages
        for (const key in KAIGO_WORK_IDS) {
            if (KAIGO_WORK_IDS.hasOwnProperty(key)) {
                $.ajax({
                    url: '/admin/messages/room',
                    data: {
                        work_id: KAIGO_WORK_IDS[key],
                        worker_id: KAIGO_WORKER_UID
                    },
                    type: "GET",
                    dataType: 'json',
                }).done(function(room_id) {
                    var url_database = 'users/' + KAIGO_WORKER_UID + '/contracts/' + room_id + '/messages';
                    firebase.firestore().collection(url_database).where('is_admin', '==', true).orderBy('created_at')
                    .onSnapshot(function(snapshot) {
                        snapshot.docChanges().forEach(function(message) {
                            var data = message.doc.data();
                            if(data){
                                $('.js-comment-worker-' + KAIGO_WORK_IDS[key]).html(data.comment);
                            }
                            else{
                                $('.js-comment-worker-' + KAIGO_WORK_IDS[key]).html('Không có tin nhắt');
                            }
                        });
                    });
                });
            }
        }
    }

    _count_message_read_at(){
        // users/{uid}/contracts/{romm_id}/messages
        var database = firebase.firestore();

        for (const key in KAIGO_WORK_IDS) {
            if (KAIGO_WORK_IDS.hasOwnProperty(key)) {
                for (const k in KAIGO_ROOM_IDs) {
                    database.collection('users/' + KAIGO_WORKER_UID + '/contracts/')
                    .onSnapshot(function(querySnapshot) {
                        var url = '';
                        querySnapshot.docChanges().forEach(function(contract) {
                            if(contract.doc.id == KAIGO_ROOM_IDs[k]){
                                var time_read_at = contract.doc.data().work_last;
                                if(time_read_at === undefined){
                                    time_read_at = 1;
                                }
                                
                                url = 'users/' + KAIGO_WORKER_UID + '/contracts/' + KAIGO_ROOM_IDs[k] + '/messages';
                                database.collection(url).orderBy('created_at').startAt(time_read_at)
                                .onSnapshot(function(snapshot) {
                                    var index = 0; 
                                    snapshot.docChanges().forEach(function(message) {
                                        $('.js-count-message-' + KAIGO_WORKER_UID + '-' + KAIGO_WORK_IDS[key]).html('');
                                        if(message.doc.data().is_admin === true){
                                            index +=1;
                                        }
                                    });

                                    if(index > 0){
                                        $('.js-count-message-' + KAIGO_WORKER_UID + '-' + KAIGO_WORK_IDS[key]).html(index);
                                    }
                                }); 
                            }
                        });
                    });
                }
            }

        }
    }
}

const workerMessages = new WorkerSendMessages();

document.addEventListener('DOMContentLoaded', () => {
    workerMessages.init();
});
