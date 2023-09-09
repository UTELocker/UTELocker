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
        Schema::table('licenses', function (Blueprint $table) {
            $table->integer('warranty_duration')->after('locker_id');
        });

        $licenses = \App\Models\License::all();
        foreach ($licenses as $license) {
            $license->warranty_duration = 1;
            $license->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn('warranty_duration');
        });
    }
};
