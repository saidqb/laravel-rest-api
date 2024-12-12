<?php

namespace App\Http\Controllers\Upload;

use Illuminate\Http\Request;
use App\Http\Core\UploadCore;

use App\Models\User;
use Saidqb\LaravelSupport\ResponseCode;
use Illuminate\Support\Facades\Hash;
use Saidqb\LaravelSupport\SQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

use Saidqb\LaravelSupport\Make\FilterQuery;
use Illuminate\Support\Str;

class Document extends UploadCore
{

    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {

        $req = $request->all();

        $fileMaxSize = 1024 * 5; // 5MB for validation
        $extensionMImes = implode(',', $this->fileExtensionType('document'));
        $validator = $this->validate($request, [
            'file' => sprintf('required|mimes:%s|max:%s', $extensionMImes, $fileMaxSize),
            'collection' => 'string',
            'category_id' => 'integer',
            'user_id' => 'integer',
            'is_private' => 'integer',

        ]);



        if ($validator->fails()) return $this->validationError($validator);

        $file = $request->file('file');
        $collection = $req['collection'] ?? 'untitled';
        $category_id = $req['category_id'] ?? 1;
        $client_user_id = $req['user_id'] ?? 0;
        $is_private = $req['is_private'] ?? 0;



        // $extension = $file->getClientOriginalExtension();
        $name = $file->hashName(); // Generate a unique, random name...
        $extension = $file->extension(); // Determine the file's extension based on the file's MIME type...
        $realPath = $file->getRealPath(); // Get the path to the file on the disk...
        $size = $file->getSize(); // Get the size of the uploaded file...
        $mimeType = $file->getMimeType(); // Get the MIME type of the file...

        $filename_no_ext = Str::of($file->getClientOriginalName())->replaceEnd('.' . $extension, '');
        $filename_original = STR::slug(strtolower(strip_tags($filename_no_ext))) . '.' . $extension;


        $access_code = $request->app_access->code;
        $date = date('Y/m');
        $dir = sprintf('%s/%s/%s', $access_code, substr($date, 0, 4), substr($date, 5, 2));

        $path = $file->store($dir);

        $fid = Str::random(160);
        $num = rand(35, 50);
        $fid = Str::of($fid)->substrReplace('-', $num, 0);
        $num = rand(95, 110);
        $fid = Str::of($fid)->substrReplace('-', $num, 0);

        DB::beginTransaction();
        try {
            $data = DB::table('files')->insert([
                'fid' => $fid,
                'user_id' => $request->app_access->user_id,
                'access_id' => $request->app_access->id,
                'category_id' => $category_id,
                'client_user_id' => $client_user_id,
                'mime_type' => $mimeType,
                'ext' => $extension,
                'collection_name' => $collection,
                'file_name_original' => $filename_original,
                'file_name' => $name,
                'directory' => $dir,
                'path' => $path,
                'disk' => 'private',
                'size' => $size,
                'extra' => json_encode([]),
                'is_private' => $is_private,
                'created_at' => now(),

            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }

        DB::commit();

        $type_file = 'image';
        if (in_array($extension, $this->fileExtensionType('document'))) {
            $type_file = 'document';
        }

        $data = [];
        $dataFiles = [];

        $itemData = [];
        if ($type_file == 'image') {
            $itemData['display'] = 'full';
        }

        $itemData['url'] = config('api.endpoint_file') . $access_code . '/' . $fid;
        $itemData['size'] = $size;
        $dataFiles[] = $itemData;

        $data[] = [
            'filename' => $filename_original,
            'extension' => $extension,
            'type' => $type_file,
            'mime_type' => $mimeType,
            'size' => $size,
            'data' => $dataFiles,
        ];

        return $this->response(['items' => $data, 'pagination' => false]);
    }
}
