<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50)->unique()->default('');
            $table->string('password', 100)->default('');
            $table->string('realname', 50)->default('');
            $table->string('mobile', 30)->default('');
            $table->string('email', 100)->default('');
            $table->tinyInteger('status')->default(0);
            $table->string('remember_token', 60)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('cardid', 30)->default('');
            $table->string('sex', 10)->default('');
            $table->string('prov', 50)->default('');
            $table->string('city', 50)->default('');
            $table->string('district', 50)->default('');
            $table->string('address', 200)->default('');
            $table->string('qq', 30)->default('');
            $table->string('motto', 100)->default('');
            $table->text('resumes')->nullable();
            $table->string('photo', 100)->default('');
            $table->unsignedBigInteger('quota')->default(0);
            $table->unsignedBigInteger('used_space')->default(0);

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
        Schema::drop('profiles');
    }
}
