<?php

use App\Classes\CommonConstant;
use App\Enums\UserGender;
use App\Enums\UserRole;
use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->string('name');
            $table->string('email')->nullable();
            $table->tinyInteger('type')->default(UserRole::NORMAL);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->tinyInteger('gender')->default(UserGender::OTHER);
            $table->string('locale')->default('en');
            $table->char('active')->default(CommonConstant::DATABASE_YES);
            $table->char('login')->default(CommonConstant::DATABASE_YES);
            $table->text('onesignal_player_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
