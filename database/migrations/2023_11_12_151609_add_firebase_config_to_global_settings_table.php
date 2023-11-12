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
        Schema::table('global_settings', function (Blueprint $table) {
            $table->string('firebase_api_key')->nullable();
            $table->string('firebase_auth_domain')->nullable();
            $table->string('firebase_project_id')->nullable();
            $table->string('firebase_storage_bucket')->nullable();
            $table->string('firebase_messaging_sender_id')->nullable();
            $table->string('firebase_app_id')->nullable();
            $table->string('firebase_measurement_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn('firebase_api_key');
            $table->dropColumn('firebase_auth_domain');
            $table->dropColumn('firebase_project_id');
            $table->dropColumn('firebase_storage_bucket');
            $table->dropColumn('firebase_messaging_sender_id');
            $table->dropColumn('firebase_app_id');
            $table->dropColumn('firebase_measurement_id');
        });
    }
};
