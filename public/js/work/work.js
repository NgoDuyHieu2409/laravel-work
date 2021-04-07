/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class WorkJs {
    init() {
        this._set_upload_dropzone();
        this._on_change_resttime_minutes();
        this._button_show_confirm_delete_file();
        this._button_confirm_delete_file();
        this._button_add_input_file_upload();
        this._set_default_value_work_start_time();
        this._set_min_value_work_end_time();
    }

    _set_upload_dropzone() {
        
    }

    _on_change_resttime_minutes() {
        $('.js-resttime-minutes').on('change', function () {
            var resttime_start = $('input[name="resttime_start_at"]').val();
            var resttime_end = $('input[name="resttime_end_at"]').val();

            if(resttime_start && resttime_end) {
                resttime_start = new Date(resttime_start);
                resttime_end = new Date(resttime_end);
                var minutes = (resttime_end - resttime_start) / 1000 / 60;

                if(minutes < 0){
                    minutes = 0;
                }

                $('input[name="resttime_minutes"]').val(minutes);
            }
            else{
                $('input[name="resttime_minutes"]').val('');
            }
        });
    }

    _button_show_confirm_delete_file(){
        $('.delete_file_work').on('click', function (e) {
            $('#confirm_delete').data('url', $(this).data('url'));
            $('#confirm_delete').data('id', $(this).data('id'));
            $('#confirm_delete').data('work_id', $(this).data('work_id'));
            $('#confirm_delete_modal').modal('show');
        });
    }

    _button_confirm_delete_file(){
        $('#confirm_delete').on('click', function(){
            var params = {
                url: $(this).data('url'),
                id: $(this).data('id'),
                workId: $(this).data('work_id'),
            };

            $.post("/admin/work/remove-files", params, function (response) {
                if ( response && response.data  && response.data.status  && response.data.status == 200 ) {
                    toastr.success(response.data.message);
                    $('li#filr-list-'+ params.workId + '-' +  params.id).fadeOut();
                } else {
                    toastr.error("Error removing file.");
                }
            });

            $('#confirm_delete_modal').modal('hide');
        });
    }


    _button_add_input_file_upload(){
        var html = '<input type="file" class="form-control-uniform-custom @error("work_photo") is-invalid @enderror" name="work_photo[]"><br>';

        $('.js-add-file-upload-photo').on('click', function(){
            $('div.js-file-photo').append(html);

            $('.form-control-uniform-custom').uniform({
                fileButtonClass: 'action btn bg-pink-400',
                fileButtonHtml: 'ファイルを選択',
                fileDefaultHtml: '選択されていません',
            });
        });
    }

    _set_default_value_work_start_time(){
        if(KAIGO_MIN_WORK_TIME != null){
            var now = new Date(KAIGO_MIN_WORK_TIME);
            now = new Date(now.setHours(now.getHours() + 5));

            var year = now.getFullYear();
            var month = now.getMonth() + 1;
            if(month < 10 ){
                month = '0' + month;
            }

            var date = now.getDate();
            if(date < 10 ){
                date = '0' + date;
            }
            var hours = now.getHours();
            if(hours < 10 ){
                hours = '0' + hours;
            }
            var minutes = now.getMinutes();
            if(minutes < 10 ){
                minutes = '0' + minutes;
            }

            var min_work_time = year + '-' + month + '-' + date + 'T' + hours + ':' + minutes;
            $('input.js-work-time-start').attr('min', min_work_time);
        }
    }

    _set_min_value_work_end_time(){
        $('input.js-work-time-start').on('change', function(){
            var time_min = $(this).val();
            $('input[name="worktime_end_at"]').attr('min', time_min);
        });
    }
}

const work = new WorkJs();
document.addEventListener('DOMContentLoaded', () => {
    work.init();
});



