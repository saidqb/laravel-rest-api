<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AppAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'Test',
                'code' => '910A01',
                'token' => Str::random(32),
                'whitlisted' => json_encode([
                    'website' => 'abc.com',
                    'ip' => '',
                ]),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'name' => 'diskominfo',
                'code' => '910A02',
                'token' => Str::random(32),
                'whitlisted' => json_encode([
                    'website' => 'abc.com',
                    'ip' => '',
                ]),
            ]
        ];
        \App\Models\AppAccess::insert($data);

        /* Membuat folder sesuai kode  */
        foreach ($data as $item) {
            $path = storage_path('app/private/' . $item['code'] . '/old');
            File::makeDirectory($path, $mode = 0777, true, true);
        }
    }
}
