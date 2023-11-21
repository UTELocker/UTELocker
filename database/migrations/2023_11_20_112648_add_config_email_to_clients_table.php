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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('email_mailer')->nullable();
            $table->string('email_host')->nullable();
            $table->string('email_port')->nullable();
            $table->string('email_username')->nullable();
            $table->string('email_password')->nullable();
            $table->string('email_encryption')->nullable();
            $table->string('email_from_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'email_host',
                'email_port',
                'email_username',
                'email_password',
                'email_encryption',
                'email_from_address',
                'email_mailer'
            ]);
        });
    }
};
