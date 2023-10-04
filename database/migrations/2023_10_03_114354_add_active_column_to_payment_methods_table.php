<?php

use App\Classes\CommonConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->char('active')->default(CommonConstant::DATABASE_NO)->after('name');
        });

        DB::transaction(function () {
            DB::table('payment_methods')->update(['active' => CommonConstant::DATABASE_YES]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
