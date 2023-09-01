<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $user = new User();
            $user->name = 'Super User';
            $user->email = 'sadmin@email.com';
            $user->type = UserRole::SUPER_USER;
            $user->password = bcrypt('123');
            $user->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
             $user = User::where('email', 'sadmin@email.com')->first();
             $user->delete();
        });
    }
};
