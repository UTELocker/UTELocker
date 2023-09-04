<?php

use App\Models\User;
use App\Models\Wallet;
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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('wallet_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });

        DB::transaction(function () {
            User::whereNotNull('client_id')->each(function (User $user) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                ]);

                $user->wallet_id = $wallet->id;
                $user->save();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wallet_id');
        });
    }
};
