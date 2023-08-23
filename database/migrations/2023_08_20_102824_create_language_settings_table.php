<?php

use App\Classes\CommonConstant;
use App\Models\LanguageSetting;
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
        Schema::create('language_settings', function (Blueprint $table) {
            $table->id();
            $table->string('language_code');
            $table->string('language_name');
            $table->string('flag_code')->nullable();
            $table->string('enabled')->default(CommonConstant::DATABASE_YES);
            $table->timestamps();
        });

        DB::table('language_settings')->insert(LanguageSetting::LANGUAGES);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_settings');
    }
};
