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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('cancel_by')->nullable()->after('status');
            $table->foreign('cancel_by')->references('id')->on('users');
            $table->longText('cancel_reason')->nullable()->after('cancel_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('cancel_by');
            $table->dropColumn('cancel_reason');
        });
    }
};
