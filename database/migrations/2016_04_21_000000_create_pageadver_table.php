<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageadverTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pageadver');
        Schema::create('pageadver', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100)->default('');
            $table->string('url', 100)->default('');
            $table->string('pic1', 100)->default('');
            $table->smallInteger('width')->default(0);
            $table->smallInteger('height')->default(0);
            $table->tinyInteger('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pageadver');
    }
}
