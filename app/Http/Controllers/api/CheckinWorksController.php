<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorksCollection;
use App\Http\Resources\WorksCheckCollection;

use App\Work;
use App\WorkApplication;
use App\Worker;

use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Auth;
use Carbon\Carbon;

class CheckinWorksController extends ApiController
{

    private $auth;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Auth $auth)
    {
        // $this->middleware('auth:admin');
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        /*
           $token = $request->bearerToken();
           $verifiedIdToken = $this->auth->verifyIdToken($token);
           $uid = $verifiedIdToken->getClaim('sub');*/

        $uid = $request->uid;
        $worker = Worker::where('uid', $uid)->first();

        if (!$worker) {
            return $this->responseUnprocessable('ユーザが見つかりません。');
        }

        $workIds = WorkApplication::where([
            'status' => Config('const.WorkApplications.STATUS_ASSIGNED'),
            'worker_id' => $worker->id,
            'confirm_yn' => Config('const.WorkApplications.CONFIRM_STATUS.YES')
        ])->pluck('work_id');


        $query = DB::table('works');

        //開始時間と比較。30分前からチェックイン可能。
        $query->whereIn('id', $workIds)
            ->whereTime('worktime_start_at', '<=', date("Y-m-d H:i:s", strtotime("+" . Config('const.CHECKIN_BEFORE_MINUTE') . " minute")))
            ->orderBy('worktime_start_at', 'DESC');

        //必ず一つのみ
        if ($query->count()) {
            return new WorksCheckCollection($query->get());
        } else {
            return [
                'data' => array(),
            ];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
