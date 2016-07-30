<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class NewsSetupTables extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('news_popedom');
        Schema::dropIfExists('news_info_pics');
        Schema::dropIfExists('news_info');
        Schema::dropIfExists('news_class');

        Schema::create('news_class', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->unsignedInteger('sortnum')->default(0);
            $table->string('order_by', 20)->default('');
            $table->string('name', 50)->default('');
            $table->string('pic1', 100)->default('');
            $table->string('url', 100)->default('');
            $table->string('sort_by', 10)->default('');
            $table->tinyInteger('depth')->default(0);
            $table->tinyInteger('mode')->default(0);
            $table->tinyInteger('allow_add')->default(0);
            $table->tinyInteger('allow_edit')->default(0);
            $table->tinyInteger('allow_del')->default(0);
            $table->tinyInteger('has_subtitle')->default(0);
            $table->tinyInteger('has_tags')->default(0);
            $table->tinyInteger('has_intro')->default(0);
            $table->tinyInteger('has_content')->default(0);
            $table->tinyInteger('has_website')->default(0);
            $table->tinyInteger('has_editor')->default(0);
            $table->tinyInteger('has_author')->default(0);
            $table->tinyInteger('has_source')->default(0);
            $table->tinyInteger('has_pic1')->default(0);
            $table->tinyInteger('has_pic2')->default(0);
            $table->tinyInteger('has_pics')->default(0);
            $table->tinyInteger('has_file1')->default(0);
            $table->tinyInteger('has_hot')->default(0);
            $table->tinyInteger('has_new')->default(0);
            $table->tinyInteger('has_top')->default(0);
            $table->tinyInteger('has_recommend')->default(0);
        });

        Schema::create('news_popedom', function (Blueprint $table) {
            $table->string('class_id', 20);
            $table->integer('role_id')->unsigned();
            $table->unsignedTinyInteger('popedom')->default(0);

            $table->foreign('class_id')->references('id')->on('news_class')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['class_id', 'role_id']);
        });

        Schema::create('news_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class_id', 20)->default('');
            $table->unsignedInteger('sortnum')->default(0);
            $table->string('title', 100)->default('');
            $table->string('subtitle', 100)->default('');
            $table->string('title_color', 10)->default('');
            $table->string('title_bold', 10)->default('');
            $table->string('first_letter', 1)->default('');
            $table->string('website', 100)->default('');
            $table->string('tags', 200)->default('');
            $table->string('author', 30)->default('');
            $table->string('editor', 30)->default('');
            $table->string('source', 50)->default('');
            $table->string('publish_at', 20)->default('');
            $table->text('intro')->nullable();
            $table->text('content')->nullable();
            $table->string('pic1', 100)->default('');
            $table->string('pic2', 100)->default('');
            $table->string('file1', 100)->default('');
            $table->tinyInteger('is_top')->default(0);
            $table->tinyInteger('is_new')->default(0);
            $table->tinyInteger('is_hot')->default(0);
            $table->tinyInteger('is_recommend')->default(0);
            $table->tinyInteger('is_locked')->default(0);
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('comments')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_user_id')->default(0);
            $table->unsignedInteger('updated_user_id')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('class_id')->references('id')->on('news_class')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('news_info_pics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('info_id')->default(0);
            $table->string('title', 100)->default('');
            $table->text('content')->nullable();
            $table->string('pic1', 100)->default('');

            $table->foreign('info_id')->references('id')->on('news_info')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_popedom');
        Schema::dropIfExists('news_info_pics');
        Schema::dropIfExists('news_info');
        Schema::dropIfExists('news_class');
    }
}
