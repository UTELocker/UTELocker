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
        Schema::table('locker_slots', function (Blueprint $table) {
            $table->renameColumn('x', 'row');
            $table->renameColumn('y', 'column');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locker_slots', function (Blueprint $table) {
            $table->renameColumn('row', 'x');
            $table->renameColumn('column', 'y');
        });
    }
};
