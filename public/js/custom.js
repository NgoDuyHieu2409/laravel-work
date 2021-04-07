/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class Cusstom {
    init() {
        // this._set_up_firebase();
        // this._count_message();
        this._set_up_select2js();
        this._set_up_tinymce();
    }

    _set_up_select2js() {
        $('.select-search').select2();
    }

    _set_up_tinymce() {
        var additionalConfig = {
            selector: 'textarea.richTextBox',
            branding: false,
            height: '100',
        }
        tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
    }


    // _set_up_firebase(){
    //     var firebaseConfig = {
    //         apiKey: "AIzaSyCUacosWsb5BZSgs3Kb_BWhI_BLgz_u1uk",
    //         authDomain: "geenie-916e9.firebaseapp.com",
    //         databaseURL: "https://geenie-916e9.firebaseio.com",
    //         projectId: "geenie-916e9",
    //         storageBucket: "geenie-916e9.appspot.com",
    //         messagingSenderId: "875878913906",
    //         appId: "1:875878913906:web:a960fd97da00313a97afeb",
    //         measurementId: "G-1L71PSDKS4"
    //       };
    //     // Initialize Firebase
    //     firebase.initializeApp(firebaseConfig);
    //     firebase.analytics();

    // }


    // _count_message(){
    //     // users/{uid}/contracts/{romm_id}/messages
    //     var database = firebase.firestore();
    //     var total_messsage = {};
    //     var _this = this;
    //     $.ajax({
    //         url: '/messages/worker-ids',
    //         type: "GET",
    //         dataType: 'json',
    //     }).done(function(room_works) {
    //         for (const work_id in room_works){
    //             for (const worker_uid in room_works[work_id]) {
    //                 database.collection('users/' + worker_uid + '/contracts').onSnapshot(function(querySnapshot) {
    //                     querySnapshot.docChanges().forEach(function(contract) {
    //                         if(contract.doc.id == room_works[work_id][worker_uid]){
    //                             var time_read_at = contract.doc.data().read_last;
    //                             var url = 'users/' + worker_uid + '/contracts/' + room_works[work_id][worker_uid] + '/messages';
    //                             if(time_read_at === undefined){
    //                                 time_read_at = 1;
    //                             }

    //                             if(url){
    //                                 database.collection(url).orderBy('created_at').startAt(time_read_at).onSnapshot(function(snapshot) {
    //                                     var index = 0;
    //                                     snapshot.docChanges().forEach(function(message) {
    //                                         if(message.doc.data().is_admin === false){
    //                                             index +=1;
    //                                         }                                            
    //                                     });

    //                                     total_messsage[room_works[work_id][worker_uid]] = index;

    //                                     _this._total_message(total_messsage);
    //                                 });   
    //                             }
    //                         }
    //                     });
    //                 });
    //             }
    //         }
    //     });
    // }

    // _total_message(total){
    //     var count_total = 0;
    //     for (const key in total) {
    //         if (total.hasOwnProperty(key)) {
    //             count_total += total[key];
    //         }
    //     }
    //     $('#js-total-message').html(count_total);
    // }
}

const cusstom = new Cusstom();

document.addEventListener('DOMContentLoaded', () => {
    cusstom.init();
});