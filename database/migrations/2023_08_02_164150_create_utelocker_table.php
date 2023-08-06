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
        if (!Schema::hasTable('site_groups')) {
            Schema::create('site_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('app_name')->nullable();
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('logo')->nullable();
                $table->string('favicon')->nullable();
                $table->string('login_background')->nullable();
                $table->text('address');
                $table->string('website')->nullable();
                $table->string('timezone')->default('Asia/Ho_Chi_Minh');
                $table->string('date_format', 20)->default('d-m-Y');
                $table->string('date_picker_format')->default('dd-mm-yyyy');
                $table->string('moment_format')->default('DD-MM-YYYY');
                $table->string('time_format', 20)->default('h:i a');
                $table->string('locale')->default('en');
                $table->decimal('latitude', 10, 8)->default(26.9124336);
                $table->decimal('longitude', 11, 8)->default(75.7872709);
                $table->timestamps();
            });

            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('site_group_id')->unsigned()->nullable();
                $table->string('name');
                $table->string('email')->nullable()->unique();
                $table->integer('user_type')->default(2);
                $table->string('password');
                $table->text('two_factor_secret')->nullable();
                $table->text('two_factor_recovery_codes')->nullable();
                $table->boolean('two_factor_confirmed')->default(false);
                $table->boolean('two_factor_email_confirmed')->default(false);
                $table->string('image')->nullable();
                $table->string('mobile')->nullable();
                $table->enum('gender', ['male', 'female', 'others'])->nullable();
                $table->string('locale')->default('en');
                $table->enum('status', ['active', 'deactive'])->default('active');
                $table->enum('login', ['enable', 'disable'])->default('enable');
                $table->text('onesignal_player_id')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });

            Schema::table('users', function (Blueprint $table) {
                $table->foreign('site_group_id')->references('id')
                    ->on('site_groups')->onDelete('cascade')->onUpdate('cascade');
            });

            Schema::create('locker_licenses', function (Blueprint $table) {
                $table->increments('id');
                $table->string('license_key')->nullable();
                $table->string('license_type')->nullable();
                $table->timestamps();
            });

            Schema::create('locations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('site_group_id')->unsigned()->nullable();
                $table->foreign('site_group_id')->references('id')
                    ->on('site_groups')->onDelete('cascade')->onUpdate('cascade');
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('status')->nullable();
                $table->string('image')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });

            Schema::create('lockers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('site_group_id')->unsigned()->nullable();
                $table->foreign('site_group_id')->references('id')
                    ->on('site_groups')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('locker_license_id')->unsigned()->nullable();
                $table->foreign('locker_license_id')->references('id')
                    ->on('locker_licenses')->onDelete('cascade')->onUpdate('cascade');
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('type')->nullable();
                $table->string('size')->nullable();
                $table->string('status')->nullable();
                $table->string('image')->nullable();
                $table->string('description')->nullable();
                $table->integer('location_id')->unsigned()->nullable();
                $table->foreign('location_id')->references('id')
                    ->on('locations')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utelocker');
    }
};
