<x-app-layout>
    <form action="{{ route('mycv.store') }}" method="post" id="form-update-cv">
        @csrf

        <input type="hidden" name="isWorkHistory" value="">
        <input type="hidden" name="isEducation" value="">
        <input type="hidden" name="isSkill" value="">
        <input type="hidden" name="isLanguage" value="">
        <input type="hidden" name="isCertification" value="">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="row mt-2 mb-2">
                    <div class="col-sm-6">
                        <a href="{{ route('dashboard') }}" title="Back to Dashboard" class=""><i
                                class="fas fa-arrow-left"></i> Back to Dashboard</a>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Save CV</button>
                    </div>
                </div>

                {{-- contact --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h4 class="card-title"><b>General Information</b></h4>
                                <div class="card-tools">
                                    <span class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <img src="{{ $user->profile_photo_url ?? '' }}" alt="{{ $user->name }}"
                                            class="rounded-full h-40 w-40 object-cover">
                                    </div>
                                    <div class="col-sm-9">
                                        @include('homes.users.items.contact-information')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- work History --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h4 class="card-title">
                                    <b>Work Histories</b>
                                    <button type="button" class="btn btn-xs btn-info ml-3 btn_add_item"
                                        data-id="work-history">
                                        <i class="fas fa-plus-circle"></i>
                                        Add Work History
                                    </button>
                                </h4>
                                <div class="card-tools">
                                    <span class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 list-work-history-items">
                                        @include('homes.users.items.work-history')
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-xs btn-info btn_add_item" data-id="work-history">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Work History
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Education --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h4 class="card-title">
                                    <b>Educations</b>
                                    <button type="button" class="btn btn-xs btn-info ml-3 btn_add_item"
                                        data-id="education">
                                        <i class="fas fa-plus-circle"></i>
                                        Add Education
                                    </button>
                                </h4>
                                <div class="card-tools">
                                    <span class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 list-education-items">
                                        @include('homes.users.items.education')
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-xs btn-info btn_add_item" data-id="education">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Education
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Skills --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h4 class="card-title">
                                    <b>Skills</b>
                                    <button type="button" class="btn btn-xs btn-info ml-3 btn_add_item"
                                        data-id="skills">
                                        <i class="fas fa-plus-circle"></i>
                                        Add Skill
                                    </button>
                                </h4>
                                <div class="card-tools">
                                    <span class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 list-skills-items">
                                        @include('homes.users.items.skills')
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-xs btn-info btn_add_item" data-id="skills">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Skill
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Languages --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h4 class="card-title">
                                    <b>Languages</b>
                                    <button type="button" class="btn btn-xs btn-info ml-3 btn_add_item"
                                        data-id="language">
                                        <i class="fas fa-plus-circle"></i>
                                        Add Language
                                    </button>
                                </h4>
                                <div class="card-tools">
                                    <span class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 list-language-items">
                                        @include('homes.users.items.language')
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-xs btn-info btn_add_item" data-id="language">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Language
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Certifications --}}
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h4 class="card-title">
                                    <b>Certifications</b>
                                    <button type="button" class="btn btn-xs btn-info ml-3 btn_add_item"
                                        data-id="certifi">
                                        <i class="fas fa-plus-circle"></i>
                                        Add Certification
                                    </button>
                                </h4>
                                <div class="card-tools">
                                    <span class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 list-certifi-items">
                                        @include('homes.users.items.certification')
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-xs btn-info btn_add_item" data-id="certifi">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Certification
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2 mb-3">
                    <div class="col-sm-12 text-right">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Save CV</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            const COMPANY_PREF = @JSON($user->contact->district ?? 0);
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // editer
                $('.summernote_edit').summernote();

                $('.btn_add_item').on('click', function() {
                    var itemId = $(this).data('id');
                    switch (itemId) {
                        case "work-history":
                            $('input[name="isWorkHistory"]').val(1);
                            addWorkHistoryItem(itemId);
                            break;
                        case "education":
                            $('input[name="isEducation"]').val(1);
                            addEducationItem(itemId);
                            break;
                        case "skills":
                            $('input[name="isSkill"]').val(1);
                            addSkillsItem(itemId);
                            break;
                        case "language":
                            $('input[name="isLanguage"]').val(1);
                            addLanguageItem(itemId);
                            break;
                        case "certifi":
                            $('input[name="isCertification"]').val(1);
                            addCertificationItem(itemId);
                            break;
                        default:
                            break;
                    }
                });

                function addWorkHistoryItem(itemId) {
                    var dataIndex = $(".list-" + itemId + "-items .user_work_history_index:last-child").attr(
                        "data-index");
                    dataIndex = (dataIndex === undefined) ? 0 : parseInt(dataIndex) + 1;
                    console.log(dataIndex)
                    var temp =
                        `@include('homes.users.items.work-history', ['dataIndex' => '${dataIndex}', 'isAdd' => true])`;
                    $('.list-' + itemId + '-items').append(temp);
                    $('.summernote_edit').summernote();
                }

                function addEducationItem(itemId) {
                    var dataIndex = $(".list-" + itemId + "-items .user_education_index:last-child").attr(
                        "data-index");
                    dataIndex = (dataIndex === undefined) ? 0 : parseInt(dataIndex) + 1;
                    var temp =
                        `@include('homes.users.items.education', ['dataIndex' => '${dataIndex}', 'isAdd' => true])`;
                    $('.list-' + itemId + '-items').append(temp);
                    $('.summernote_edit').summernote();
                }

                function addSkillsItem(itemId) {
                    var dataIndex = $(".list-" + itemId + "-items .user_skill_index:last-child").attr("data-index");
                    dataIndex = (dataIndex === undefined) ? 0 : parseInt(dataIndex) + 1;
                    var temp =
                        `@include('homes.users.items.skills', ['dataIndex' => '${dataIndex}', 'isAdd' => true])`;
                    $('.list-' + itemId + '-items').append(temp);
                    $('.summernote_edit').summernote();
                }

                function addLanguageItem(itemId) {
                    var dataIndex = $(".list-" + itemId + "-items .user_language_index:last-child").attr(
                        "data-index");
                    dataIndex = (dataIndex === undefined) ? 0 : parseInt(dataIndex) + 1;
                    var temp =
                        `@include('homes.users.items.language', ['dataIndex' => '${dataIndex}', 'isAdd' => true])`;
                    $('.list-' + itemId + '-items').append(temp);
                    $('.select-search').select2();
                }

                function addCertificationItem(itemId) {
                    var dataIndex = $(".list-" + itemId + "-items .user_certification_index:last-child").attr(
                        "data-index");
                    dataIndex = (dataIndex === undefined) ? 0 : parseInt(dataIndex) + 1;
                    var temp =
                        `@include('homes.users.items.certification', ['dataIndex' => '${dataIndex}', 'isAdd' => true])`;
                    $('.list-' + itemId + '-items').append(temp);
                    $('.summernote_edit').summernote();
                }

                $('#form-update-cv').on('submit', function (e) {
                    e.preventDefault();
                    $('div.validation-invalid-label').text("");

                    $(this).ajaxSubmit({
                        target: '',
                        error: function (err) {
                            if (err.status === 422) {
                                Object.keys(err.responseJSON.errors).forEach(key => {
                                    $('span.'+ key +'-error').text(err.responseJSON.errors[key][0]);
                                });
                            }
                            toastr.error('Cập nhật thất bại.');
                        },
                        success: function () {
                            toastr.success('Cập nhật hồ sơ thành công.');
                            setTimeout(function(){
                                location.reload();
                            }, 1500);
                        }
                    })
                });
            })
        </script>
    @endpush
</x-app-layout>
