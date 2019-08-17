<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamp('banned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('name', 50)->unique();
            $table->string('title', 50);
            $table->string('component', '50');
            $table->string('icon', 50);
            $table->string('path', 50)->nullable();
            $table->string('permission')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_cache')->default(false);
            $table->timestamps();
        });

        Schema::create('admin_role_users', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::create('admin_user_permissions', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::create('admin_role_menu', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
        });

        Schema::create('admin_operation_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_menu');
        Schema::dropIfExists('admin_role_users');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_role_menu');
        Schema::dropIfExists('admin_operation_log');
    }
}
