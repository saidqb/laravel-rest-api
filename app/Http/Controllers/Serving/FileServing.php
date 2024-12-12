<?php

namespace App\Http\Controllers\Serving;

use Illuminate\Http\Request;
use App\Http\Core\FileCore;

use App\Models\User;
use Saidqb\LaravelSupport\ResponseCode;
use Illuminate\Support\Facades\Hash;
use Saidqb\LaravelSupport\SQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

use Saidqb\LaravelSupport\Make\FilterQuery;
use Illuminate\Http\Response;
use Saidqb\CorePhp\Mimes;
use Illuminate\Support\Facades\File;

use Saidqb\LaravelSupport\Concerns\HasFile;

use function Laravel\Prompts\error;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FileServing extends FileCore
{
    use HasFile;

    public function __invoke(Request $request, $fid = NULL)
    {

        $validator = $this->validate($request, [
            'display' => 'in:download,view',
            'filename' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->view('file-errors.404');
        }

        $req = $request->all();

        $fid = strip_tags($fid);


        $fidArr = explode('/', $fid);
        $accessCode = Arr::get($fidArr, '0');
        // $urlAfterAccessCode = Str::replaceStart($accessCode . '/', '', $fid);
        $fidKey =  Arr::get($fidArr, '1');
        $sizeType =  Arr::get($fidArr, '2');

        // dd($fidKey);

        $app_access = DB::table('app_access')->where('code', $accessCode)->first();
        $db_file = DB::table('files')->where('access_id', $app_access?->id)->where('fid', $fidKey)->first();

        if (!$db_file) {
            return response()->view('file-errors.404');
        }

        $extension = $db_file->ext;
        $display = $req['display'] ?? 'view';
        $filename = $req['filename'] ?? $db_file->file_name;

        $pathToFile = storage_path('app/private/' . $db_file->path);

        $mime = Mimes::guessTypeFromExtension($db_file->ext);


        $headers = [
            'Content-Type' => $mime,
        ];

        if (in_array($extension, $this->fileExtensionType('image'))) {

            if ($display == 'view') {
                if ($sizeType == 'thumb') {
                    $extension = Str::afterLast($pathToFile, '.');
                    $pathFileWithoutExt = Str::of($pathToFile)->replaceEnd('.' . $extension, '');
                    $pathToFile = $pathFileWithoutExt . '_thumb.' . $extension;
                }
                return response()->file($pathToFile, $headers);
            } else {
                if ($sizeType == 'thumb') {
                    $extension = Str::afterLast($pathToFile, '.');
                    $pathFileWithoutExt = Str::of($pathToFile)->replaceEnd('.' . $extension, '');
                    $pathToFile = $pathFileWithoutExt . '_thumb.' . $extension;
                }
                return response()->download($pathToFile, $filename, $headers);
            }
        }

        if (in_array($extension, $this->fileExtensionType('document'))) {
            if ($extension != 'pdf') {
                return response()->download($pathToFile, $filename, $headers);
            }

            if ($display == 'view') {
                return response()->file($pathToFile, $headers);
            } else {
                return response()->download($pathToFile, $filename, $headers);
            }
        }
    }
}
