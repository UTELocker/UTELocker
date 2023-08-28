<?php

use App\Models\GlobalSetting;
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
        Schema::create('global_settings', function (Blueprint $table) {
            $table->id();
            $table->string('global_app_name')->default('UTELocker');
            $table->string('logo')->default('images/default/logo.png');
            $table->string('favicon')->default('images/default/favicon.png');
            $table->string('login_background')->default('images/default/login-background.jpg');
            $table->string('logo_background_color')->default('#ffffff');
            $table->string('header_color')->default('#ffffff');
            $table->string('sidebar_logo_style')->default(GlobalSetting::STYLE_SIDEBAR_LOGO_SQUARE);
            $table->string('locale')->default('en');
            $table->string('date_format')->default('d-m-Y');
            $table->string('time_format')->default('h:i a');
            $table->string('timezone')->default('Asia/Ho_Chi_Minh');
            $table->string('moment_format')->default('DD-MM-YYYY');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_settings');
    }
};
