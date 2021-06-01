class IndexMessages {
    init() {
        this._count_total_message_in_work();
    }
    _count_total_message_in_work(){
        var database = firebase.firestore();
        var count = {};
        var _this = this;
        if(typeof KAIGO_ROOM_IDS !== 'undefined') {
            for (const work_id in KAIGO_ROOM_IDS) {

                $.ajax({
                    url: '/admin/messages/worker-uids',
                    data: {'work_id':work_id},
                    type: "GET",
                    dataType: 'json',
                }).done(function(worker_uids) {
                    for (const room in KAIGO_ROOM_IDS[work_id]) {
                        for (const k in worker_uids) {
                            var url_contract = 'users/' + worker_uids[k] + '/contracts/';
                            var work = [];
                            //Count total messsage in work(is-admin = false)
                            database.collection(url_contract).onSnapshot(function(querySnapshot) {
                                querySnapshot.docChanges().forEach(function(contract) {
                                    if(contract.doc.id == KAIGO_ROOM_IDS[work_id][room]){
                                        var room_id = KAIGO_ROOM_IDS[work_id][room];
                                        var time_read_at = contract.doc.data().read_last;
                                        var url_doc = 'users/' + worker_uids[k] + '/contracts/' + room_id + '/messages';
                                        
                                        if(time_read_at === undefined){
                                            time_read_at = 1;
                                        }

                                        database.collection(url_doc).orderBy('created_at').startAt(time_read_at)
                                            .onSnapshot(function(snapshot) {
                                                var index = 0;
                                                snapshot.docChanges().forEach(function(message) {
                                                    if(message.doc.data().is_admin == false){
                                                        index +=1;
                                                    }
                                                });

                                                work[room_id] = index;
                                                count[work_id] = work;

                                                _this._total_message_by_work(count);
                                            });
                                    }
                                });
                            });

                            //Get time message last admin send
                            database.collection(url_contract).onSnapshot(function(querySnapshot) {
                                var time = '';
                                querySnapshot.docChanges().forEach(function(contract) {
                                    var url_doc = 'users/' + worker_uids[k] + '/contracts/' + KAIGO_ROOM_IDS[work_id][room] + '/messages';

                                    database.collection(url_doc).orderBy('created_at').get().then(function(snapshot) {
                                        snapshot.docChanges().forEach(function(message) {
                                            if(message.doc.data().is_admin == true){
                                                time = message.doc.data().created_at.toDate();
                                            }
                                        });

                                        if(time){
                                            var year = time.getFullYear();
                                            var month = time.getMonth();
                                            if(month < 10){
                                                month = '0' + month;
                                            }
                                            var day = time.getDate();
                                            if(day < 10){
                                                day = '0' + day;
                                            }

                                            var hours = time.getHours();
                                            if(hours < 10){
                                                hours = '0' + hours;
                                            }

                                            var minutes = time.getMinutes();
                                            if(minutes < 10){
                                                minutes = '0' + minutes;
                                            }

                                            var date = year + '年' + month + '月' + day + '日 ' + hours + ':' + minutes;
                                            $('.message_last_' + work_id).html(date);
                                        }
                                    });

                                });
                            });
                        }
                    }
                });
            }
        }
    }

    _total_message_by_work(total){
        for (const work_id in total) {
            var count = 0;
            if (total.hasOwnProperty(work_id)) {
                for (const key in total[work_id]) {
                    if (total[work_id].hasOwnProperty(key)) {
                        count += total[work_id][key];
                    }
                }
                $('.js-count-total-message-in-' + work_id).html(count);
            }
        }
    }
}

const index = new IndexMessages();

document.addEventListener('DOMContentLoaded', () => {
    index.init();
});
