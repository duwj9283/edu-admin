<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserfilesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_folders');
        Schema::create('user_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('f_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('sort')->default(0);
            $table->string('name', 100)->default('');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });

        Schema::dropIfExists('user_files');
        Schema::create('user_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('folder_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->string('title', 100)->default('');
            $table->string('file1', 100)->default('');
            $table->string('orig_name', 100)->default('');
            $table->string('file_type', 50)->default('');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_ext', 10)->default('');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_folders');
        Schema::dropIfExists('user_files');
    }
}
