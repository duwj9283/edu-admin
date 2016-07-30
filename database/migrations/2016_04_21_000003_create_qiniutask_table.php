<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQiniutaskTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('qiniutask');
        Schema::create('qiniutask', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table_name', 100)->default('');
            $table->string('field_name', 100)->default('');
            $table->unsignedInteger('master_id')->default(0);
            $table->string('file_path', 100)->default('');
            $table->string('target_path', 100)->default('');
            $table->tinyInteger('need_upload')->default(0);
            $table->tinyInteger('need_convert')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('qiniutask');
    }
}
