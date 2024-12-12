<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('fid', 200)->nullable()->index();
            $table->foreignId('user_id')->nullable()->index();
            $table->foreignId('access_id')->nullable()->index();
            $table->foreignId('category_id')->nullable()->index();
            $table->foreignId('client_user_id')->nullable()->index()->default(0);
            $table->string('mime_type')->nullable();
            $table->string('ext', 30)->nullable();
            $table->string('collection_name', 80)->nullable()->index();
            $table->string('file_name')->nullable()->nullable();
            $table->string('file_name_original')->nullable();
            $table->string('directory')->nullable();
            $table->string('path')->nullable();
            $table->string('disk')->nullable();
            $table->tinyInteger('is_private')->default(0);
            $table->unsignedBigInteger('size')->index();
            $table->json('extra')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
