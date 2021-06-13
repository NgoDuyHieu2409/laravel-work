/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

class AdminCusstom {
    init() {
        this._set_up_tinymce();
        this._worksTable();
        this._add_class_to_datatable();
        this._fix_min_height_content();
    }

    _set_up_tinymce() {
        var additionalConfig = {
            selector: 'textarea.richTextBox',
            branding: false,
            height: '100',
        }
        tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
    }

    _fix_min_height_content() {
        var h = window.innerHeight;
        var nav_top = $('nav.navbar-fixed-top').height();
        var footer = $('.app-footer').height();

        var content = (h - nav_top - footer) + 10;
        $('.side-body.padding-top').css('min-height', content);
    }

    _worksTable() {
        var _this = this;
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        $('.js-geenie-table').DataTable({
            ordering: false,
            scrollX: true,
            language: {
                search: '_INPUT_',
                searchPlaceholder: 'Tìm kiếm',
                lengthMenu: '_MENU_',
            },
        });

        $('.js-admin-table').DataTable({
            ordering: false,
            scrollX: true,
            language: {
                search: null,
                lengthMenu: null,
            },
        });
    };

    _add_class_to_datatable(){
        $('.dataTables_info').addClass('mb-0');
        $('.dataTables_info').parent().addClass('mb-0');
        $('.dataTables_paginate').addClass('mb-0');
        $('.dataTables_paginate').parent().addClass('mb-0');
        $('.dataTables_length').parent().addClass('mb-0');
        $('.dataTables_filter').parent().addClass('mb-0');

    }
}

const adminCusstom = new AdminCusstom();

document.addEventListener('DOMContentLoaded', () => {
    adminCusstom.init();
});
