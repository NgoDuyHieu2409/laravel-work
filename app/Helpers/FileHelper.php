<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function uploadFile($_attributeName, $_disk = 'root', $_destinationPath, $_value, $_saveWithFullPath = true)
    {
        // if the file input is empty
        if (is_null($_value)) {
            return null;
        }
        $request = \Request::instance();
        // if a new file is uploaded, store it on disk and its filename in the database
        if ($request->hasFile($_attributeName) && $request->file($_attributeName)->isValid()) {
            // 1. Generate a new file name
            $file = $request->file($_attributeName);
            $new_file_name = md5($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();

            // 2. Move the new file to the correct path
            Storage::disk($_disk)->putFileAs($_destinationPath, $file, $new_file_name);
            // 3. Save the complete path to the database
            if ($_saveWithFullPath) {
                return $_destinationPath . '/' . $new_file_name;
            } else {
                return $new_file_name;
            }
        }
    }
}
