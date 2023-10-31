<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Classes\CommonConstant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sadmin = User::where('email', 'sadmin@email.com')
            ->first();
        $sadmin->is2fa = CommonConstant::DATABASE_NO;
        $sadmin->save();

        $gen = User::where('email', 'gen@email.com')
            ->first();
        $gen->is2fa = CommonConstant::DATABASE_NO;
        $gen->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
