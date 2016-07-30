<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsercodeTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_codes');
        Schema::create('user_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100)->default('');
            $table->string('mobile', 30)->default('');
            $table->string('code', 100)->default('');
            $table->dateTime('expired_at')->nullable();
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
        Schema::dropIfExists('user_codes');
    }
}
