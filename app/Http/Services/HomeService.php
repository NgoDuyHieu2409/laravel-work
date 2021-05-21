<?php

namespace App\Http\Services;

use App\Home;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeService
{
    public function editHome($request)
    {
        $home = Home::find(Auth::id());

        $url_photo = $request->file('photo_url') ? json_encode($this->uploadFiles($request->file('photo_url'), 'home')) : $home->photo_url;

        $params = [
            'company_id' => 1,
            'email' => $request->email,
            'name' => $request->name,
            'name_kana' => $request->name_kana,
            'contact_name' => $request->contact_name,
            'contact_name_kana' => $request->contact_name_kana,
            'sub_contact_name' => $request->sub_contact_name,
            'sub_contact_name_kana' => $request->sub_contact_name_kana,
            'certification_no' => $request->certification_no,
            'zipcode' => $request->zipcode1 . '-' . $request->zipcode2,
            'pref' => $request->pref,
            'city' => $request->city,
            'address1' => $request->address1,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'access' => $request->access,
            'website_url' => $request->website_url,
            'photo_url' => $url_photo,
            'description' => $request->description ,
            'type' => $request->type,
        ];


        $home->fill($params)->save();
    }

    public function uploadFiles($files, $folder)
    {
        if (!is_array($files)) {
            $files = array($files);
        }

        $urls = [];
        $folder = "/public/" . $folder;
        if ($files) {
            foreach ($files as $file) {
                $file_name = $file->getClientOriginalName();
                $type = $file->getClientOriginalExtension();
                $size = $file->getSize();
                $file_type = $file->getMimeType();

                $urls = [
                    'url' => Storage::putFile($folder, $file),
                    'file_name' => $file_name,
                ];
            }
            return $urls;
        }

        return false;
    }

}
