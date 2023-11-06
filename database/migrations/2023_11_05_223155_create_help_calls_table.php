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
        Schema::create('help_calls', function (Blueprint $table) {
            $table->id();
            $table->string('src_id', 255)->nullable();
            $table->string('help_call_src', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('type');
            $table->string('title', 255);
            $table->longText('content');
            $table->longText('attachment')->nullable();
            $table->string('priority', 255)->nullable();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('supporter_id')->nullable()->constrained('users');
            $table->foreignId('location_id')->nullable()->constrained('locations');
            $table->foreignId('help_call_std_problems_id')->nullable()->constrained('help_call_std_problems');
            $table->timestamp('log_created_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_calls');
    }
};
