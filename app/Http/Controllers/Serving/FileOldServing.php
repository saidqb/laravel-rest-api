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

class FileOldServing extends FileCore
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

        $fidArr = explode('/', $fid);
        $accessCode = Arr::get($fidArr, '0');
        $pathReplaced = Str::replaceStart($accessCode . '/', '', $fid);


        $pathToFile = storage_path('app/private/' . $accessCode . '/old/' . $pathReplaced);

        if (!File::exists($pathToFile)) {
            return response()->view('file-errors.404');
        }

        $extension = Str::afterLast($pathToFile, '.');
        $filename = Str::afterLast($pathToFile, '/');




        $display = $req['display'] ?? 'view';
        $filename = $req['filename'] ?? $filename;


        $mime = Mimes::guessTypeFromExtension($extension);


        $headers = [
            'Content-Type' => $mime,
        ];

        if (in_array($extension, $this->fileExtensionType('image'))) {

            if ($display == 'view') {
                return response()->file($pathToFile, $headers);
            } else {
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
