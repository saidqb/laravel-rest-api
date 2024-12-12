<?php

namespace App\Http\Core;

use Saidqb\LaravelSupport\Concerns\HasFile;


class UploadCore extends BaseCore
{

    protected function fileExtensionType($type)
    {
        $exstension = [
            'image' => [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'bmp',
                'webp',
                'svg',
                'ico'
            ],
            'document' => [
                'pdf',
                'doc',
                'docx',
                'xls',
                'xlsx',
                'ppt',
                'pptx',
                'txt',
                'csv',
                'rtf'
            ],
            'video' => [
                'mp4',
                'avi',
                'flv',
                'wmv',
                'mov',
                'webm',
                'mkv',
                '3gp',
                'mpg',
                'mpeg'
            ],
        ];

        $exstension['document_image'] = array_merge($exstension['image'], $exstension['document']);
        $exstension['all'] = array_merge($exstension['image'], $exstension['document'], $exstension['video']);

        if (isset($exstension[$type])) {
            return $exstension[$type];
        }
        return [];
    }

}
