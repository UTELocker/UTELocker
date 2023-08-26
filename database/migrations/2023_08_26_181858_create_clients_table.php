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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('app_name')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
