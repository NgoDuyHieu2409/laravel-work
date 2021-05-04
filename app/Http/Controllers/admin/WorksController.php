<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

use App\Http\Services\WorkService;
use App\Http\Services\WorkRecordService;
use App\Models\Work;
use App\Models\WorkPhoto;
use App\Traits\AppUtility;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataAdded;
use Illuminate\Database\Eloquent\SoftDeletes;
use TCG\Voyager\Events\BreadDataUpdated;

class WorksController extends VoyagerBaseController
{
    use AppUtility;

    protected $workService;
    protected $workRecordService;

    public function __construct(
        WorkService $workService,
        WorkRecordService $workRecordService
    ){
        $this->workService = $workService;
        $this->workRecordService = $workRecordService;
    }

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);
        $user_id = Auth::id();

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        $searchNames = [];
        if ($dataType->server_side) {
            $searchable = SchemaManager::describeTable(app($dataType->model_name)->getTable())->pluck('name')->toArray();
            $dataRow = Voyager::model('DataRow')->whereDataTypeId($dataType->id)->get();
            foreach ($searchable as $key => $value) {
                $field = $dataRow->where('field', $value)->first();
                $displayName = ucwords(str_replace('_', ' ', $value));
                if ($field !== null) {
                    $displayName = $field->getTranslatedAttribute('display_name');
                }
                $searchNames[$value] = $displayName;
            }
        }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $model->{$dataType->scope}();
            } else {
                $query = $model::select('*');
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }
            $query = $query->where('user_id', $user_id);

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
                $query->where($search->key, $search_filter, $search_value);
            }

            if ($orderBy && in_array($orderBy, $dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $dataTypeContent = call_user_func([
                    $query->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'browse', $isModelTranslatable);

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($dataTypeContent->first())) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $dataTypeContent->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::user()->can('delete', app($dataType->model_name))) {
            $showCheckboxColumn = true;
        } else {
            foreach ($actions as $action) {
                if (method_exists($action, 'massAction')) {
                    $showCheckboxColumn = true;
                }
            }
        }

        // Count worker appliction to work
        foreach($dataTypeContent as $data){
            $data->recruitment_person_count = $data->recruitment_person_count . ' ứng viên';
            $this->workService->countWorkApplication($data);
        };

        // Define orderColumn
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, $sortOrder ?? 'desc']];
        }

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view, compact(
            'actions',
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn'
        ));
    }

    public function create(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'add', $isModelTranslatable);

        $view = 'voyager::bread.edit-add';
        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        $dataSelect = $this->getDataSelect2();

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'dataSelect'));
    }

    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
            $dataTypeContent->where('user_id', Auth::id())->first();
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'edit', $isModelTranslatable);
        
        $dataSelect = $this->getDataSelect2();
        $this->workService->formatDateTime($dataTypeContent);
        $dataTypeContent['tag_ids'] = $this->workService->getTagIds($dataTypeContent->id);
        $dataTypeContent['qualification_ids'] = $this->workService->getQualificationIds($dataTypeContent->id);
        $dataTypeContent['skill_ids'] = $this->workService->getSkillIds($dataTypeContent->id);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'dataSelect'));
    }

    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'read', $isModelTranslatable);

        // Convert data show
        $dataTypeContent = $this->workService->getDataWorkFormat($dataTypeContent);

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }

    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);
        $val->after(function ($val) use ($request) {
            $errors = $val->errors();
            $this->workValidation($errors, $request);
        });
        if ($val->fails()) {
            return back()->withErrors($val)->withInput();
        }
        
        $data = $this->saveData($request, $dataType, $slug, new $dataType->model_name());

        event(new BreadDataAdded($dataType, $data));

        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = $model->findOrFail($id);
        }

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $data = $this->saveData($request, $dataType, $slug, $data);

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    public function copy(Request $request, $copy = true)
    {
        $work = $this->workService->getDetail($request->work_id);
        $tag_ids = $this->workService->getTagIds($work->id);
        $qualification_ids = $this->workService->getQualificationIds($work->id);
        $skill_ids = $this->workService->getSkillIds($work->id);

        return view('home.works.create')->with(compact('work', 'tag_ids', 'qualification_ids', 'skill_ids', 'copy'));
    }

    public function workValidation($errors, $request)
    {

        $date_now = Carbon::parse();
        $resttime_start_at = $request->resttime_start_at ? Carbon::parse($request->resttime_start_at) : '';
        $resttime_end_at = $request->resttime_end_at ? Carbon::parse($request->resttime_end_at) : '';
        $resttime_minutes =  $request->resttime_minutes ?? 0;

        $worktime_start_at = Carbon::parse($request->worktime_start_at);
        $worktime_end_at = Carbon::parse($request->worktime_end_at);
        $diff_work_time = $worktime_start_at->floatDiffInHours($worktime_end_at);

        $diff_work_time_start = $worktime_start_at->floatDiffInHours($date_now);

        if(!$request->work_id){
            if(($diff_work_time_start < 5) || ($worktime_start_at < $date_now)){
                $errors = $errors->add('worktime_start_at', '「Thời gian bắt đầu công việc」 ngày và giờ bắt đầu trước ngày và giờ đăng ký mới 5 giờ.');
            }
        }

        if($diff_work_time * 60 - $resttime_minutes > 8*60){
            $errors = $errors->add('worktime_end_at', '「Thời gian làm việc」 không được vượt quá 8 giờ.');
        }

        if($diff_work_time < 1){
            $errors = $errors->add('worktime_end_at', '「Thời gian bắt đầu công việc」 quá gần thời gian hiện tại (1 giờ trở lên).');
        }

        if(($diff_work_time * 60 - $resttime_minutes) < 60){
            $errors = $errors->add('worktime_end_at', '「Thời gian làm việc」 quá ngắn (1 giờ trở lên).');
        }

        if($resttime_start_at){
            if($resttime_start_at < $worktime_start_at){
                $errors = $errors->add('resttime_start_at', '「Thời gian bắt đầu giải lao」 phải lớn hơn thời gian bắt đầu công việc.');
            }

            if($resttime_start_at > $worktime_end_at){
                $errors = $errors->add('resttime_start_at', '「Thời gian bắt đầu giải lao」 phải trước thời gian kết thúc công việc.');
            }
        }

        if($resttime_end_at && ($resttime_end_at > $worktime_end_at)){
            $errors = $errors->add('resttime_end_at', '「Thời gian kết thúc giải lao」 phải trước thời gian kết thúc công việc.');
        }

        if($resttime_start_at && $resttime_end_at){
            if($resttime_start_at > $resttime_end_at){
                $errors = $errors->add('resttime_end_at', '「Thời gian kết thúc giải lao」 phải lớn hơn hoặc bằng ngày "thời gian bắt đầu nghỉ giải lao".');
            }
        }
    }

    public function getDataSelect2()
    {
        $data = [
            'employments'    => $this->getItemStringToArray(setting('admin.employment_form')),
            'deadline_types' => $this->getItemStringToArray(setting('admin.deadline_types')),
            'categories' => $this->getItemStringToArray(setting('admin.categories')),
            'occupations' => $this->getItemStringToArray(setting('admin.occupations')),
            'tags' => array_chunk($this->getItemStringToArray(setting('admin.tags')), 4, true),
            'qualifications' => array_chunk($this->getItemStringToArray(setting('admin.qualifications')), 4, true),
            'skills' => array_chunk($this->getItemStringToArray(setting('admin.skills')), 4, true),
        ];
        
        return $data;
    }

    public function saveData($request, $dataType, $slug, $data)
    {
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, $data);
        $this->workService->createWorkTag($data->id, $request->tags);
        $this->workService->createWorkQualification($data->id, $request->qualifications);
        $this->workService->createWorkSkill($data->id, $request->skills);
        $this->workService->createWorkPhoto($data->id, $request->work_photo);

        return $data;
    }

    public function insertUpdateData($request, $slug, $rows, $data)
    {
        $multi_select = [];
        // Pass $rows so that we avoid checking unused fields
        $request->attributes->add(['breadRows' => $rows->pluck('field')->toArray()]);

        /*
         * Prepare Translations and Transform data
         */
        $translations = is_bread_translatable($data) ? $data->prepareTranslations($request) : [];

        foreach ($rows as $row) {
            // if the field for this row is absent from the request, continue
            // checkboxes will be absent when unchecked, thus they are the exception
            if (!$request->hasFile($row->field) && !$request->has($row->field) && $row->type !== 'checkbox') {
                // if the field is a belongsToMany relationship, don't remove it
                // if no content is provided, that means the relationships need to be removed
                if (isset($row->details->type) && $row->details->type !== 'belongsToMany') {
                    continue;
                }
            }

            // Value is saved from $row->details->column row
            if ($row->type == 'relationship' && $row->details->type == 'belongsTo') {
                continue;
            }

            $content = $this->getContentBasedOnType($request, $slug, $row, $row->details);

            if ($row->type == 'relationship' && $row->details->type != 'belongsToMany') {
                $row->field = @$row->details->column;
            }

            /*
             * merge ex_images/files and upload images/files
             */
            if (in_array($row->type, ['multiple_images', 'file']) && !is_null($content)) {
                if (isset($data->{$row->field})) {
                    $ex_files = json_decode($data->{$row->field}, true);
                    if (!is_null($ex_files)) {
                        $content = json_encode(array_merge($ex_files, json_decode($content)));
                    }
                }
            }

            if (is_null($content)) {

                // If the image upload is null and it has a current image keep the current image
                if ($row->type == 'image' && is_null($request->input($row->field)) && isset($data->{$row->field})) {
                    $content = $data->{$row->field};
                }

                // If the multiple_images upload is null and it has a current image keep the current image
                if ($row->type == 'multiple_images' && is_null($request->input($row->field)) && isset($data->{$row->field})) {
                    $content = $data->{$row->field};
                }

                // If the file upload is null and it has a current file keep the current file
                if ($row->type == 'file') {
                    $content = $data->{$row->field};
                    if (!$content) {
                        $content = json_encode([]);
                    }
                }

                if ($row->type == 'password') {
                    $content = $data->{$row->field};
                }
            }

            if ($row->type == 'relationship' && $row->details->type == 'belongsToMany') {
                // Only if select_multiple is working with a relationship
                $multi_select[] = [
                    'model'           => $row->details->model,
                    'content'         => $content,
                    'table'           => $row->details->pivot_table,
                    'foreignPivotKey' => $row->details->foreign_pivot_key ?? null,
                    'relatedPivotKey' => $row->details->related_pivot_key ?? null,
                    'parentKey'       => $row->details->parent_key ?? null,
                    'relatedKey'      => $row->details->key,
                ];
            } else {
                $data->{$row->field} = $content;
            }
        }

        if (isset($data->additional_attributes)) {
            foreach ($data->additional_attributes as $attr) {
                if ($request->has($attr)) {
                    $data->{$attr} = $request->{$attr};
                }
            }
        }

        $data->user_id = Auth::id();
        $data->status = $this->workService->setStatusApplication($data); 

        $data->save();

        // Save translations
        if (count($translations) > 0) {
            $data->saveTranslations($translations);
        }

        foreach ($multi_select as $sync_data) {
            $data->belongsToMany(
                $sync_data['model'],
                $sync_data['table'],
                $sync_data['foreignPivotKey'],
                $sync_data['relatedPivotKey'],
                $sync_data['parentKey'],
                $sync_data['relatedKey']
            )->sync($sync_data['content']);
        }

        // Rename folders for newly created data through media-picker
        if ($request->session()->has($slug.'_path') || $request->session()->has($slug.'_uuid')) {
            $old_path = $request->session()->get($slug.'_path');
            $uuid = $request->session()->get($slug.'_uuid');
            $new_path = str_replace($uuid, $data->getKey(), $old_path);
            $folder_path = substr($old_path, 0, strpos($old_path, $uuid)).$uuid;

            $rows->where('type', 'media_picker')->each(function ($row) use ($data, $uuid) {
                $data->{$row->field} = str_replace($uuid, $data->getKey(), $data->{$row->field});
            });
            $data->save();
            if ($old_path != $new_path && !Storage::disk(config('voyager.storage.disk'))->exists($new_path)) {
                $request->session()->forget([$slug.'_path', $slug.'_uuid']);
                Storage::disk(config('voyager.storage.disk'))->move($old_path, $new_path);
                Storage::disk(config('voyager.storage.disk'))->deleteDirectory($folder_path);
            }
        }

        return $data;
    }

    public function removeFiles(Request $request)
    {
        try {
            $work = Work::find($request->workID);

            if (Storage::get($request->url)) {
                $delete = Storage::delete($request->url);
                if($delete){
                    WorkPhoto::where('id', $request->id)->where('work_id', $request->workId)->delete();
                    return response()->json([
                        'data' => [
                            'status'  => 200,
                            'message' => __('voyager::media.file_removed'),
                        ],
                    ]);
                }

                return response()->json([
                    'data' => [
                        'status'  => 500,
                        'message' => __('voyager::generic.internal_error'),
                    ],
                ], 500);
                
            } else {
                throw new Exception(__('voyager::media.file_does_not_exist'), 400);
            }
        } catch (Exception $e) {
            $code = 500;
            $message = __('voyager::generic.internal_error');

            if ($e->getCode()) {
                $code = $e->getCode();
            }

            if ($e->getMessage()) {
                $message = $e->getMessage();
            }

            return response()->json([
                'data' => [
                    'status'  => $code,
                    'message' => $message,
                ],
            ], $code);
        }
    }
}
