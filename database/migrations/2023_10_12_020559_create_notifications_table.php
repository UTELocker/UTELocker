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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('parent_table')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('type');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('owner_id')->nullable()->constrained('users');
            $table->text('content');
            $table->char('status')->default(\App\Classes\CommonConstant::DATABASE_NO);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
