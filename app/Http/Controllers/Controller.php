<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function uploadFiles(Request $request)
    {
        $file = $request->file('file');
        $file_name = $file->getClientOriginalName();
        $folder = $request->folder . '/' . date('F') . date('Y');
        $urls= [
            'url' => Storage::disk('public')->put($folder, $file, 'public'),
            'file_name' => $file_name,
        ];

        return response()->json(json_encode($urls, true));
    }
}
