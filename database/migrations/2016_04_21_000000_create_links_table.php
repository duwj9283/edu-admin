<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('link_tag');
        Schema::dropIfExists('links');
        Schema::dropIfExists('linktags');

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->string('url', 100)->default('');
            $table->string('pic1', 100)->default('');
            $table->tinyInteger('status')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });

        Schema::create('linktags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag', 50)->default('');
        });

        Schema::create('link_tag', function (Blueprint $table) {
            $table->integer('link_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->primary(['link_id', 'tag_id']);
            $table->foreign('link_id')->references('id')->on('links')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('linktags')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_tag');
        Schema::dropIfExists('links');
        Schema::dropIfExists('linktags');
    }
}
