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
        Schema::create('help_call_comments', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->foreignId('help_call_id')->constrained('help_calls');
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_call_comments');
    }
};
